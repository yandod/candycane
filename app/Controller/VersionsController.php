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
        $this->request->data = $this->Version->read();

        $issues = $this->Version->FixedIssue->find('all', array(
            'conditions' => array(
                'fixed_version_id' => $id
            )
        ));
        foreach ($issues as $key => $issue) {
            $issues[$key]['Issue'] = $issue['FixedIssue'];
        }
        $this->set('issues', $issues); // @FIXME
        $fixed_issue_count = count($issues);
        $this->set('fixed_issue_count', $fixed_issue_count);
        $wiki_content = $this->Wiki->WikiPage->find('first', array(
            'conditions' => array(
                'WikiPage.title' => $this->request->data['Version']['wiki_page_title']
            )
        ));
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
        if (!empty($this->request->data)) {
            if (empty($this->request->data['Version']['effective_date'])) {
                $this->request->data['Version']['effective_date'] = null;
            }
            if ($this->Version->save($this->request->data, true, array('name', 'description', 'wiki_page_title', 'effective_date'))) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash notice'));
                $this->redirect(array(
                    'controller' => 'projects',
                    'action' => 'settings',
                    'project_id' => $this->version['Project']['identifier'],
                    '?' => 'tab=versions',
                ));
            }
        }

        if ($id !== null) {
            $this->request->data = $this->Version->read();
        }
    }

    public function destroy($id)
    {
        if ($this->Version->delete($id)) {
            $this->Session->setFlash(
                __('Successful update.'),
                'default',
                array('class' => 'flash notice')
            );
        } else {
            $this->Session->setFlash(__('Unable to delete version.'));
        }
        $this->redirect(array(
            'controller' => 'projects',
            'action' => 'settings',
            'project_id' => $this->version['Project']['identifier'],
            '?' => 'tab=versions',
        ));
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
                'Version.id' => $this->request->params['pass'][0],
            ),
        ));
        $this->request->params['project_id'] = $this->version['Project']['identifier'];
        return parent::_findProject();
#    @version = Version.find(params[:id])
#    @project = @version.project
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end  
    }
}