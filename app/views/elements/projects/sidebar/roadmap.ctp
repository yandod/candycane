<?php echo $form->create('Project', array('url'=>array(
	'controller' => 'projects',
	'action' => 'roadmap',
	'id' => $main_project['Project']['identifier'],
), 'type'=>'get')) ?>
<h3><?php __('Roadmap') ?></h3>
<?php foreach($trackers as $tracker): ?>
<label><?php echo $form->input("tracker_ids[]", array('type'=>'checkbox', 'value'=>$tracker['Tracker']['id'], /* (@selected_tracker_ids.include? tracker.id.to_s), :id => nil, */ 'label'=>$tracker['Tracker']['name'])) ?>
  </label><br />
<?php endforeach ?>
<br />
<label for="completed"><?php echo $form->input('completed', array('type'=>'checkbox', 'value'=>1, /* params[:completed] */ 'label'=>__('Show completed versions', true))) ?> </label>
<p><?php echo $form->submit('Apply', array('class'=>'button-small', 'name'=>null)) ?></p>
<?php echo $form->end() ?>

<h3><?php __('Versions') ?></h3>
<?php foreach($this->data['Version'] as $version): ?>
<?php echo $html->link($version['name'], "#{$version['name']}") ?><br />
<?php endforeach ?>
