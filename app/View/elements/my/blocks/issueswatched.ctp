<h3><%=l(:label_watched_issues)%></h3>
<% watched_issues = Issue.find(:all, 
                               :include => [:status, :project, :tracker, :watchers],
                               :limit => 10, 
                               :conditions => ["#{Watcher.table_name}.user_id = ? AND #{Project.table_name}.status=#{Project::STATUS_ACTIVE}", user.id],
                               :order => "#{Issue.table_name}.updated_on DESC") %>
<%= render :partial => 'issues/list_simple', :locals => { :issues => watched_issues } %>
<% if watched_issues.length > 0 %>
<p><%=lwr(:label_last_updates, watched_issues.length)%></p>
<% end %>
