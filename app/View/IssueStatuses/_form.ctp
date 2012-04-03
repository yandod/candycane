<%= error_messages_for 'issue_status' %>

<div class="box">
<!--[form:issue_status]-->
<p><label for="issue_status_name"><%=l(:field_name)%><span class="required"> *</span></label>
<%= text_field 'issue_status', 'name'  %></p>

<p><label for="issue_status_is_closed"><%=l(:field_is_closed)%></label>
<%= check_box 'issue_status', 'is_closed' %></p>

<p><label for="issue_status_is_default"><%=l(:field_is_default)%></label>
<%= check_box 'issue_status', 'is_default' %></p>

<!--[eoform:issue_status]-->
</div>