<h3><?php echo __('Reported issues') ?></h3>
<?php /*
<% reported_issues = Issue.find(:all, 
                                :conditions => ["author_id=? AND #{Project.table_name}.status=#{Project::STATUS_ACTIVE}", user.id],
                                :limit => 10, 
                                :include => [ :status, :project, :tracker ], 
                                :order => "#{Issue.table_name}.updated_on DESC") %>
<%= render :partial => 'issues/list_simple', :locals => { :issues => reported_issues } %>
<% if reported_issues.length > 0 %>
<p class="small"><%= link_to l(:label_issue_view_all), :controller => 'issues', :action => 'index', :set_filter => 1, :author_id => 'me' %></p>
<% end %>
 */ ?>
 <?php
$Issue = ClassRegistry::init('Issue');
$reported_issues = $Issue->find('all',
  array(
    'conditions' => aa('Issue.author_id',$currentuser['id']),
    'limit' => 10,
    'order' => 'Issue.updated_on DESC'
  )
);
?>
<?php echo $this->element('issues/list_simple',array('issues'=>$reported_issues)) ?>
<?php if (count($reported_issues) > 0): ?>
<p class="small"><?php echo $this->Html->link(__('View all issues'), aa('controller','issues','action','index','set_filter',1,'author_id','me' )) ?></p>
<?php endif; ?>

<?php
//<% content_for :header_tags do %>
//<%= auto_discovery_link_tag(:atom, 
//                            {:controller => 'issues', :action => 'index', :set_filter => 1,
//                             :author_id => 'me', :format => 'atom', :key => User.current.rss_key},
//                            {:title => l(:label_reported_issues)}) %>
//<% end %>
?>
