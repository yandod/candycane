<?php echo $candy->lwr('Issue %s has been reported by %s.', $issue['Issue']['id'],$candy->format_username($issue['Author']))?> 

----------------------------------------
<?php echo $this->renderElement('mailer/issue_text_plain',array('issue' => $issue, 'issue_url' => $issueurl)) ?>　
