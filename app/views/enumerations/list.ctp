<h2><?php echo $candy->html_title(__('Enumerations',true)) ?></h2>

<?php $Enumeration = ClassRegistry::getObject('Enumeration'); ?>
<?php foreach($Enumeration->OPTIONS as $option => $params  ): ?>
<h3><?php __($params['label']) ?></h3>

<!-- <% enumerations = Enumeration.get_values(option) %> -->
<?php $enumerations = $Enumeration->get_values($option) ?>
<!-- <% if enumerations.any? %> -->
<?php if(!empty($enumerations)): ?>
<table class="list">
<!-- <% enumerations.each do |enumeration| %> -->
<?php foreach($enumerations as $enumeration): ?>
<tr class="<?php echo $candy->cycle() ?>">
    <td><?php echo $html->link($enumeration['Enumeration']['name'],array('action'=>'edit','id'=>$enumeration['Enumeration']['id']),array('id'=>'enumeration')) ?></td>
    <td style="width:15%;"><?php if ($enumeration['Enumeration']['is_default']) echo $html->image('true.png') ?></td>
    <td style="width:15%;">
    <?php echo $html->link($html->image('2uparrow.png',  array('alt'=>__('Move to top',true))),   array('action'=>'move', 'id'=>$enumeration['Enumeration']['id'], 'position'=>'highest'), array('title'=>__('Move to top', true)), null, false); ?>
    <?php echo $html->link($html->image('1uparrow.png',  array('alt'=>__('Move up',true))),       array('action'=>'move', 'id'=>$enumeration['Enumeration']['id'], 'position'=>'higher'),  array('title'=>__('Move up', true))    , null, false); ?> -
    <?php echo $html->link($html->image('1downarrow.png',array('alt'=>__('Move down',true))),     array('action'=>'move', 'id'=>$enumeration['Enumeration']['id'], 'position'=>'lower'),   array('title'=>__('Move down', true))  , null, false); ?>
    <?php echo $html->link($html->image('2downarrow.png',array('alt'=>__('Move to bottom',true))),array('action'=>'move', 'id'=>$enumeration['Enumeration']['id'], 'position'=>'lowest'),  array('title'=>__('Move to bottom',true)),null,false); ?>
    </td>
    <td align="center" style="width:10%;">
    <?php 
      echo $form->create(null, array('url'=>array('action'=>'destroy', 'id'=>$enumeration['Enumeration']['id']), 'class'=>'button_to'));
      echo $form->submit(__('Delete',true), array('onclick'=>'return confirm("'.__('Are you sure ?',true).'");', 'class'=>"button-small"));
      echo $form->end();
    ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php $candy->reset_cycle() ?>
<?php endif; ?>

<p><%= link_to l(:label_enumeration_new), { :action => 'new', :opt => option } %></p>
<?php endforeach; ?>
