<!--<%= error_messages_for 'query' %>-->
<input type="hidden" name="confirm" value="1" />
<!--<%= hidden_field_tag 'confirm', 1 %>-->

<?php //d($this->request->data['Query']) ?>
<div class="box">
<div class="tabular">
<p><?php echo $this->Form->input('Query.name', array('type' => 'text', 'size' => '80', 'div' => false, 'label' => __('Name'))) ?></p>

<?php if ($currentuser['admin'] || $this->Candy->authorize_for(':manage_public_queries')): ?>
<p><label for="QueryIsPublic"><?php echo __('Public') ?></label>
<?php echo $this->Form->input('Query.is_public', array('type' => 'checkbox', 'multiple' => false, 'onchange' => $currentuser['admin'] ? null : 'if (this.checked) {$("query_is_for_all").checked = false; $("query_is_for_all").disabled = true;} else {$("query_is_for_all").disabled = false;}', 'div' => false, 'label' => false)) ?></p>
<?php endif ?>

<p><label for="QueryQueryIsForAll"><?php echo __('For all projects') ?></label>
<?php echo $this->Form->input('Query.query_is_for_all', array('type' => 'checkbox', 'value' => '1', 'div' => false, 'label' => false, 'disabled' => (!(isset($query_new_record) && $query_new_record) && ((!isset($main_project) || !$main_project) || ((isset($this->request->data['Query']['is_public']) && $this->request->data['Query']['is_public']) && !$currentuser['admin']))) ? true : false)) ?>

<!--<%= check_box_tag 'query_is_for_all', 1, @query.project.nil?,
      :disabled => (!@query.new_record? && (@query.project.nil? || (@query.is_public? && !User.current.admin?))) %>--></p>

<p><label for="query_default_columns"><?php echo __('Default columns') ?></label>
<?php echo $this->Form->input('Query.default_columns', array('type' => 'checkbox', 'value' => '1', 'onclick' => 'if (this.checked) {Element.hide("columns")} else {Element.show("columns")}', 'div' => false, 'label' => false, 'id' => 'query_default_columns', 'checked' => $this->request->data['Query']['default_columns'])) ?>
<!--<%= check_box_tag 'default_columns', 1, @query.has_default_columns?, :id => 'query_default_columns',
      :onclick => 'if (this.checked) {Element.hide("columns")} else {Element.show("columns")}' %>--></p>
</div>

<fieldset><legend><?php echo __('Filters') ?></legend>
<?php echo $this->element('queries/filters', array('query' => array('Query' => $this->request->data['Query']))) ?>
<!--<%= render :partial => 'queries/filters', :locals => {:query => query}%>-->
</fieldset>

<?php echo $this->element('queries/columns', array('query' => array('Query' => $this->request->data['Query']))) ?>
<!--<%= render :partial => 'queries/columns', :locals => {:query => query}%>-->
</div>
