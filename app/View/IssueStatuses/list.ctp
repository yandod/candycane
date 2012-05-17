<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<div class="contextual">
<?php echo $this->Html->link(__('New status'), array('action' => 'add'), array('class' => 'icon icon-add')); ?>
</div>

<h2><?php echo $this->Candy->html_title(__('Issue statuses')); ?></h2>

<table class="list">
  <thead><tr>
  <th><?php echo __('Status');?></th>
  <th><?php echo __('Default value');?></th>
  <th><?php echo __('Issue closed');?></th>
  <th><?php echo __('Sort');?></th>
  <th></th>
  </tr></thead>
  <tbody>  
<?php foreach($issue_statuses as $status): ?>
  <tr class="<?php echo $this->Candy->cycle("odd", "even");?>">
  <td><?php echo $this->Html->link($status['IssueStatus']['name'], array('action' => 'edit', $status['IssueStatus']['id'])); ?></td>
  <td align="center"><?php if($status['IssueStatus']['is_default']){ echo $this->Html->image('true.png'); } ?></td>
  <td align="center"><?php if($status['IssueStatus']['is_closed']){ echo $this->Html->image('true.png'); } ?></td>
  <td align="center" style="width:15%;">
    <?php echo $this->Html->link($this->Html->image('2uparrow.png',  array('alt'=>__('Move to top'))),   array('action'=>'move', $status['IssueStatus']['id'], 'position'=>'highest'), array('title'=>__('Move to top'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('1uparrow.png',  array('alt'=>__('Move up'))),       array('action'=>'move', $status['IssueStatus']['id'], 'position'=>'higher'),  array('title'=>__('Move up'), 'escape' => false)); ?> -
    <?php echo $this->Html->link($this->Html->image('1downarrow.png',array('alt'=>__('Move down'))),     array('action'=>'move', $status['IssueStatus']['id'], 'position'=>'lower'),   array('title'=>__('Move down'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('2downarrow.png',array('alt'=>__('Move to bottom'))),array('action'=>'move', $status['IssueStatus']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom'), 'escape' => false)); ?>
  </td>
  <td align="center" style="width:10%;">
    <?php 
      echo $this->Form->create(null, array('url'=>array('action'=>'destroy', $status['IssueStatus']['id']), 'class'=>'button_to'));
      echo $this->Form->submit(__('Delete'), array('onclick'=>'return confirm("'.__('Are you sure ?').'");', 'class'=>"button-small"));
      echo $this->Form->end();
    ?>
  </td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>
<!--
<p class="pagination"><%= pagination_links_full @issue_status_pages %></p>

<% html_title(l(:label_issue_status_plural)) -%>
-->
