<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo __('Custom fields'); ?></h2>

<div class="tabs">
<ul>
<?php foreach($this->CustomField->custom_fields_tabs() as $tab): ?>
    <li><?php echo $this->Html->link($tab['label'], array('?'=>array('tab' => $tab['name'])),
                                    array('id' => "tab-{$tab['name']}",
                                    'class' => ($tab['name'] != $selected_tab ? null : 'selected'),
                                    'onclick' => "showTab('{$tab['name']}'); this.blur(); return false;")); ?></li>
<?php endforeach; ?>
</ul>
</div>

<?php foreach($this->CustomField->custom_fields_tabs() as $tab): ?>
<div id="tab-content-<?php echo $tab['name'] ?>" class="tab-content" style="<?php echo $tab['name'] != $selected_tab ? 'display:none' : null; ?>">
<table class="list">		
  <thead><tr>	
  <th width="30%"><?php echo __('Name')?></th>
  <th><?php echo __('Format')?></th>
  <th><?php echo __('Required')?></th>
  <?php if ($tab['name'] == 'IssueCustomField'): ?>
  <th><?php echo __('For all projects')?></th>
  <th><?php echo __('Used by')?></th>
  <?php endif; ?>
  <th><?php echo __('Sort')?></th>
  <th width="10%"></th>
  </tr></thead>
  <tbody>
<?php foreach ($this->CustomField->sort_custom_fields_by_type($custom_fields_by_type, $tab) as $custom_field): ?>
  <tr class="<?php echo $this->Candy->cycle(); ?>">
  <td><?php echo $this->Html->link($custom_field['CustomField']['name'], array('action' => 'edit', $custom_field['CustomField']['id'])); ?></td>
  <td align="center"><?php echo __($this->CustomField->field_format($custom_field['CustomField']['field_format'], 'name')) ?></td>
  <td align="center"><?php echo $custom_field['CustomField']['is_required'] ? $this->Html->image('true.png') : '&nbsp;'; ?></td>
  <?php if ($tab['name'] == 'IssueCustomField'): ?>
  <td align="center"><?php echo $custom_field['CustomField']['is_for_all'] ? $this->Html->image('true.png') : '&nbsp;'; ?></td>
  <td align="center"><?php echo ($custom_field['CustomField']['type'] == 'IssueCustomField' && !$custom_field['CustomField']['is_for_all']) ? $custom_field['Project']['count_all'].' '.__n('Project', 'Projects', $custom_field['Project']['count_all']) : '&nbsp;'; ?></td>
  <?php endif; ?>
    <td align="center" style="width:15%;">
      <?php echo $this->Html->link($this->Html->image('2uparrow.png',  array('alt'=>__('Move to top'))),   array('action'=>'move', $custom_field['CustomField']['id'], 'position'=>'highest'), array('title'=>__('Move to top'), 'escape' => false)); ?>
      <?php echo $this->Html->link($this->Html->image('1uparrow.png',  array('alt'=>__('Move up'))),       array('action'=>'move', $custom_field['CustomField']['id'], 'position'=>'higher'),  array('title'=>__('Move up'), 'escape' => false)); ?> -
      <?php echo $this->Html->link($this->Html->image('1downarrow.png',array('alt'=>__('Move down'))),     array('action'=>'move', $custom_field['CustomField']['id'], 'position'=>'lower'),   array('title'=>__('Move down'), 'escape' => false)); ?>
      <?php echo $this->Html->link($this->Html->image('2downarrow.png',array('alt'=>__('Move to bottom'))),array('action'=>'move', $custom_field['CustomField']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom'), 'escape' => false)); ?>
    </td>
    <td align="center">
      <?php 
        echo $this->Form->create(null, array('url'=>array('action'=>'destroy', $custom_field['CustomField']['id']), 'class'=>'button_to'));
        echo $this->Form->submit(__('Delete'), array('onclick'=>'return confirm("'.__('Are you sure ?').'");', 'class'=>"button-small"));
        echo $this->Form->end();
      ?>
    </td>
  </tr>
<?php endforeach; $this->Candy->reset_cycle(); ?>
  </tbody>
</table>

<p><?php echo $this->Html->link(__('New custom field'), array('action' => 'add', '?'=>array('type' => $tab['name'])), array('class' => 'icon icon-add')); ?></p>
</div>
<?php endforeach; ?>

<?php $this->Candy->html_title(__('Custom fields')); ?>
