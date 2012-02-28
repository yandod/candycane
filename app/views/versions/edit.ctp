<h2><?php __('Version') ?></h2>

<?php echo $form->create('Version', array('action'=>'edit','class'=>'tabular')) ?>

<?php /*
<% labelled_tabular_form_for :version, @version, :url => { :action => 'edit' } do |f| %>
<%= render :partial => 'form', :locals => { :f => f } %>
 */ ?>

<div class="box">
<p>
  <?php echo $form->label('name', __('Name', true)); ?>
  <?php echo $form->input('name',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('description', __('Description', true)); ?>
  <?php echo $form->input('description',array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('wiki_page_title', __('Wiki page', true)); ?>
  <?php echo $form->input('wiki_page_title', array('div'=>false, 'label'=>false, 'size'=>60)) ?>
</p>
<p>
  <?php echo $form->label('effective_date', __('Date', true)); ?>
  <?php echo $form->input('effective_date', array('div'=>false, 'label'=>false, 'size'=>10, 'type'=>'text')); ?>
<?php echo $candy->calendar_for('VersionEffectiveDate'); ?>
</p>
</div>


<?php echo $form->submit(__('Save', true)) ?>

<?php echo $form->end() ?>


