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
#  def new
#    @tracker = Tracker.new(params[:tracker])
#    if request.post? and @tracker.save
#      # workflow copy
#      if !params[:copy_workflow_from].blank? && (copy_from = Tracker.find_by_id(params[:copy_workflow_from]))
#        @tracker.workflows.copy(copy_from)
#      end
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list'
#    end
#    @trackers = Tracker.find :all, :order => 'position'
#  end
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
#  def destroy
#    @tracker = Tracker.find(params[:id])
#    unless @tracker.issues.empty?
#      flash[:error] = "This tracker contains issues and can\'t be deleted."
#    else
#      @tracker.destroy
#    end
#    redirect_to :action => 'list'
#  end  
#end
}