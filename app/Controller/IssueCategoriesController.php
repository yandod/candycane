<?php
class IssueCategoriesController extends AppController
{
#  menu_item :settings
#  before_filter :find_project, :authorize
#  
#  verify :method => :post, :only => :destroy
#
  function edit()
  {
    if ($this->request->data) {
      $this->request->data['IssueCategory']['id'] = $this->request->params['id'];
      $this->request->data['IssueCategory']['project_id'] = $this->_project['Project']['id'];
      if ($this->IssueCategory->save($this->request->data,true,array('name','assigned_to_id'))){
        $this->Session->setFlash(__('Successful update.'), 'default', array('class'=>'flash flash_notice'));
        $this->redirect(aa('controller','projects','action','settings','project_id',$this->_project['Project']['identifier'],'?','tab=categories'));
      }
    }
    $issue_category_data = $this->IssueCategory->find('first',aa('conditions',aa('IssueCategory.id',$this->request->params['id'])));
    $this->set('issue_category_data',$issue_category_data);
  }
#
  function destroy()
  {
#    @issue_count = @category.issues.size
#    if @issue_count == 0
#      # No issue assigned to this category
#      @category.destroy
#      redirect_to :controller => 'projects', :action => 'settings', :id => @project, :tab => 'categories'
#    elsif params[:todo]
#      reassign_to = @project.issue_categories.find_by_id(params[:reassign_to_id]) if params[:todo] == 'reassign'
#      @category.destroy(reassign_to)
#      redirect_to :controller => 'projects', :action => 'settings', :id => @project, :tab => 'categories'
#    end
    App::uses('Issue', 'Model');
    $Issue = new Issue();
    $issue_count = $Issue->find('count',aa('conditions',aa('category_id',$this->request->params['id'])));
    
    if ($issue_count == 0) {
      $this->IssueCategory->del($this->request->params['id']);
      $this->redirect(aa('controller','projects','action','settings','project_id',$this->_project['Project']['identifier'],'?','tab=categories'));
    } elseif ( $this->request->data ) {
      $reassgin_to = null;
      if ($this->request->data['IssueCategory']['todo'] == 'reassgin_to') {
        $reassgin_to = $this->request->data['IssueCategory']['reassign_to_id'];
      }
      $this->IssueCategory->del_with_reassgin($this->request->params['id'],$reassgin_to);
      $this->redirect(aa('controller','projects','action','settings','project_id',$this->_project['Project']['identifier'],'?','tab=categories'));
    }
    $issue_category_data = $this->IssueCategory->find('first',aa('conditions',aa('IssueCategory.id',$this->request->params['id'])));
    $this->set('issue_category_data',$issue_category_data);    
    $this->set('issue_count',$issue_count);
  }
#
#private
#  def find_project
#    @category = IssueCategory.find(params[:id])
#    @project = @category.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end    
}
