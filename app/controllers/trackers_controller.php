<?php

class TrackersController extends AppController
{
#  before_filter :require_admin
#
  function index(){
     $this->getlist();
#    render :action => 'list' unless request.xhr?
    $this->render('list');
  }
#
#  # GETs should be safe (see http://www.w3.org/2001/tag/doc/whenToUseGet.html)
#  verify :method => :post, :only => [ :destroy, :move ], :redirect_to => { :action => :list }
#
  function getlist(){
    $param = array(
      'order' => 'position'
    );
    $this->set('trackers',$this->Tracker->find('all',$param));
#    @tracker_pages, @trackers = paginate :trackers, :per_page => 10, :order => 'position'
#    render :action => "list", :layout => false if request.xhr?
  }
#
  function add() {
    $param = array(
      'order' => 'position'
    );
    $this->set('trackers',$this->Tracker->find('list',$param));

    if(!empty($this->data)) {
      $this->Tracker->create();
      if($this->Tracker->save($this->data)) {
        if (!empty($this->data['Tracker']['copy_workflow_from'])) {
          $this->Tracker->workflow_copy($this->data['Tracker']['copy_workflow_from']);
        }
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect('index');
      } else {
        $this->Session->setFlash(__('Please correct errors below.', true), 'default', array('class'=>'flash flash_error'));
      }
    }
#    if request.post? and @tracker.save
#      # workflow copy
#      if !params[:copy_workflow_from].blank? && (copy_from = Tracker.find_by_id(params[:copy_workflow_from]))
#        @tracker.workflows.copy(copy_from)
#      end
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list'
#    end
    $this->render('new');
  }
#
#  def edit
#    @tracker = Tracker.find(params[:id])
#    if request.post? and @tracker.update_attributes(params[:tracker])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list'
#    end
#  end
#
#  def move
#    @tracker = Tracker.find(params[:id])
#    case params[:position]
#    when 'highest'
#      @tracker.move_to_top
#    when 'higher'
#      @tracker.move_higher
#    when 'lower'
#      @tracker.move_lower
#    when 'lowest'
#      @tracker.move_to_bottom
#    end if params[:position]
#    redirect_to :action => 'list'
#  end
#  
  function destroy($id){
    $tracker = $this->Tracker->find('first',array('conditions'=>array('id'=>$id)));
    if (count($tracker['Issue']) > 0) {
      $this->Session->setFlash(__('This tracker contains issues and can\'t be deleted.', true), 'default', array('class'=>'flash flash_error'));
    } else {
       $this->Tracker->del($id);
    }
    $this->redirect('index');
  }
}