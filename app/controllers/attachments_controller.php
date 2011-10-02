<?php
## Redmine - project management software
## Copyright (C) 2006-2008  Jean-Philippe Lang
##
## This program is free software; you can redistribute it and/or
## modify it under the terms of the GNU General Public License
## as published by the Free Software Foundation; either version 2
## of the License, or (at your option) any later version.
## 
## This program is distributed in the hope that it will be useful,
## but WITHOUT ANY WARRANTY; without even the implied warranty of
## MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
## GNU General Public License for more details.
## 
## You should have received a copy of the GNU General Public License
## along with this program; if not, write to the Free Software
## Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

/**
 * Attachments Controller
 *
 * @package candycane
 * @subpackage candycane.controllers
 */
class AttachmentsController extends AppController {

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
	public function beforeFilter() {
		$this->_find_project();
		parent::beforeFilter();

		switch ($this->action) {
			case 'destroy':
				$this->_delete_authorize();
				break;
			default:
				$this->_read_authorize();
				break;
		}
		return true;
	}

/**
 * Show action
 *
 * @return void
 */
	function show() {
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
	function download() {
		$data = $this->Attachment->data[$this->Attachment->alias];
		if ($data['container_type'] == 'Version' || $data['container_type'] == 'Project') {
			$this->Attachment->increment_download();
		}
		$path = pathinfo($this->Attachment->diskfile());
		$this->view = 'Media';
		$params = array(
			'id' => $data['disk_filename'],
			'name' => basename($data['filename'], '.'.$path['extension']),
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
	function destroy() {
		// Make sure association callbacks are called
		$this->Attachment->del();
		if ($this->referer(false)) {
			$this->redirect($this->referer());
		} else {
			$this->redirect(array('controller' => 'projects', 'action' => 'show', 'id' => $this->params['project_id']));
		}
	}

/**
 * Find a project
 *
 * @return void
 * @access private
 */
	function _find_project() {
		$this->Attachment->read(null, $this->params['id']);
		$this->set('attachment', $this->Attachment->data[$this->Attachment->alias]);
		$this->set('author', $this->Attachment->data['Author']);

		// Show 404 if the filename in the url is wrong
		if (!empty($this->params['filename']) && $this->params['filename'] != $this->Attachment->data[$this->Attachment->name]['filename']) {
			$this->cakeError('error404');
		}
		$project = $this->Attachment->project();
		if (!empty($project['Project']['identifier'])) {
			$this->params['project_id'] = $project['Project']['identifier'];
			parent::_findProject();
		} else {
			$this->cakeError('error404');
		}
	}

/**
 * Check for read authorization
 *
 * @return boolean True if user can read
 */
	function _read_authorize() {
		$this->Attachment->is_visible($this->current_user, $this->_project) ?  true : $this->deny_access();
	}

/**
 * Check for delete authorization
 *
 * @return boolean True if user can delete
 */
	function _delete_authorize() {
		$this->Attachment->is_deletable($this->current_user, $this->_project) ?  true : $this->deny_access();
	}
}
