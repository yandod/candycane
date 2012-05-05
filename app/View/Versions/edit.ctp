<h2><?php echo __('Version') ?></h2>

<?php echo $this->Form->create('Version', array('action'=>'edit','class'=>'tabular')) ?>

<?php /*
<% labelled_tabular_form_for :version, @version, :url => { :action => 'edit' } do |f| %>
<%= render :partial => 'form', :locals => { :f => f } %>
 */ ?>

<div class="box">
<p>
  <?php echo $this->Form->label('name', __('Name')); ?>
  <?php echo $this->Form->input('name',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('description', __('Description')); ?>
  <?php echo $this->Form->input('description',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('wiki_page_title', __('Wiki page')); ?>
  <?php echo $this->Form->input('wiki_page_title', array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $this->Form->label('effective_date', __('Date')); ?>
  <?php echo $this->Form->input('effective_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
<?php echo $this->Candy->calendar_for('VersionEffectiveDate'); ?>
</p>
</div>


<?php echo $this->Form->submit(__('Save')) ?>

<?php echo $this->Form->end() ?>


