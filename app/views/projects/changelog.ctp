<?php /*
vim: filetype=php
 */ ?>
<h2><?php __('Change log') ?></h2>

<?php if (count($this->data['Version']) == 0): ?>
<p class="nodata"><?php __('No data to display') ?></p>
<?php endif ?>

<?php foreach($this->data['Version'] as $version): ?>
    <a name="<?php echo h($version['name']) ?>"><h3 class="icon22 icon22-package"><?php echo h($version['name']) ?></h3></a>
    <?php if ($version['effective_date']): ?>
      <p><?php echo $time->niceShort($version['effective_date']) ?></p>
    <?php endif ?>
    <p><?php echo h($version['description']) ?></p>
<?php if (count($version['Issue']) != 0): ?>
    <ul>
<?php foreach($version['Issue'] as $issue): ?>
    <li><?php echo $candy->link_to_issue($issue) ?>: <?php echo h($issue['Issue']['subject']) ?></li>
<?php endforeach ?>
    </ul>
<?php endif ?>
<?php endforeach ?>

<?php $this->set('Sidebar', $this->renderElement('projects/sidebar/changelog')) ?>

