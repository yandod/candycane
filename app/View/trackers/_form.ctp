<%= error_messages_for 'tracker' %>
<div class="box">
<!--[form:tracker]-->
<p><%= f.text_field :name, :required => true %></p>
<p><%= f.check_box :is_in_chlog %></p>
<p><%= f.check_box :is_in_roadmap %></p>
<% if @tracker.new_record? && @trackers.any? %>
<p><label><%= l(:label_copy_workflow_from) %></label>
<%= select_tag(:copy_workflow_from, content_tag("option") + options_from_collection_for_select(@trackers, :id, :name)) %></p>
<% end %>
<!--[eoform:tracker]-->
</div>
