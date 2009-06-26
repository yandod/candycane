<h2><?php __('Custom fields') ?></h2>

<div class="tabs">
<ul>
<?php foreach($customField->custom_fields_tabs() as $tab): ?>
    <li><?php echo $html->link($tab['label'], array('?'=>array('tab' => $tab['name'])),
                                    array('id' => "tab-{$tab['name']}",
                                    'class' => ($tab['name'] != $selected_tab ? null : 'selected'),
                                    'onclick' => "showTab('{$tab['name']}'); this.blur(); return false;")); ?></li>
<?php endforeach; ?>
</ul>
</div>

<?php foreach($customField->custom_fields_tabs() as $tab): ?>
<div id="tab-content-<?php echo $tab['name'] ?>" class="tab-content" style="<?php echo $tab['name'] != $selected_tab ? 'display:none' : null; ?>">
<table class="list">		
  <thead><tr>	
  <th width="30%"><?php __('Name')?></th>
  <th><?php __('Format')?></th>
  <th><?php __('Required')?></th>
  <?php if ($tab['name'] == 'IssueCustomField'): ?>
  <th><?php echo __('For all projects')?></th>
  <th><?php echo __('Used by')?></th>
  <?php endif; ?>
  <th><?php echo __('Sort')?></th>
  <th width="10%"></th>
  </tr></thead>
  <tbody>
<?php foreach ($customField->sort_custom_fields_by_type($custom_fields_by_type, $tab) as $custom_field): ?>
  <tr class="<?php echo $candy->cycle(); ?>">
  <td><?php echo $html->link($custom_field['CustomField']['name'], array('action' => 'edit', 'id' => $custom_field['CustomField']['id'])); ?></td>
  <td align="center"><?php __($customField->field_format($custom_field['CustomField']['field_format'], 'name')) ?></td>
  <td align="center"><?php echo $custom_field['CustomField']['is_required'] ? $html->image('true.png') : '&nbsp;'; ?></td>
  <?php if ($tab['name'] == 'IssueCustomField'): ?>
  <td align="center"><?php echo $custom_field['CustomField']['is_for_all'] ? $html->image('true.png') : '&nbsp;'; ?></td>
  <td align="center"><?php echo ($custom_field['CustomField']['type'] == 'IssueCustomField' && !$custom_field['CustomField']['is_for_all']) ? /*count($custom_field.projects) + ' ' + lwr(:label_project, custom_field.projects.count)*/ 'count' : '&nbsp;'; ?></td>
  <?php endif; ?>
    <td align="center" style="width:15%;">
      <?php echo $html->link($html->image('2uparrow.png',  array('alt'=>__('Move to top',true))),   array('action'=>'move', 'id'=>$custom_field['CustomField']['id'], 'position'=>'highest'), array('title'=>__('Move to top', true)), null, false); ?>
      <?php echo $html->link($html->image('1uparrow.png',  array('alt'=>__('Move up',true))),       array('action'=>'move', 'id'=>$custom_field['CustomField']['id'], 'position'=>'higher'),  array('title'=>__('Move up', true))    , null, false); ?> -
      <?php echo $html->link($html->image('1downarrow.png',array('alt'=>__('Move down',true))),     array('action'=>'move', 'id'=>$custom_field['CustomField']['id'], 'position'=>'lower'),   array('title'=>__('Move down', true))  , null, false); ?>
      <?php echo $html->link($html->image('2downarrow.png',array('alt'=>__('Move to bottom',true))),array('action'=>'move', 'id'=>$custom_field['CustomField']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom',true)),null,false); ?>
    </td>
    <td align="center">
      <?php 
        echo $form->create(null, array('url'=>array('action'=>'destroy', 'id'=>$custom_field['CustomField']['id']), 'class'=>'button_to'));
        echo $form->submit(__('Delete',true), array('onclick'=>'return confirm("'.__('Are you sure ?',true).'");', 'class'=>"button-small"));
        echo $form->end();
      ?>
    </td>
  </tr>
<?php endforeach; $candy->reset_cycle(); ?>
  </tbody>
</table>

<p><%= link_to l(:label_custom_field_new), {:action => 'new', :type => tab[:name]}, :class => 'icon icon-add' %></p>
</div>
<?php endforeach; ?>

<% html_title(l(:label_custom_field_plural)) -%>
