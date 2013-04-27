<?php

class JournalsController extends AppController
{
    public $name = 'Journals';
    public $components = array(
        'RequestHandler',
    );

    public $helpers = array(
        'Journals',
        'Js' => array('Prototype')
    );

    public function edit($id)
    {
        if ($this->RequestHandler->isAjax()) {
            $this->layout = 'ajax';
            Configure::write('debug', 0);
        }
        $journal = $this->_find_journal($id);
        $this->set(compact('journal'));
        $delete = false;
        if (!empty($journal) && !empty($this->request->data)) {
            if (empty($journal['JournalDetails']) && ($this->request->data['Journal']['notes'] == '')) {
                $delete = $this->Journal->delete($id);
            } else {
                $this->Journal->saveField('notes', $this->request->data['Journal']['notes']);
            }
            $this->set(compact('delete'));

            $event = new CakeEvent(
                'Controller.Candy.journalsEditPost',
                $this,
                array(
                    'jornal' => $this->request->data
                )
            );
            $this->getEventManager()->dispatch($event);

            if ($this->RequestHandler->isAjax()) {
                $this->render('update');
            } else {
                $this->redirect(
                    array(
                        'controller' => 'issues',
                        'action' => 'show',
                        $journal['Journal']['journalized_id']
                    )
                );
            }
        } else {
            $this->request->data = $journal;
        }
    }

    public function _find_journal($id)
    {
        $this->Journal->recursive = 1;
        $journal = $this->Journal->read(null, $id);
        if (empty($journal)) {
            throw new NotFoundException();
        }
        if (!$this->Journal->is_editable_by($this->current_user)) {
            throw new NotFoundException();
        }
        return $journal;
    }
}