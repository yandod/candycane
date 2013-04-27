<?php

class CustomFieldsController extends AppController
{
    var $name = 'CustomFields';
    var $components = array(
        'RequestHandler',
    );
    var $helpers = array(
        'CustomField',
    );

    public $uses = array(
        'CustomField',
        'CustomValue',
    );

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    function index()
    {
        $custom_fields_by_type = $this->CustomField->group_by($this->CustomField->find('all'), 'type');
        $this->CustomField->count_project($custom_fields_by_type);
        $tab = $this->_get_param('tab');
        if (empty($tab)) {
            $tab = 'IssueCustomField';
        }

        $this->set('selected_tab', $tab);
        $this->set('custom_fields_by_type', $custom_fields_by_type);
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
        }
        $this->render("list");
    }

    function add()
    {
        if (!in_array($this->_get_param('type'), array('IssueCustomField', 'UserCustomField', 'ProjectCustomField', 'TimeEntryCustomField'))) {
            $this->redirect('index');
        }
        $this->CustomField->bindModel(array('hasMany' => array('CustomFieldsTracker')), false);
        $custom_field = array($this->CustomField->name => array(
            'type' => $this->_get_param('type'),
        ));
        if (!empty($this->request->data)) {
            $this->CustomField->set($this->request->data);
            if ($this->CustomField->save()) {
                $event = new CakeEvent(
                    'Controller.Candy.customFieldsNewAfterSave',
                    $this,
                    array(
                        'custom_field' => $this->request->data
                    )
                );
                $this->getEventManager()->dispatch($event);

                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect(array('action' => 'index', '?' => array('tab' => $this->_get_param('type'))));
            }
        } else {
            $this->request->data = $custom_field;
        }
        if (($this->_get_param('type') == "IssueCustomField") && $this->_get_param('tracker_ids')) {
            $custom_field['Tracker'] = $this->CustomFieldsTracker->Tracker . find('list', array('conditions' => array('id' => $this->_get_param('tracker_ids'))));
        }
        $Tracker = ClassRegistry::init('Tracker');
        $this->set('trackers', $Tracker->find('list', array('order' => 'position')));
        $this->set('custom_field', $custom_field);
        $this->render("new");
    }

    function edit($id)
    {
        $this->CustomField->bindModel(array('hasMany' => array('CustomFieldsTracker')), false);
        $custom_field = $this->CustomField->read(null, $id);
        if (!empty($this->request->data)) {
            $this->CustomField->set($this->request->data);
            if ($this->CustomField->save()) {
                $event = new CakeEvent(
                    'Controller.Candy.customFieldsEditAfterSave',
                    $this,
                    array(
                        'custom_field' => $this->request->data
                    )
                );
                $this->getEventManager()->dispatch($event);

                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect(array('action' => 'index', '?' => array('tab' => $this->CustomField->data[$this->CustomField->alias]['type'])));
            }
        } else {
            $this->request->data = $custom_field;
        }
        $Tracker = ClassRegistry::init('Tracker');
        $this->set('trackers', $Tracker->find('list', array('order' => 'position')));
        $this->set('custom_field', $custom_field);
    }

    function move($id)
    {
        $this->CustomField->read(null, $id);
        if (!empty($this->request->params['named']['position'])) {
            switch ($this->request->params['named']['position']) {
                case 'highest' :
                    $this->CustomField->move_to_top();
                    break;
                case 'higher' :
                    $this->CustomField->move_higher();
                    break;
                case 'lower' :
                    $this->CustomField->move_lower();
                    break;
                case 'lowest' :
                    $this->CustomField->move_to_bottom();
                    break;
            }
            $this->redirect(array('action' => 'index', '?' => array('tab' => $this->CustomField->data[$this->CustomField->alias]['type'])));
        }
    }

    function destroy($id)
    {
        $this->CustomField->read(null, $id);
        $this->CustomValue->deleteAll(
            array(
                'CustomValue.custom_field_id' => $id
            )
        );
        if ($this->CustomField->delete()) {
            $this->Session->setFlash(__('Successful deletion.'), 'default', array('class' => 'flash flash_notice'));
            $this->redirect(array('action' => 'index', '?' => array('tab' => $this->CustomField->data[$this->CustomField->alias]['type'])));
        } else {
            $this->Session->setFlash(__('Unable to delete custom field'), 'default', array('class' => 'flash flash_error'));
            $this->redirect('index');
        }
    }
}