<?php echo $this->Form->create('Project', array('url'=>array(
	'controller' => 'projects',
	'action' => 'roadmap',
	'id' => $main_project['Project']['identifier'],
), 'type'=>'get')) ?>
<h3><?php echo __('Roadmap') ?></h3>
<?php foreach($trackers as $tracker): ?>
<label><?php echo $this->Form->input("tracker_ids[]", array('type'=>'checkbox', 'value'=>$tracker['Tracker']['id'], /* (@selected_tracker_ids.include? tracker.id.to_s), :id => nil, */ 'label'=>$tracker['Tracker']['name'])) ?>
  </label><br />
<?php endforeach ?>
<br />
<label for="completed"><?php echo $this->Form->input('completed', array('type'=>'checkbox', 'value'=>1, /* params[:completed] */ 'label'=>__('Show completed versions'))) ?> </label>
<p><?php echo $this->Form->submit('Apply', array('class'=>'button-small', 'name'=>null)) ?></p>
<?php echo $this->Form->end() ?>

<h3><?php echo __('Versions') ?></h3>
<?php foreach($this->request->data['Version'] as $version): ?>
<?php echo $this->Html->link($version['name'], "#{$version['name']}") ?><br />
<?php endforeach ?>
