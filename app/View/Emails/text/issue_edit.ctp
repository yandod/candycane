<?php $this->loadHelper('Candy'); ?>
<?php $this->loadHelper('Issues'); ?>
<?php echo $this->Candy->lwr(
	'Issue %s has been updated by %s.',
	$journal['Issue']['id'],
	$this->Candy->format_username($journal['User'])
); ?>


<?php foreach ($journal['JournalDetail'] as $detail) {
	echo $this->Issues->show_detail($detail,true) . "\n";
} ?>


<?php echo $journal['Journal']['notes']; ?>


----------------------------------------
<?php echo $this->element('mailer/issue_text_plain', array(
	'issue' => $issue,
	'issue_url' => $issueurl
)); ?>　
