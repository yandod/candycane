<?php

class TrackersController extends AppController
{
    function beforeFilter()
    {
        parent::beforeFilter();
        return parent::require_admin();
    }

    function index()
    {
        $this->getlist();
#    render :action => 'list' unless request.xhr?
        $this->render('list');
    }

#
#  # GETs should be safe (see http://www.w3.org/2001/tag/doc/whenToUseGet.html)
#  verify :method => :post, :only => [ :destroy, :move ], :redirect_to => { :action => :list }
#
    function getlist()
    {
        $param = array(
            'order' => 'position'
        );
        $this->set('trackers', $this->Tracker->find('all', $param));
#    @tracker_pages, @trackers = paginate :trackers, :per_page => 10, :order => 'position'
#    render :action => "list", :layout => false if request.xhr?
    }

#
    function add()
    {
        $param = array(
            'order' => 'position'
        );
        $this->set('trackers', $this->Tracker->find('list', $param));

        if (!empty($this->request->data)) {
            $this->Tracker->create();
            if ($this->Tracker->save($this->request->data)) {
                if (!empty($this->request->data['Tracker']['copy_workflow_from'])) {
                    $this->Tracker->workflow_copy($this->request->data['Tracker']['copy_workflow_from']);
                }
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            } else {
                $this->Session->setFlash(__('Please correct errors below.'), 'default', array('class' => 'flash flash_error'));
            }
        }
        $this->render('new');
    }

    function edit($id)
    {
        if (!empty($this->request->data)) {
            $this->Tracker->save($this->request->data);
            $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
            $this->redirect('index');
        }

        $this->request->data = $this->Tracker->read(null, $id);
    }

    function move($id)
    {
        $this->Tracker->read(null, $id);
        if (!empty($this->request->params['named']['position'])) {
            switch ($this->request->params['named']['position']) {
                case 'highest' :
                    $this->Tracker->move_to_top();
                    break;
                case 'higher' :
                    $this->Tracker->move_higher();
                    break;
                case 'lower' :
                    $this->Tracker->move_lower();
                    break;
                case 'lowest' :
                    $this->Tracker->move_to_bottom();
                    break;
            }
        }
        $this->redirect('index');
    }

    function destroy($id)
    {
        $tracker = $this->Tracker->find('first', array('conditions' => array('id' => $id)));
        if (count($tracker['Issue']) > 0) {
            $this->Session->setFlash(__('This tracker contains issues and can\'t be deleted.'), 'default', array('class' => 'flash flash_error'));
        } else {
            $this->Tracker->delete($id);
        }
        $this->redirect('index');
    }
}