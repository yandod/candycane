<?php echo $this->Form->create('Project', array('url'=>array(
	'controller' => 'projects',
	'action' => 'roadmap',
	'project_id' => $main_project['Project']['identifier'],
), 'type'=>'get')) ?>

<h3><?php echo __('Roadmap') ?></h3>
<ul>
<?php foreach($trackers as $tracker) { 
	echo $this->Html->tag('li',
		$this->Form->input("tracker_ids[]", array(
			'type' 	=> 'checkbox',
			'value' => $tracker['Tracker']['id'],
			'label'	=> $tracker['Tracker']['name'],
			'div'	=> false
		))
	);
} 
?>
		<?php 
			echo $this->Html->tag('li',
				$this->Form->input('completed', array(
					'type' 		=> 'checkbox',
					'value' 	=> 1, 
					'label'		=> __('Show completed versions'),
					'checked' 	=> isset($this->request->query['completed']) && $this->request->query['completed'] ,
					'div'		=> false
				))
			);
		?> 
	</label>
	<p><?php echo $this->Form->submit(__('Apply'), array('class'=>'button-small', 'name'=>null)) ?></p>
</ul>
<?php echo $this->Form->end() ?>

<h3><?php echo __('Versions') ?></h3>
<?php foreach($this->request->data['Version'] as $version): ?>
<?php 
if( !isset($this->request->query['completed']) || !$this->request->query['completed']) {
  if ($version['completed']) {
    continue;
  }
}
?>
<?php echo $this->Html->link($version['name'], "#{$version['name']}") ?><br />
<?php endforeach ?>
