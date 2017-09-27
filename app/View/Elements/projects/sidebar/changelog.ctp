<?php echo $this->Form->create() ?>
<h3><?php echo __('Change log') ?></h3>
<ul>
<?php foreach($trackers as $tracker): ?>
  <li>
  	<?php echo $this->Form->input('tracker_ids[]', 
  		array(
  			'type'=>'checkbox', 
  			'value' => $tracker['Tracker']['id'], 
  			'label' => $tracker['Tracker']['name'],
  			'div' => false
  	)); ?>
  </li>
<?php endforeach ?>
</ul>
<p><?php echo $this->Form->submit(__('Apply'), array('class' => 'button-small')) ?></p>
<?php echo $this->Form->end() ?>

<h3><?php echo __('Versions') ?></h3>
<?php foreach($versions as $version): ?>
<?php echo $this->Html->link($version['name'], '#'.$version['name']) ?><br />
<?php endforeach ?>

