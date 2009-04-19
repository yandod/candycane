<div class="contextual">
<?php
  if($addIssueWatchersAllowed) {
    echo $ajax->link(__('Add',true), array(
      'controller'=>'watchers','action'=>'add',
      'object_type'=>$object_type,
      'object_id'=>$watched));
  }
?>
</div>

<p><strong><?php __('Watchers') ?></strong></p>
<?php echo $watchers->watchers_list($list); ?>

<% unless @watcher.nil? %>
<% remote_form_for(:watcher, @watcher, 
                   :url => {:controller => 'watchers',
                            :action => 'new',
                            :object_type => watched.class.name.underscore,
                            :object_id => watched},
                   :method => :post,
                   :html => {:id => 'new-watcher-form'}) do |f| %>
<p><%= f.select :user_id, (watched.addable_watcher_users.collect {|m| [m.name, m.id]}), :prompt => true %>

<%= submit_tag l(:button_add) %>
<%= toggle_link l(:button_cancel), 'new-watcher-form'%></p>
<% end %>
<% end %>
