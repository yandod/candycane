<?php /*
vim: filetype=php
*/ ?>
<h2><?php __('Overview') ?></h2> 

<div class="splitcontentleft">
  <?php echo $candy->textilizable($this->data['Project']['description']) ?>	
  <ul>
<?php if (!empty($this->data['Project']['homepage'])): ?>
    <li><?php __('Homepage') ?>: <a href="<?php echo h($this->data['Project']['homepage']) ?>"><?php echo h($this->data['Project']['homepage']) ?></a></li>
<?php endif ?>
<?php if (count($subprojects) > 0): ?>
  <li><?php __('Subprojects') ?>: 
<?php foreach($subprojects as $key=>$subproject): ?>
<?php if ($key != 0) { echo ', '; } ?>
<?php echo $html->link($subproject['Project']['name'], array('controller'=>'projects', 'action'=>'show', 'project_id'=>$subproject['Project']['identifier_or_id'])) ?>
<?php endforeach ?>
</li>
<?php endif ?>
  <?php if ($parent_project): ?>
  <li><?php __('Subproject of') ?>: <?php echo $html->link($parent_project['Project']['name'], array('controller'=>'projects', 'action'=>'show', 'project_id'=>$parent_project['Project']['identifier_or_id'])) ?></li>
  <?php endif ?>
<?php if (isset($custom_values)): ?>
<?php foreach($custom_values as $custom_value): ?>
  <?php if (!empty($custom_value['CustomValue']['value'])): ?>
  <li><?php echo h($custom_value['CustomField']['name']) ?>: <?php echo h($custom_field->show_value($custom_value)) ?></li>
   <?php endif ?>
<?php endforeach ?>
<?php endif ?>
	</ul>	

<?php /*
  <% if User.current.allowed_to?(:view_issues, @project) %>
 */ ?>
  <div class="box">    
    <h3 class="icon22 icon22-tracker"><?php __('Issue tracking') ?></h3>
    <ul>
    <?php foreach($this->data['Tracker'] as $tracker): ?>
    <li><?php echo $html->link($tracker['name'], array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'], '?' . http_build_query(array('set_filter' => '1', 'tracker_id' => $tracker['id']), '', '&'))) ?>:
    <?php echo $tracker['open_issues_by_tracker'] ?> <?php echo $candy->lwr('open', $tracker['open_issues_by_tracker']) ?>
    <?php __("'on'") ?> <?php echo $tracker['total_issues_by_tracker'] ?></li>
    <?php endforeach ?>
    </ul>
    <p><?php echo $html->link(__('View all issues', true), array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'],'?set_filter=1')) ?></p>
  </div>
<?php /*
  <% end %>
*/ ?>
</div>

<div class="splitcontentright">
<?php if (count($members_by_role) > 0): ?>
	<div class="box">
  <h3 class="icon22 icon22-users"><?php __('Members') ?></h3>	
    <p>
<?php foreach($members_by_role as $key=>$members): ?>
      <?php echo h($key) ?>:
      <?php foreach($members as $key2=>$member): ?>
        <?php if ($key2 != 0) { echo ', '; } ?>
        <?php echo $candy->link_to_user($member['User']) ?>
      <?php endforeach ?>
		<br />
<?php endforeach ?>
		</p>
	</div>
<?php endif ?>
    
<?php if ((count($news) > 0) && (true /* authorize_for('news', 'index') */)): ?>
  <div class="box">
    <h3><?php  __('Latest news') ?></h3>  
    <?php echo $this->element('news') ?>
<?php /*
    <%= render :partial => 'news/news', :collection => @news %>
 */ ?>
    <p><?php echo $html->link(__('View all news', true), array('controller'=>'news', 'action'=>'index', 'project_id'=>$this->data['Project']['identifier_or_id'])) ?></p>
  </div>  
<?php endif ?>
</div>

<?php $this->set('Sidebar', $this->renderElement('projects/sidebar/show')) ?>
<?php $this->set('header_tags', $this->renderElement('projects/rss')) ?>

<?php $candy->html_title(__('Overview', true)) ?>
