<?php

class AdminController extends AppController
{
    var $name = 'Admin';
    var $uses = array('Project');
    var $helpers = array('Candy');
    var $components = array('Sort', 'Mailer');

    /**
     * beforeFilter
     *
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->require_admin();
    }

    public function index()
    {
    }

    /**
     * projects
     *
     */
    public function projects()
    {
        $this->Sort->sort_init('name', 'asc');
        $this->Sort->sort_update(
            array('name', 'is_public', 'created_on')
        );

        if (isset($this->request->query['status'])) {
            $status = (int)$this->request->query['status'];
        } else {
            $status = 1;
        }

        $this->set('status', $status);

        $status_options = array(
            '' => __('all'),
            1 => __('active'),
        );

        $this->set('status_options', $status_options);

        if ($status == '1') {
            $condition = array('Project.status' => $status);
        } else {
            $condition = array();
        }

        $name = null;
        if (!empty($this->request->query['name'])) {
            $name = $this->request->query['name'];
            $q_name = "%{$name}%";
            $condition['LOWER(Project.identifier) LIKE ? OR LOWER(Project.name) LIKE ?'] = array($q_name, $q_name);
        }

        $this->set('name', $name);

        // @todo fix limit count
        $projects = $this->Project->find('all',
            array(
                'recursive' => 0,
                'conditions' => $condition,
            )
        );

        $this->set('projects', $projects);
    }

    public function plugins()
    {
        $pluginContainer = ClassRegistry::getObject('PluginContainer');
        $pluginContainer->fetchEntry();
        $this->set('plugins', $pluginContainer->getEntries());
    }

    public function installPlugin($id)
    {
        $pluginContainer = ClassRegistry::getObject('PluginContainer');
        $pluginContainer->fetchEntry();
        if ($pluginContainer->install($id)) {
            $this->Session->setFlash(sprintf(__('Installed plugin: %s'), $id), 'default', array('class' => 'flash flash_notice'));
        }
        $this->redirect('plugins');
    }

    public function upgradePlugin($id)
    {
        $pluginContainer = ClassRegistry::getObject('PluginContainer');
        $pluginContainer->fetchEntry();
        if ($pluginContainer->upgrade($id)) {
            $this->Session->setFlash(
                sprintf(__('Upgrade plugin: %s'), $id),
                'default',
                array('class' => 'flash flash_notice')
            );
        }
        $this->redirect('plugins');
    }

    public function uninstallPlugin($id)
    {
        $pluginContainer = ClassRegistry::getObject('PluginContainer');
        $pluginContainer->fetchEntry();
        if ($pluginContainer->uninstall($id)) {
            $this->Session->setFlash(sprintf(__('Uninstalled plugin: %s'), $id), 'default', array('class' => 'flash flash_notice'));
        }
        $this->redirect('plugins');
    }

    public function default_configration()
    {
    }

    public function test_email()
    {
        if ($this->Mailer->deliver_test($this->current_user)) {
            $this->Session->setFlash(sprintf(__('An email was sent to %s'), $this->current_user['mail']), 'default', array('class' => 'flash flash_notice'));
        } else {
            $this->Session->setFlash(sprintf(__('An error occurred while sending mail (%s)'), $this->current_user['mail']), 'default', array('class' => 'flash flash_error'));
        }
        $this->redirect(array(
            'controller' => 'settings',
            'action' => 'edit',
            'tab' => 'notifications'
        ));
    }

    /**
     * info
     *
     */
    public function info()
    {
        $db =& ConnectionManager::getDataSource($this->Project->useDbConfig);
        $this->set('db_driver', $db->config['datasource']);
    }
}
