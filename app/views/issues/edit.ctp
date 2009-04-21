<h2><?php $candy->html_title();echo $issue['Tracker']['name'].' ##'.$issue['Issue']['id']; ?></h2>

<?php echo $this->renderElement('issues/edit', compact(
    'statuses', 'priorities', 'assignableUsers', 'issueCategories', 'fixedVersions', 
    'customFieldValues', 'editAllowed', 'timeEditAllowed', 'timeEntryCustomFields',
    'timeEntryActivities')); ?>


