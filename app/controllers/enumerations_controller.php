<?php
class EnumerationsController extends AppController {
#  before_filter :require_admin
#  
  function index(){
    $this->getlist();
    $this->render('list');
  }
#
#  # GETs should be safe (see http://www.w3.org/2001/tag/doc/whenToUseGet.html)
#  verify :method => :post, :only => [ :destroy, :create, :update ],
#         :redirect_to => { :action => :list }
#
  function getlist(){
    
  }
  
#
#  def new
#    @enumeration = Enumeration.new(:opt => params[:opt])
#  end
#
   function add(){
#    @enumeration = Enumeration.new(params[:enumeration])
#    if @enumeration.save
#      flash[:notice] = l(:notice_successful_create)
#      redirect_to :action => 'list', :opt => @enumeration.opt
#    else
#      render :action => 'new'
#    end
     if ($this->data) {
       $listBehavior = ClassRegistry::getObject('ListBehavior');
       $listBehavior->settings['Enumeration']['scope'] = "Enumeration.opt = '{$this->params['named']['opt']}'";
       
       if ($this->Enumeration->save($this->data)) {
           $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
           $this->redirect('index');
         }
     }
     $this->set('options',$this->Enumeration->OPTIONS);
     $this->set('opt',$this->params['named']['opt']);
     $this->render('new');
   }
#
#  def edit
#    @enumeration = Enumeration.find(params[:id])
#  end
#
#  def update
#    @enumeration = Enumeration.find(params[:id])
#    if @enumeration.update_attributes(params[:enumeration])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :action => 'list', :opt => @enumeration.opt
#    else
#      render :action => 'edit'
#    end
#  end
#
  function move($id) {
    $this->Enumeration->read(null, $id);
    if(!empty($this->params['named']['position'])) {
      $listBehavior = ClassRegistry::getObject('ListBehavior');
      $listBehavior->settings['Enumeration']['scope'] = "Enumeration.opt = '{$this->params['named']['opt']}'";
      switch($this->params['named']['position']) {
      case 'highest' :
        $this->Enumeration->move_to_top();
        break;
      case 'higher' :
        $this->Enumeration->move_higher();
        break;
      case 'lower' :
        $this->Enumeration->move_lower();
        break;
      case 'lowest' :
        $this->Enumeration->move_to_bottom();
        break;
      }
    }  
    $this->redirect('index');
  }
  
#  def destroy
#    @enumeration = Enumeration.find(params[:id])
#    if !@enumeration.in_use?
#      # No associated objects
#      @enumeration.destroy
#      redirect_to :action => 'index'
#    elsif params[:reassign_to_id]
#      if reassign_to = Enumeration.find_by_opt_and_id(@enumeration.opt, params[:reassign_to_id])
#        @enumeration.destroy(reassign_to)
#        redirect_to :action => 'index'
#      end
#    end
#    @enumerations = Enumeration.get_values(@enumeration.opt) - [@enumeration]
#  #rescue
#  #  flash[:error] = 'Unable to delete enumeration'
#  #  redirect_to :action => 'index'
#  end
#end
}