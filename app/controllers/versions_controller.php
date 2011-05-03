<?php
class VersionsController extends AppController
{
  var $name = 'Versions';
  var $uses = array('User', 'Version', 'Wiki');
  var $helpers = array('Time');
#  menu_item :roadmap
#  before_filter :find_project, :authorize

  function show($id)
  {
    $this->Version->id = $id;
    $this->data = $this->Version->read();

    $issues = $this->Version->FixedIssue->find('all', aa('conditions', aa('fixed_version_id', $id)));
    foreach($issues as $key=>$issue) {
      $issues[$key]['Issue'] = $issue['FixedIssue'];
    }
    $this->set('issues', $issues); // @FIXME
    $fixed_issue_count = count($issues);
    $this->set('fixed_issue_count', $fixed_issue_count);
    $wiki_content = $this->Wiki->WikiPage->find('first',
                                                aa('conditions',
                                                   aa('WikiPage.title',
                                                   $this->data['Version']['wiki_page_title'])));
    $this->set('wiki_content', $wiki_content);
    /*
<% issues = @version.fixed_issues.find(:all,
                                       :include => [:status, :tracker],
                                       :order => "#{Tracker.table_name}.position, #{Issue.table_name}.id") %>
     */
  }

  function edit($id)
  {
#    if request.post? and @version.update_attributes(params[:version])
#      flash[:notice] = l(:notice_successful_update)
#      redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#    end
    $this->Version->id = $id;
    if(!empty($this->data)) {
      if($this->Version->save($this->data, true, array('name', 'description', 'wiki_page_title', 'effective_date'))) {
        $this->Session->setFlash(__('Successful update.',true));
        $this->redirect(array('controller'=>'projects', 'action'=>'settings', 'id'=>$this->version['Project']['identifier'], '?' => 'tab=versions'));
      }
    }

    if ($id !== null) {
      $this->data = $this->Version->read();
    }
  }

  function destroy($id)
  {
    if ($this->Version->del($id)) {
    } else {
      $this->Session->setFlash(__('Unable to delete version.'));
    }
    $this->redirect(array('controller'=>'versions', 'action'=>'show', 'id'=>$this->Version->id));
#    @version.destroy
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
#  rescue
#    flash[:error] = l(:notice_unable_delete_version)
#    redirect_to :controller => 'projects', :action => 'settings', :tab => 'versions', :id => @project
  }

  function status_by($id)
  {
#    respond_to do |format|
#      format.html { render :action => 'show' }
#      format.js { render(:update) {|page| page.replace_html 'status_by', render_issue_status_by(@version, params[:status_by])} }
#    end

  }

  // private
  function _findProject()
  {
    $this->version = $this->Version->find('first', array(
        'conditions' => array(
          'Version.id' => $this->params['pass'][0],
        ),
      ));
    $this->params['project_id'] = $this->version['Project']['identifier'];
    return parent::_findProject();
#    @version = Version.find(params[:id])
#    @project = @version.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end  
  }

}

