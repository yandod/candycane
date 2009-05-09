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
    if ($this->data) {
      $this->data['IssueCategory']['id'] = $this->params['id'];
      $this->data['IssueCategory']['project_id'] = $this->_project['Project']['id'];
      if ($this->IssueCategory->save($this->data,true,array('name','assigned_to_id'))){
        $this->Session->setFlash(__('Successful update.', true), 'default', array('class'=>'flash flash_notice'));
        $this->redirect(aa('controller','projects','action','settings','project_id',$this->_project['Project']['identifier'],'?','tab=categories'));
      }
    }
    $issue_category_data = $this->IssueCategory->find('first',aa('conditions',aa('IssueCategory.id',$this->params['id'])));
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
    $this->IssueCategory->del($this->params['id']);
    $this->redirect(aa('controller','projects','action','settings','project_id',$this->_project['Project']['identifier'],'?','tab=categories'));
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
