<?php

/**
 * Attachments Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 */
class AttachmentsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Attachments';

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array(
        'UnifiedDiff',
        'Number',
    );

    /**
     * BeforeFilter
     *
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->_find_project();

        switch ($this->request->action) {
            case 'destroy':
                $this->_delete_authorize();
                break;
            default:
                $this->_read_authorize();
                break;
        }
    }

    /**
     * Show action
     *
     * @return void
     */
    function show()
    {
        if ($this->Attachment->is_diff()) {
            $diff = @file($this->Attachment->diskfile());
            $this->set('diff', $diff);
            $this->set('diff_type', $this->_get_param('diff_type'));

            $this->render('diff');
        } elseif ($this->Attachment->is_text()) {
            $content = @file($this->Attachment->diskfile());
            $this->set('content', $content);
            $this->render('file');
        } else {
            $this->download();
        }
    }

    /**
     * Download action
     *
     * @return void
     */
    function download()
    {
        $data = $this->Attachment->data[$this->Attachment->alias];
        if ($data['container_type'] == 'Version' || $data['container_type'] == 'Project') {
            $this->Attachment->increment_download();
        }
        $path = pathinfo($this->Attachment->diskfile());
        $this->viewClass = 'Media';
        $params = array(
            'id' => $data['disk_filename'],
            'name' => basename($data['filename'], '.' . $path['extension']),
            'download' => !$this->Attachment->is_image(),
            'path' => $path['dirname'] . DS,
            'extension' => $path['extension'],
        );
        $this->set($params);
    }

    /**
     * Destroy action
     *
     * @return void
     */
    function destroy()
    {
        // Make sure association callbacks are called
        $this->Attachment->delete();
        if ($this->referer(false)) {
            $this->redirect($this->referer());
        } else {
            $this->redirect(array('controller' => 'projects', 'action' => 'show', 'id' => $this->request->params['project_id']));
        }
    }

    /**
     * Find a project
     *
     * @return void
     * @access private
     */
    function _find_project()
    {
        $attachment = $this->Attachment->read(null, $this->request->params['id']);
        $this->set('attachment', $attachment[$this->Attachment->alias]);
        $this->set('author', $attachment[$this->Attachment->Author->alias]);

        // Show 404 if the filename in the url is wrong
        if (!empty($this->request->params['filename']) && $this->request->params['filename'] != $attachment[$this->Attachment->alias]['filename']) {
            throw new NotFoundException();
        }
        $project = $this->Attachment->project();
        if (!empty($project['Project']['identifier'])) {
            $this->request->params['project_id'] = $project['Project']['identifier'];
            parent::_findProject();
        } else {
            throw new NotFoundException();
        }
    }

    /**
     * Check for read authorization
     *
     * @return boolean True if user can read
     */
    function _read_authorize()
    {
        $this->Attachment->is_visible($this->current_user, $this->_project) ? true : $this->deny_access();
    }

    /**
     * Check for delete authorization
     *
     * @return boolean True if user can delete
     */
    function _delete_authorize()
    {
        $this->Attachment->is_deletable($this->current_user, $this->_project) ? true : $this->deny_access();
    }
}