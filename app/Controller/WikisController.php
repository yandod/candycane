<?php

class WikisController extends AppController
{
    var $uses = array('Wiki', 'Project', 'User');
    var $components = array('RequestHandler');
    public $helpers = array(
        'Js' => 'Prototype'
    );

    function edit()
    {
        if ($this->request->data) {
            $this->request->data['Wiki']['id'] = isset($this->_project['Wiki']['id']) ? $this->_project['Wiki']['id'] : null;
            $this->request->data['Wiki']['project_id'] = $this->_project['Project']['id'];
            $this->request->data['Wiki']['status'] = 1;
            $this->Wiki->save($this->request->data);
        }
        $this->render('/Elements/projects/settings/wiki');
    }


#class WikisController < ApplicationController
#  menu_item :settings
#  before_filter :find_project, :authorize
#  
#
#  # Delete a project's wiki
    function destroy()
    {
        if ($this->request->data && isset($this->request->data['Wiki']['confirm'])) {
            $this->Project->Wiki->delete($this->_project['Wiki']['id']);
            $this->redirect(
                array(
                    'controller' => 'projects',
                    'action' => 'settings',
                    'project_id' => $this->request->params['project_id'],
                    '?' => 'tab=wiki'
                )
            );
        }
    }
#  
#private
#  def find_project
#    @project = Project.find(params[:id])
#  rescue ActiveRecord::RecordNotFound
#    render_404
#  end
#end
}