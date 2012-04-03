<?php /*
vim: filetype=php
 */ ?>
<h2><?php echo __('Change log') ?></h2>

<?php if (count($this->request->data['Version']) == 0): ?>
<p class="nodata"><?php echo __('No data to display') ?></p>
<?php endif ?>

<?php foreach($this->request->data['Version'] as $version): ?>
    <a name="<?php echo h($version['name']) ?>"><h3 class="icon22 icon22-package"><?php echo h($version['name']) ?></h3></a>
    <?php if ($version['effective_date']): ?>
      <p><?php echo $this->Time->niceShort($version['effective_date']) ?></p>
    <?php endif ?>
    <p><?php echo h($version['description']) ?></p>
<?php if (count($version['Issue']) != 0): ?>
    <ul>
<?php foreach($version['Issue'] as $issue): ?>
    <li><?php echo $this->Candy->link_to_issue($issue) ?>: <?php echo h($issue['Issue']['subject']) ?></li>
<?php endforeach ?>
    </ul>
<?php endif ?>
<?php endforeach ?>

<?php $this->set('Sidebar', $this->element('projects/sidebar/changelog')) ?>

