<?php
	class HomeController extends CcLikeItAppController {

	public $uses = array('CcLikeIt.Like');

	public $layout = null;

	public function index() {
		$issue_id = $this->params['named']['issue_id'];
		$count = $this->Like->getLiked($issue_id);
		$liked = $this->Like->getLiked($issue_id,$this->current_user['id']);
		$this->set('count',$count);
		$this->set('liked',$liked);
	}

	public function like() {
		$issue_id = $this->params['named']['issue_id'];
		$this->Like->likeIt($issue_id,$this->current_user);
		$this->redirect(array(
			'action' => 'index',
			'issue_id' => $this->params['named']['issue_id']
		));
	}

}

