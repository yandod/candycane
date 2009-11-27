<div class="contextual">
<?php echo $html->link(__('New tracker', true), array('action' => 'add'), array('class' => 'icon icon-add')); ?>
</div>

<h2><?php echo $candy->html_title(__('Trackers',true)) ?></h2>

<table class="list">
  <thead><tr>
  <th><?php __('Tracker') ?></th>
  <th></th>
  <th><?php __('Sort') ?></th>
  <th></th>
  </tr></thead>
  <tbody>
<?php foreach($trackers as $tracker): ?>
  <tr class="<?php echo $candy->cycle(); ?>">
  <td><?php echo $html->link($tracker['Tracker']['name'],array('action'=>'edit','id'=>$tracker['Tracker']['id']),array('id'=>'tracker')) ?></td>
  <td align="center"><?php if (count($tracker['Workflow']) == 0):?><span class="icon icon-warning"><? __('No workflow defined for this tracker') ?> (<?php echo $html->link(__('Edit',true),array('controller'=>'workflows','action'=>'edit','tracker_id'=>$tracker['Tracker']['id'])) ?>)</span><?php endif; ?></td>
  <td align="center" style="width:15%;">
    <?php echo $html->link($html->image('2uparrow.png',  array('alt'=>__('Move to top',true))),   array('action'=>'move', 'id'=>$tracker['Tracker']['id'], 'position'=>'highest'), array('title'=>__('Move to top', true)), null, false); ?>
    <?php echo $html->link($html->image('1uparrow.png',  array('alt'=>__('Move up',true))),       array('action'=>'move', 'id'=>$tracker['Tracker']['id'], 'position'=>'higher'),  array('title'=>__('Move up', true))    , null, false); ?> -
    <?php echo $html->link($html->image('1downarrow.png',array('alt'=>__('Move down',true))),     array('action'=>'move', 'id'=>$tracker['Tracker']['id'], 'position'=>'lower'),   array('title'=>__('Move down', true))  , null, false); ?>
    <?php echo $html->link($html->image('2downarrow.png',array('alt'=>__('Move to bottom',true))),array('action'=>'move', 'id'=>$tracker['Tracker']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom',true)),null,false); ?>
  </td>
  <td align="center" style="width:10%;">
    <?php 
      echo $form->create(null, array('url'=>array('action'=>'destroy', 'id'=>$tracker['Tracker']['id']), 'class'=>'button_to'));
      echo $form->submit(__('Delete',true), array('onclick'=>'return confirm("'.__('Are you sure ?',true).'");', 'class'=>"button-small"));
      echo $form->end();
    ?>
  </td>
  </tr>
<?php endforeach; ?>
  </tbody>
</table>

<?php //<p class="pagination"><%= pagination_links_full @tracker_pages %></p>?>
