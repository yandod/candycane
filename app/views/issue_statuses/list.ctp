<div class="contextual">
<?php echo $html->link(__('New status', true), array('action' => 'new', 'class' => 'icon icon-add')); ?>
</div>

<h2><?php __('Issue statuses'); ?></h2>
 
<table class="list">
  <thead><tr>
  <th><?php __('Status');?></th>
  <th><?php __('Default value');?></th>
  <th><?php __('Issue closed');?></th>
  <th><?php __('Sort');?></th>
  <th></th>
  </tr></thead>
  <tbody>  
<?php foreach($issueStatuses as $status): ?>
  <tr class="<?php /*cycle("odd", "even"); */ ?>">
  <td><?php echo $html->link($status['IssueStatus']['name'], array('action' => 'edit', 'id' => $status['IssueStatus']['id'])); ?></td>
  <td align="center"><?php if($status['IssueStatus']['is_default']){ echo $html->image('true.png'); } ?></td>
  <td align="center"><?php if($status['IssueStatus']['is_closed']){ echo $html->image('true.png'); } ?></td>
  <td align="center" style="width:15%;">
    <?php echo $html->link($html->image('2uparrow.png',  array('alt'=>__('Move to top',true))),   array('action'=>'move', 'id'=>$status['IssueStatus']['id'], 'position'=>'highest'), array('title'=>__('Move to top', true)), null, false); ?>
    <?php echo $html->link($html->image('1uparrow.png',  array('alt'=>__('Move up',true))),       array('action'=>'move', 'id'=>$status['IssueStatus']['id'], 'position'=>'higher'),  array('title'=>__('Move up', true))    , null, false); ?> -
    <?php echo $html->link($html->image('1downarrow.png',array('alt'=>__('Move down',true))),     array('action'=>'move', 'id'=>$status['IssueStatus']['id'], 'position'=>'lower'),   array('title'=>__('Move down', true))  , null, false); ?>
    <?php echo $html->link($html->image('2downarrow.png',array('alt'=>__('Move to bottom',true))),array('action'=>'move', 'id'=>$status['IssueStatus']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom',true)),null,false); ?>
  </td>
  <td align="center" style="width:10%;">
    <?php 
      echo $form->create(null, array('action'=>'destroy', 'id'=>$status['IssueStatus']['id'], 'class'=>'button_to'));
      echo $form->submit(__('Delete',true), array('onclick'=>'return confirm("'.__('Are you sure ?',true).'");', 'class'=>"button-small"));
      echo $form->end();
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