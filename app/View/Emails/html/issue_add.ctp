<?php $this->loadHelper('Candy'); ?>
<?php echo $this->Candy->lwr(
	'Issue %s has been reported by %s.',
	$issue['Issue']['id'],
	$this->Candy->format_username($issue['Author'])
); ?>
<hr />
<?php echo $this->element('mailer/issue_text_html', array(
	'issue' => $issue,
	'issue_url' => $issueurl
)); ?>