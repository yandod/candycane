<?php echo $this->Form->create('Project',array(
	'url' => array(
	    'controller' => 'projects',
	    'action' => 'modules',
	    'project_id' => $main_project['Project']['identifier'],
	    '?' => 'tab=modules'
	    ),
	'id' => 'modules-form')) ?>
<!-- <% form_for :project, @project,
            :url => { :action => 'modules', :id => @project },
            :html => {:id => 'modules-form'} do |f| %>
-->            
<div class=box>
<strong><?php echo __("Select modules to enable for this project:") ?></strong>
<?php
  $checked_modules = array();
  if (isset($main_project)) {
    $checked_modules = Set::extract('Project/EnabledModule/name',array($main_project));
  }
?>

<?php foreach ($available_project_modules as $v): ?>
<?php
    $checked = "";
    if ( in_array($v,$checked_modules) ) {
      $checked = 'checked';
    }
?>
<p><label for="<?php echo $v; ?>"><?php echo $this->Form->checkbox('EnabledModule][', array('value' => $v,'div' => false,'label' => false,'checked' => $checked,'id' => $v)); ?>
 <?php echo h(__(ucfirst(str_replace('_',' ',$v)))) ?></label></p>
<!--
<p><label><%= check_box_tag 'enabled_modules[]', m, @project.module_enabled?(m) -%>
 <%= (l_has_string?("project_module_#{m}".to_sym) ? l("project_module_#{m}".to_sym) : m.to_s.humanize) %></label></p>
 -->
<?php endforeach; ?>
</div>

<!-- <p><%= check_all_links 'modules-form' %></p> -->
<p><?php echo $this->Form->submit(__('Save')) ?></p>
<?php echo $this->Form->end(); ?>
