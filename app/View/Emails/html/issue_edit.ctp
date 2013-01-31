<?php $this->loadHelper('Candy'); ?>
<?php $this->loadHelper('Issues'); ?>
<?php echo $this->Candy->lwr(
	'Issue %s has been updated by %s.',
	$journal['Issue']['id'],
	$this->Candy->format_username($journal['User'])
); ?>

<ul>
<?php foreach ($journal['JournalDetail'] as $detail): ?>
	<li><?php echo $this->Issues->show_detail($detail,true); ?></li>
<?php endforeach; ?>
</ul>

<?php echo nl2br(h($journal['Journal']['notes'])); ?>
<hr />
<?php echo $this->element('mailer/issue_text_html', array(
	'issue' => $issue,
	'issue_url' => $issueurl
)); ?>