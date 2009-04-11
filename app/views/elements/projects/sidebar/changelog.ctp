<?php echo $form->create() ?>
<h3><?php __('Change log') ?></h3>
<?php foreach($trackers as $tracker): ?>
  <label><?php echo $form->input('tracker_ids[]', array('type'=>'checkbox', 'value'=>$tracker['Tracker']['id'], /* @selected_tracker_ids.include? tracker.id.to_s */ 'label'=>$tracker['Tracker']['name'])) ?>
  </label><br />
<?php endforeach ?>
<p><?php echo $form->submit(__('Apply', true), array('class' => 'button-small')) ?></p>
<?php echo $form->end() ?>

<h3><?php __('Versions') ?></h3>
<?php foreach($versions as $version): ?>
<?php echo $html->link($version['name'], '#'.$version['name']) ?><br />
<?php endforeach ?>

