<?php echo $this->element(
    'watchers/watchers',
    array(
        'list' => !empty($data['Watcher'])?$data['Watcher']:array(), 
        'object_type' => $object_type, 
        'watched' => $object_id, 
        'members' => $members,
        'addIssueWatchersAllowed' => $this->Candy->authorize_for(':add_issue_watchers')
    )
	//'Watchers'
); ?>
