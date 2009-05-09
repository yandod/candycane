<?php
class WikisController extends AppController {
  var $uses = array('Wiki', 'Project', 'User');
  var $components = array('RequestHandler');

  function edit() {
    if($this->data) {
      $this->data['Wiki']['id'] = isset($this->_project['Wiki']['id']) ? $this->_project['Wiki']['id'] : null;
      $this->data['Wiki']['project_id'] = $this->_project['Project']['id'];
      $this->data['Wiki']['status'] = 1;
      $this->Wiki->save($this->data);
    }
    $this->render('/elements/projects/settings/wiki');  
  }


#class WikisController < ApplicationController
#  menu_item :settings
#  before_filter :find_project, :authorize
#  
#
#  # Delete a project's wiki
  function destroy()
  {
    if ($this->data && isset($this->data['Wiki']['confirm'])) {
      $this->Project->Wiki->del($this->_project['Wiki']['id']);    
      $this->redirect(aa('controller','projects','action','settings','id',$this->params['project_id'],'?','tab=wiki'));
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