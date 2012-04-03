<p><%= l(:mail_body_reminder, @issues.size, @days) %></p>

<ul>
<% @issues.each do |issue| -%>
    <li><%=h "#{issue.project} - #{issue.tracker} ##{issue.id}: #{issue.subject}" %></li>
<% end -%>
</ul>

<p><%= link_to l(:label_issue_view_all), @issues_url %></p>
