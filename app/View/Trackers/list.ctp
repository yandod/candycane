<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<div class="contextual">
<?php echo $this->Html->link(__('New tracker'), array('action' => 'add'), array('class' => 'icon icon-add')); ?>
</div>

<h2><?php echo $this->Candy->html_title(__('Trackers')) ?></h2>

<table class="list">
  <thead><tr>
  <th><?php echo __('Tracker') ?></th>
  <th></th>
  <th><?php echo __('Sort') ?></th>
  <th></th>
  </tr></thead>
  <tbody>
<?php foreach($trackers as $tracker): ?>
  <tr class="<?php echo $this->Candy->cycle(); ?>">
  <td><?php echo $this->Html->link($tracker['Tracker']['name'],array('action'=>'edit',$tracker['Tracker']['id']),array('id'=>'tracker')) ?></td>
  <td align="center"><?php if (count($tracker['Workflow']) == 0):?><span class="icon icon-warning"><?php echo __('No workflow defined for this tracker') ?> (<?php echo $this->Html->link(__('Edit'),array('controller'=>'workflows','action'=>'edit','tracker_id'=>$tracker['Tracker']['id'])) ?>)</span><?php endif; ?></td>
  <td align="center" style="width:15%;">
    <?php echo $this->Html->link($this->Html->image('2uparrow.png',  array('alt'=>__('Move to top'))),   array('action'=>'move', $tracker['Tracker']['id'], 'position'=>'highest'), array('title'=>__('Move to top'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('1uparrow.png',  array('alt'=>__('Move up'))),       array('action'=>'move', $tracker['Tracker']['id'], 'position'=>'higher'),  array('title'=>__('Move up'), 'escape' => false)); ?> -
    <?php echo $this->Html->link($this->Html->image('1downarrow.png',array('alt'=>__('Move down'))),     array('action'=>'move', $tracker['Tracker']['id'], 'position'=>'lower'),   array('title'=>__('Move down'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('2downarrow.png',array('alt'=>__('Move to bottom'))),array('action'=>'move', $tracker['Tracker']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom'), 'escape' => false)); ?>
  </td>
  <td align="center" style="width:10%;">
    <?php 
      echo $this->Form->create(null, array('url'=>array('action'=>'destroy', $tracker['Tracker']['id']), 'class'=>'button_to'));
      echo $this->Form->submit(__('Delete'), array('onclick'=>'return confirm("'.__('Are you sure ?').'");', 'class'=>"button-small"));
      echo $this->Form->end();
    ?>
  </td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>

<?php //<p class="pagination"><%= pagination_links_full @tracker_pages %></p>?>
