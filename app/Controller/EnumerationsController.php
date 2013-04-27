<?php

class EnumerationsController extends AppController
{
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    function index()
    {
        $this->getlist();
        $this->render('list');
    }

#
#  # GETs should be safe (see http://www.w3.org/2001/tag/doc/whenToUseGet.html)
#  verify :method => :post, :only => [ :destroy, :create, :update ],
#         :redirect_to => { :action => :list }
#
    function getlist()
    {

    }

    function add()
    {
        if ($this->request->data) {
            $listBehavior = ClassRegistry::getObject('ListBehavior');
            $listBehavior->settings['Enumeration']['scope'] = "Enumeration.opt = '{$this->request->params['named']['opt']}'";

            if ($this->Enumeration->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            }
        }
        $this->set('options', $this->Enumeration->OPTIONS);
        $this->set('opt', $this->request->params['named']['opt']);
        $this->render('new');
    }

    function edit($id)
    {
        $enumeration = $this->Enumeration->findById($id);
        $this->set('enumeration', $enumeration);
        if ($this->request->data) {
            $this->request->data['Enumeration']['id'] = $id;
            if ($this->Enumeration->save($this->request->data)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
#      redirect_to :action => 'list', :opt => @enumeration.opt
                $this->redirect('index');
            } else {
                $this->render('edit');
            }
        }
        $this->request->data = $enumeration;
    }

    function move($id)
    {
        $this->Enumeration->read(null, $id);
        if (!empty($this->request->params['named']['position'])) {
            $listBehavior = ClassRegistry::getObject('ListBehavior');
            $listBehavior->settings['Enumeration']['scope'] = "Enumeration.opt = '{$this->request->params['named']['opt']}'";
            switch ($this->request->params['named']['position']) {
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

    function destroy($id)
    {
        $param = array(
            'conditions' => array(
                'id' => $id
            )
        );
        $enumeration = $this->Enumeration->find('first', $param);
        $this->set('options', $this->Enumeration->OPTIONS);
        $this->set('enumeration', $enumeration);
        $count = $this->Enumeration->objects_count($enumeration);
        $this->set('objects_count', $count);
        if ($count == 0) {
            # No associated objects
            if ($this->Enumeration->delete($id)) {
                $this->Session->setFlash(__('Successful update.'), 'default', array('class' => 'flash flash_notice'));
                $this->redirect('index');
            }
        } else if (isset($this->request->data['Enumeration']['reassign_to_id'])) {
#      if reassign_to = Enumeration.find_by_opt_and_id(@enumeration.opt, params[:reassign_to_id])
            $this->Enumeration->destroy($enumeration, $this->request->data['Enumeration']['reassign_to_id']);
            $this->redirect('index');
#      end
        }
        $this->set('enumerations', $this->Enumeration->get_values($enumeration['Enumeration']['opt']));
#  #rescue
#  #  flash[:error] = 'Unable to delete enumeration'
#  #  redirect_to :action => 'index'
    }
#end
}
