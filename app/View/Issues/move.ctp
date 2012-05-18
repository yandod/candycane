<h2><?php $this->Candy->html_title();__('Move'); ?></h2>

<ul>
  <?php foreach($issue_datas as $issue) : ?>
  <li><?php echo $this->Html->link($issue['Tracker']['name'].' #'.$issue['Issue']['id'], array('action'=>'show', 'id'=>$issue['Issue']['id'])).':'.h($issue['Issue']['subject']);?></li>
  <?php endforeach; ?>
</ul>

<?php echo $this->Form->create(array('url'=>array('action'=>'move','issue_id'=>$issue['Issue']['id']), 'id'=>'IssueMoveForm')); ?>
  <div class="box tabular">
  <?php foreach($issue_datas as $issue) : ?>
    <?php echo $this->Form->hidden('ids', array('name'=>'data[Issue][ids][]', 'value'=>$issue['Issue']['id'])); ?>
  <?php endforeach; ?>
    <p>
      <?php echo $this->Form->label('project_id', __('Project').' :'); ?>
      <?php echo $this->Form->input('project_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$allowed_projects)); ?></p>
    </p>
    <?php echo $ajax->observeField('IssueProjectId', array(
        'url'=>array('action'=>'move','issue_id'=>$issue['Issue']['id']),
        'update'=>'content',
        'allowCache'=>false,
        'with'=>'Form.serialize(\'IssueMoveForm\')'
        )); ?>
    <p>
      <?php echo $this->Form->label('tracker_id', __('Tracker').' :'); ?>
      <?php echo $this->Form->input('tracker_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$trackers, 'empty'=>__('(No change)'))); ?></p>
    </p>
  </div>
<?php echo $this->Form->end(__('Move')); ?>
