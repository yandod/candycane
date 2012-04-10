<div class="contextual">
<?php if (true): ?>
<?php echo $this->Html->link(__('New file'), array('controller'=>'projects', 'action'=>'add_file', 'project_id'=>$this->request->data['Project']['identifier_or_id']), array('class'=>'icon icon-add')) ?>
<?php /*
<%= link_to_if_authorized l(:label_attachment_new), {:controller => 'projects', :action => 'add_file', :id => @project}, :class => 'icon icon-add' %>
 */ ?>
<?php endif ?>
</div>

<h2><?php echo __('Files') ?></h2>

<?php /*
<% delete_allowed = User.current.allowed_to?(:manage_files, @project) %>
 */ ?>

<table class="list">
  <thead>
  <?php echo $this->Html->tableHeaders(
  array(
    __('File'),
    __('Date'),
    __('Size'),
    __('D/L'),
    'MD5',
    ''
  )
  ); ?>
<?php /*
  <tr>
    <%= sort_header_tag('filename', :caption => l(:field_filename)) %>
    <%= sort_header_tag('created_on', :caption => l(:label_date), :default_order => 'desc') %>
    <%= sort_header_tag('size', :caption => l(:field_filesize), :default_order => 'desc') %>
    <%= sort_header_tag('downloads', :caption => l(:label_downloads_abbr), :default_order => 'desc') %>
    <th>MD5</th>
    <th></th>
    </tr>
 */ ?>
  </thead>
  <tbody>
<?php foreach($containers as $container): ?>
  <?php if (count($container['Attachment']) == 0) { continue; } ?>
  <?php if (isset($container['Version']['id'])): ?>
  <tr><th colspan="6" align="left"><span class="icon icon-package"><b><?php echo h($container['Version']['name']) ?></b></span></th></tr>
  <?php endif ?>
  <?php foreach($container['Attachment'] as $file): ?>
  <tr class="<?php echo $this->Candy->cycle() ?>">
    <td><?php echo $this->Candy->link_to_attachment($file, array('download' => true, 'title' => $file['Attachment']['description'])) ?></td>
    <td align="center"><?php echo $this->Time->niceShort($file['Attachment']['created_on']) ?></td>
    <td align="center"><?php echo $this->Number->toReadableSize($file['Attachment']['filesize']) ?></td>
    <td align="center"><?php echo $file['Attachment']['downloads'] ?></td>
    <td align="center"><small><?php echo $file['Attachment']['digest'] ?></small></td>
    <td align="center">
<?php if ($delete_allowed): ?>
<?php echo $this->Html->link($this->Html->image('delete.png'), array('controller'=>'attachments', 'action'=>'destroy', 'id'=>$file['Attachment']['id']), array('escape' => false), __('Are you sure ?')) ?>
<?php endif ?>
    </td>
  </tr>
  <?php endforeach ?>
<?php endforeach ?>
  </tbody>
</table>

<?php $this->Candy->html_title(__('Files')) ?>
