<div class="contextual">
<?php if ($editable) : ?>
<%= link_to_if_authorized(l(:button_edit), {:action => 'edit', :page => @page.title}, :class => 'icon icon-edit', :accesskey => accesskey(:edit)) if @content.version == @page.content.version %>
<%= link_to_if_authorized(l(:button_lock), {:action => 'protect', :page => @page.title, :protected => 1}, :method => :post, :class => 'icon icon-lock') if !@page.protected? %>
<%= link_to_if_authorized(l(:button_unlock), {:action => 'protect', :page => @page.title, :protected => 0}, :method => :post, :class => 'icon icon-unlock') if @page.protected? %>
<%= link_to_if_authorized(l(:button_rename), {:action => 'rename', :page => @page.title}, :class => 'icon icon-move') if @content.version == @page.content.version %>
<%= link_to_if_authorized(l(:button_delete), {:action => 'destroy', :page => @page.title}, :method => :post, :confirm => l(:text_are_you_sure), :class => 'icon icon-del') %>
<%= link_to_if_authorized(l(:button_rollback), {:action => 'edit', :page => @page.title, :version => @content.version }, :class => 'icon icon-cancel') if @content.version < @page.content.version %>
<?php endif; ?>
<%= link_to_if_authorized(l(:label_history), {:action => 'history', :page => @page.title}, :class => 'icon icon-history') %>
</div>

<%= breadcrumb(@page.ancestors.reverse.collect {|parent| link_to h(parent.pretty_title), {:page => parent.title}}) %>

<?php if ($content['Content']['version'] !== $page['Content']['version']) : ?>
    <p>
    <?php if ($content['Content']['version'] > 1) { echo $html->link('&#171; ' . __('Previous', true), aa('action', 'index', 'page', $page['Page']['title'], 'version', $content['Content']['version'] - 1), aa(), false, false); echo " - "; } ?>
    <?php printf('%s %s/%s', __('Versions'), $content['Content']['version'], $page['Content']['version']) ?>
    <?php if ($content['Content']['version'] > 1) { printf('(%s)', $html->link('diff', aa('controller', 'wiki', 'action', 'diff', 'page', $page['Page']['title'], 'version', $content['Content']['version']))); } ?> - 
    <?php if ($content['Content']['version'] < $page['Content']['version']) { echo $html->link(__('Next', true). ' &#187;', aa('action', 'index', 'page', $page['Page']['title'], 'version', $content['Content']['version'] + 1), aa(), false, false) . " - "; } ?>
    <?php echo $html->link(__('Current version', true), aa('action', 'index', 'page', $page['Page']['title'])); ?>
    <br />
    <em><?php echo $content['Content']['author'] ? $content['Content']['author']['name'] : "anonyme" ?>, <?php //$time->format($content['Content']['updated_on']) ?> </em><br />
    <?php echo h($content['Content']['comments']) ?>
    </p>
    <hr />
<?php endif; ?>

<?php echo $this->renderElement('wiki/_content', aa('content',$content)); ?>

<%= link_to_attachments @page %>

<?php if ($editable /* && authorize_for('wiki', 'add_attachment')*/ ) : ?>
<p><?php echo $html->link(__('New file', true), '', aa('onclick', "Element.show('add_attachment_form'); Element.hide(this); Element.scrollTo('add_attachment_form'); return false;", 'id', 'attach_files_link')); ?></p>
<?php echo $form->create();
/* form_tag({ :controller => 'wiki', :action => 'add_attachment', :page => @page.title }, :multipart => true, :id => "add_attachment_form", :style => "display:none;") do */
?>
  <div class="box">
  <p><?php echo $this->renderElement('attachments/_form'); ?></p>
  </div>
<?php $form->submit(__('Add', true)); ?>
<?php echo $html->link(__('Cancel' ,true), '', aa('onclick', "Element.hide('add_attachment_form'); Element.show('attach_files_link'); return false;")) ?>
<?php echo $form->end(); ?>
<?php endif; ?>

<p class="other-formats">
<?php echo __("'Also available in:'", true) ?>
<span><?php echo $html->link('HTML', aa('page', $page['Page']['title'], 'export', 'html', 'version', $content['Content']['version'])) ?></span>
<span><?php echo $html->link('TXT', aa('page', $page['Page']['title'], 'export', 'txt', 'version', $content['Content']['version'], 'class', 'text')) ?></span>
</p>

<% content_for :header_tags do %>
  <%= stylesheet_link_tag 'scm' %>
<% end %>

<% content_for :sidebar do %>
  <?php echo $this->renderElement('wiki/_sidebar'); ?>
<% end %>

<% html_title @page.pretty_title %>
