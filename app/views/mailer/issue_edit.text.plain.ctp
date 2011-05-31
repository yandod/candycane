<?php echo $candy->lwr('Issue %s has been updated by %s.', $journal['Issue']['id'],$candy->format_username($journal['User']))?> 
<?php foreach ($journal['JournalDetail'] as $detail) {
	echo $issues->show_detail($detail,true)."\n";
}?> 
<?php echo $journal['Journal']['notes'];?>

----------------------------------------
<?php echo $this->renderElement('mailer/issue_text_plain',array('issue' => $issue, 'issue_url' => $issueurl)) ?>　
