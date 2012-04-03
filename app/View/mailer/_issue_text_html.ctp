<h1><%= link_to "#{issue.tracker.name} ##{issue.id}: #{issue.subject}", issue_url %></h1>

<ul>
<li><%=l(:field_author)%>: <%= issue.author %></li>
<li><%=l(:field_status)%>: <%= issue.status %></li>
<li><%=l(:field_priority)%>: <%= issue.priority %></li>
<li><%=l(:field_assigned_to)%>: <%= issue.assigned_to %></li>
<li><%=l(:field_category)%>: <%= issue.category %></li>
<li><%=l(:field_fixed_version)%>: <%= issue.fixed_version %></li>
<% issue.custom_values.each do |c| %>
  <li><%= c.custom_field.name %>: <%= show_value(c) %></li>
<% end %>
</ul>

<%= textilizable(issue, :description, :only_path => false) %>
