<h2><?php echo __('Version') ?></h2>

<?php echo $form->create('Version', array('action'=>'edit')) ?>

<?php /*
<% labelled_tabular_form_for :version, @version, :url => { :action => 'edit' } do |f| %>
<%= render :partial => 'form', :locals => { :f => f } %>
 */ ?>

<div class="box">
<p><?php echo $form->input('name') ?></p>
<p><?php echo $form->input('description') ?></p>
<p><?php echo $form->input('wiki_page_title', array('label'=>__('Wiki page', true))) ?></p>
<p><?php echo $form->input('effective_date', array('label'=>__('Date', true))) ?></p>
</div>

<?php echo $form->submit(__('Save', true)) ?>

<?php echo $form->end() ?>


