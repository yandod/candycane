<div class="contextual">
<?php echo $this->Html->link(__('New role', TRUE), array('action' => 'add'),array('class' => 'icon icon-add'));?>
</div>

<h2><?php $this->Candy->html_title(); __('Roles'); ?></h2>

<table class="list">
  <thead><tr>
   <th><?php echo __('Role');?></th>
    <th><?php echo __('Sort'); ?></th>
	<th></th>
  </tr></thead>
  <tbody>
    <?php foreach ($roles as $role): ?>
    <tr class="<?php echo $this->Candy->cycle('odd','even');?>">
    <td>
      <?php
        $tag = ($role['Role']['builtin'] == 1) ? 'em' : 'span';
        echo sprintf('<%s>%s</%s>',
                     $tag,
                     $this->Html->link($role['Role']['name'],array('action' => 'edit',
                                                             'id' => $role['Role']['id'])),
                     $tag);
      ?>
      <td align="center" style="width:15%;">
        <?php if (! ($role['Role']['builtin'] != 0)): ?>
        <?php echo $this->Html->link($this->Html->image('2uparrow.png', array('alt' => __('Move to top',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'highest'), array('title' => __('Move to top', TRUE)), NULL, FALSE); ?>
        <?php echo $this->Html->link($this->Html->image('1uparrow.png', array('alt' => __('Move up',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'higher'), array('title' => __('Move up', TRUE)), NULL, FALSE); ?> -
        <?php echo $this->Html->link($this->Html->image('1downarrow.png', array('alt' => __('Move down',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'lower'), array('title' => __('Move down', TRUE)), NULL, FALSE); ?>
        <?php echo $this->Html->link($this->Html->image('2downarrow.png', array('alt' => __('Move to bottom',TRUE))), array('action' => 'move', 'id' => $role['Role']['id'], 'position' => 'lower'), array('title' => __('Move to bottom', TRUE)), NULL, FALSE); ?>
        <?php endif; ?>
      </td>
      <td align="center" style="width:10%;">

        <?php
          echo $this->Form->create(NULL, array('url' => array('action' => 'destroy', 'id' => $role['Role']['id']), array('class' => 'button_to')));
          if ($role['Role']['builtin'] != 0) {
            $disabled = array('disabled' => 'disabled');
          } else {
            $disabled = array();
          }
          echo $this->Form->submit(__('Delete', TRUE), array_merge(array('onclick' => 'return confirm("'.__('Are you sure ?', TRUE).'");',
                                                                   'class' => 'button-small'), $disabled));
          echo $this->Form->end();
        ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<p class="pagination"><?php //TODO pagination ...  <%= pagination_links_full @role_pages %> ?></p>
<p><?php echo $this->Html->link(__('Permissions report', TRUE), array('action' => 'report'));?></p>
