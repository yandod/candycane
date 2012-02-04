<?php
class Like extends CcLikeItAppModel {
	public $useTable = false;

	public function likeIt($issue_id,$currentuser) {
		if (!$currentuser['logged']) {
			return false;
		}

		$journal = ClassRegistry::init('Journal');

		$liked = $journal->getLiked($issue_id,$currentuser['id']);
		if ($liked) {
			return false;
		}

		$data = array(
			'Journal' => array(
				'journalized_id' => $issue_id,
				'journalized_type' => 'Issue',
				'user_id' => $currentuser['id']
			),
			'JournalDetail' => array(
				array(
					'property' => 'cf',
					'prop_key' => 'like',
					'old_value' => 0,
					'value' => 1
				)
			)
		);
		return $journal->saveAll($data);
	}

	public function getLiked($issue_id,$user_id = null) {
		$journal = ClassRegistry::init('Journal');
		$journal->unbindModel(array(
			'hasMany' => array('JournalDetail')
		));
		$journal->bindModel(array(
			'hasOne' => array(
				'JournalDetail' => array(
					'className' => 'JournalDetail',
					'type' => 'inner',
					'conditions' => array(
						'JournalDetail.prop_key' => 'like',
						'JournalDetail.value' => '1'
					)
				)
			)
		));

		$conditions = array(
			'Journal.journalized_id' => $issue_id,
			'Journal.journalized_type' => 'Issue'
		);

		if ($user_id) {
			$conditions['Journal.user_id'] = $user_id;
		}
		$count = $journal->find('count',array(
			'conditions' => $conditions
		));
		return $count;
	}
}
