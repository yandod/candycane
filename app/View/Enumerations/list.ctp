<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<h2><?php echo $this->Candy->html_title(__('Enumerations')) ?></h2>

<?php $Enumeration = ClassRegistry::getObject('Enumeration'); ?>
<?php foreach($Enumeration->OPTIONS as $option => $params  ): ?>
<h3><?php echo __($params['label']) ?></h3>

<!-- <% enumerations = Enumeration.get_values(option) %> -->
<?php $enumerations = $Enumeration->get_values($option) ?>
<!-- <% if enumerations.any? %> -->
<?php if(!empty($enumerations)): ?>
<table class="list">
<!-- <% enumerations.each do |enumeration| %> -->
<?php foreach($enumerations as $enumeration): ?>
<tr class="<?php echo $this->Candy->cycle() ?>">
    <td><?php echo $this->Html->link($enumeration['Enumeration']['name'],array('action'=>'edit',$enumeration['Enumeration']['id']),array('id'=>'enumeration')) ?></td>
    <td style="width:15%;"><?php if ($enumeration['Enumeration']['is_default']) echo $this->Html->image('true.png') ?></td>
    <td style="width:15%;">
    <?php echo $this->Html->link($this->Html->image('2uparrow.png',  array('alt'=>__('Move to top'))),   array('action'=>'move', $enumeration['Enumeration']['id'], 'position'=>'highest','opt'=>$option), array('title'=>__('Move to top'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('1uparrow.png',  array('alt'=>__('Move up'))),       array('action'=>'move', $enumeration['Enumeration']['id'], 'position'=>'higher','opt'=>$option),  array('title'=>__('Move up'), 'escape' => false)); ?> -
    <?php echo $this->Html->link($this->Html->image('1downarrow.png',array('alt'=>__('Move down'))),     array('action'=>'move', $enumeration['Enumeration']['id'], 'position'=>'lower','opt'=>$option),   array('title'=>__('Move down'), 'escape' => false)); ?>
    <?php echo $this->Html->link($this->Html->image('2downarrow.png',array('alt'=>__('Move to bottom'))),array('action'=>'move', $enumeration['Enumeration']['id'], 'position'=>'lowest','opt'=>$option),  array('title'=>__('Move to bottom'), 'escape' => false)); ?>
    </td>
    <td align="center" style="width:10%;">
    <?php 
      echo $this->Form->create(null, array('url'=>array('action'=>'destroy', $enumeration['Enumeration']['id']), 'class'=>'button_to'));
      echo $this->Form->submit(__('Delete'), array('onclick'=>'return confirm("'.__('Are you sure ?').'");', 'class'=>"button-small"));
      echo $this->Form->end();
    ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php $this->Candy->reset_cycle() ?>
<?php endif; ?>

<p><?php echo $this->Html->link(__('New value'),array('action'=>'add','opt'=>$option)) ?></p>
<?php endforeach; ?>
