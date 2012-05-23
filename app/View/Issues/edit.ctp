<h2><?php $this->Candy->html_title();echo $issue['Tracker']['name'].' ##'.$issue['Issue']['id']; ?></h2>

<?php echo $this->element('issues/edit', compact(
    'statuses', 'priorities', 'assignableUsers', 'issueCategories', 'fixedVersions', 
    'customFieldValues', 'timeEntryCustomFields', 'timeEntryActivities')); ?>


