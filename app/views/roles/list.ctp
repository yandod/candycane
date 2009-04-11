<div class="contextual">
<?php echo $html->link(__('New role', TRUE), array('action' => 'add'),array('class' => 'icon icon-add'));?>
</div>

<h2><?php $candy->html_title(); __('Roles'); ?></h2>

<table class="list">
  <thead><tr>
   <th><?php __('Role');?></th>
    <th><?php __('Sort'); ?></th>
	<th></th>
  </tr></thead>
  <tbody>
    <?php foreach ($roles as $role): ?>
    <tr class="<?php echo $candy->cycle('odd','even');?>">
    <td>
      <?php
        $tag = ($role['Role']['builtin'] == 1) ? 'em' : 'span';
        echo sprintf('<%s>%s</%s>',
                     $tag,
                     $html->link($role['Role']['name'],array('action' => 'edit',
                                                             'id' => $role['Role']['id'])),
                     $tag);
      ?>
      <td align="center" style="width:15%;">
        <?php if (! ($role['Role']['builtin'] != 0)): ?>
        <?php echo $html->link($html->image('2uparrow.png', array('alt' => __('Move to top',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'highest'), array('title' => __('Move to top', TRUE)), NULL, FALSE); ?>
        <?php echo $html->link($html->image('1uparrow.png', array('alt' => __('Move up',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'higher'), array('title' => __('Move up', TRUE)), NULL, FALSE); ?> -
        <?php echo $html->link($html->image('1downarrow.png', array('alt' => __('Move down',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'lower'), array('title' => __('Move down', TRUE)), NULL, FALSE); ?>
        <?php echo $html->link($html->image('2downarrow.png', array('alt' => __('Move to bottom',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'lower'), array('title' => __('Move to bottom', TRUE)), NULL, FALSE); ?>
        <?php endif; ?>
      </td>
      <td align="center" style="width:10%;">

        <?php
          echo $form->create(NULL, array('url' => array('action' => 'destroy', 'id' => $role['Role']['id']), array('class' => 'button_to')));
          if ($role['Role']['builtin'] != 0) {
            $disabled = array('disabled' => 'disabled');
          } else {
            $disabled = array();
          }
          echo $form->submit(__('Delete', TRUE), array_merge(array('onclick' => 'return confirm("'.__('Are you sure ?', TRUE).'");',
                                                                   'class' => 'button-small'), $disabled));
          echo $form->end();
        ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p class="pagination"><%= pagination_links_full @role_pages %></p>
<p><?php echo $html->link(__('Permissions report', TRUE), array('action' => 'report'));?></p>
