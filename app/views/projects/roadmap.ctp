<h2><?php __('Roadmap') ?></h2>

<?php if (count($this->data['Version']) == 0): ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php else: ?>
<div id="roadmap">
<?php foreach($this->data['Version'] as $version): ?>
	<?php 
	if( !isset($this->params['url']['completed']) || !$this->params['url']['completed']) {
		if ($version['completed']) {
			continue;
		}
	}
	?>
    <?php echo $html->tag('a', null, array('name' => $version['name'])) ?>
    <h3 class="icon22 icon22-package"><?php echo $html->link($version['name'], '/versions/show/'.$version['id']) ?></h3>
    <?php echo $this->element('versions/overview', array('version' => $version, 'fixed_issue_count' => count($version['Issue']))) ?>
<?php /*
    <%= render(:partial => "wiki/content", :locals => {:content => version.wiki_page.content}) if version.wiki_page %>
 */ ?>
    <?php if (count($issues) > 0): ?>
    <fieldset class="related-issues"><legend><?php __('Related issues') ?></legend>
    <ul>
    <?php foreach($issues as $issue): ?>
      <li><%= link_to_issue(issue) %>: <?php echo h($issue['Issue']['subject']) ?></li>
    <?php endforeach ?>
    </ul>
    </fieldset>
    <?php endif ?>
<?php endforeach ?>
</div>
<?php endif ?>

<?php $this->set('Sidebar', $this->renderElement('projects/sidebar/roadmap')) ?>

<?php $candy->html_title(__('Roadmap', true)) ?>
