<h2><?php echo $this->Candy->html_title(__('Overview')) ?></h2>

<div class="splitcontentleft">
  <?php echo $this->Candy->textilizable($this->request->data['Project']['description']) ?>
  <ul>
    <?php if (!empty($this->request->data['Project']['homepage'])): ?>
      <li><?php echo __('Homepage') ?>: <a href="<?php echo h($this->request->data['Project']['homepage']) ?>"><?php echo h($this->request->data['Project']['homepage']) ?></a></li>
    <?php endif ?>

    <?php if (count($subprojects) > 0): ?>
      <li>
        <?php echo __('Subprojects') ?>:
        <?php foreach($subprojects as $key => $subproject): ?>
          <?php if ($key != 0) { echo ', '; } ?>
          <?php echo $this->Html->link($subproject['Project']['name'], array('controller'=>'projects', 'action'=>'show', 'project_id'=>$subproject['Project']['identifier_or_id'])) ?>
        <?php endforeach ?>
      </li>
    <?php endif ?>

    <?php if ($parent_project): ?>
      <li><?php echo __('Subproject of') ?>: <?php echo $this->Html->link($parent_project['Project']['name'], array('controller'=>'projects', 'action'=>'show', 'project_id'=>$parent_project['Project']['identifier_or_id'])) ?></li>
    <?php endif ?>
    <?php if (isset($main_project['CustomValue'])): ?>
      <?php foreach($main_project['CustomValue'] as $custom_value): ?>
        <?php if (!empty($custom_value['value'])): ?>
          <?php $custom_value['CustomValue'] = $custom_value; ?>
          <li><?php echo h($custom_value['CustomField']['name']) ?>: <?php echo h($this->CustomField->show_value($custom_value)) ?></li>
        <?php endif ?>
      <?php endforeach ?>
    <?php endif ?>
  </ul>

  <div class="box">
    <h3 class="icon22 icon22-tracker"><?php echo __('Issue tracking') ?></h3>
    <ul>
      <?php foreach($this->request->data['Tracker'] as $tracker): ?>
        <li>
          <?php echo $this->Html->link($tracker['name'], array(
            'controller' => 'issues',
            'action'     => 'index',
            'project_id' => $this->request->data['Project']['identifier_or_id'],
            '?' . http_build_query(array('set_filter' => '1', 'tracker_id' => $tracker['id']), '', '&')))
          ?>:
          <?php echo $tracker['open_issues_by_tracker'] ?> <?php echo $this->Candy->lwr('open', $tracker['open_issues_by_tracker']) ?>
          <?php echo __("'on'") ?> <?php echo $tracker['total_issues_by_tracker'] ?>
        </li>
      <?php endforeach ?>
    </ul>
    <p><?php echo $this->Html->link(__('View all issues'), array('controller'=>'issues', 'action'=>'index', 'project_id'=>$this->request->data['Project']['identifier_or_id'],'?set_filter=1')) ?></p>
  </div>
</div>

<div class="splitcontentright">
  <?php if (count($members_by_role) > 0): ?>
  <div class="box">
    <h3 class="icon22 icon22-users"><?php echo __('Members') ?></h3>
    <p>
      <?php foreach($members_by_role as $key=>$members): ?>
        <?php echo h($key) ?>:
        <?php foreach($members as $key2=>$member): ?>
          <?php if ($key2 != 0) { echo ', '; } ?>
          <?php echo $this->Candy->link_to_user($member['User']) ?>
        <?php endforeach ?>
        <br />
      <?php endforeach ?>
    </p>
  </div>
  <?php endif ?>

  <?php if ((count($news) > 0) && (true /* authorize_for('news', 'index') */)): ?>
    <div class="box">
      <h3><?php echo __('Latest news') ?></h3>
      <?php echo $this->element('news') ?>
      <p><?php echo $this->Html->link(__('View all news'), array('controller'=>'news', 'action'=>'index', 'project_id'=>$this->request->data['Project']['identifier_or_id'])) ?></p>
    </div>
    <?php endif ?>
</div>

<?php $this->set('Sidebar', $this->element('projects/sidebar/show')) ?>
<?php $this->set('header_tags', $this->element('projects/rss')) ?>
