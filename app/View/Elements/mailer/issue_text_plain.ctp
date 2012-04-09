<?php echo "{$issue['Tracker']['name']} #{$issue['Issue']['id']} {$issue['Issue']['subject']}";?> 
<?php echo $issue_url;?> 
<?php echo __('Author')?>: <?php echo $this->Candy->format_username($issue['Author']); ?> 
<?php echo __('Status')?>: <?php echo $issue['Status']['name']; ?> 
<?php echo __('Priority')?>: <?php echo $issue['Priority']['name']; ?> 
<?php echo __('Assigned To')?>: <?php echo $this->Candy->format_username($issue['AssignedTo']); ?> 
<?php echo __('Category')?>: <?php echo $issue['Category']['name']; ?> 
<?php echo __('Fixed Version')?>: <?php echo $issue['FixedVersion']['name']; ?> 
<?php if(isset($issue['CustomValue']) && is_array($issue['CustomValue'])): ?>
<?php foreach ($issue['CustomValue'] as $c): ?>
<?php $c['CustomField']['name'];　?>: <?php echo $c['value'] ?>
<?php endforeach; ?>　
<?php endif; ?>
<?php echo $issue['Issue']['description']?>　
