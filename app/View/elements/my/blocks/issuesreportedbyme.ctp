<h3><?php echo __('Reported issues') ?></h3>
 <?php
$Issue = ClassRegistry::init('Issue');
$reported_issues = $Issue->find('all',
  array(
    'conditions' => array('Issue.author_id' => $currentuser['id']),
    'limit' => 10,
    'order' => 'Issue.updated_on DESC'
  )
);
?>
<?php echo $this->element('issues/list_simple',array('issues'=>$reported_issues)) ?>
<?php if (count($reported_issues) > 0): ?>
<p class="small"><?php echo $this->Html->link(__('View all issues'), array(
	'controller' => 'issues',
	'action' => 'index',
	'set_filter' => 1,
	'author_id' => 'me'
)) ?></p>
<?php endif; ?>

<?php
//<% content_for :header_tags do %>
//<%= auto_discovery_link_tag(:atom, 
//                            {:controller => 'issues', :action => 'index', :set_filter => 1,
//                             :author_id => 'me', :format => 'atom', :key => User.current.rss_key},
//                            {:title => l(:label_reported_issues)}) %>
//<% end %>
?>
