<h2><?php $candy->html_title();__('Move'); ?></h2>

<ul>
  <?php foreach($issue_datas as $issue) : ?>
  <li><?php echo $html->link($issue['Tracker']['name'].' #'.$issue['Issue']['id'], array('action'=>'show', 'id'=>$issue['Issue']['id'])).':'.h($issue['Issue']['subject']);?></li>
  <?php endforeach; ?>
</ul>

<?php echo $form->create(array('url'=>array('action'=>'move'), 'id'=>'IssueMoveForm')); ?>
  <div class="box tabular">
  <?php foreach($issue_datas as $issue) : ?>
    <?php echo $form->hidden('ids', array('name'=>'data[Issue][ids][]', 'value'=>$issue['Issue']['id'])); ?>
  <?php endforeach; ?>
    <p>
      <?php echo $form->label('project_id', __('Project', true).' :'); ?>
      <?php echo $form->input('project_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$allowedProjects)); ?></p>
    </p>
    <?php echo $ajax->observeField('IssueProjectId', array(
        'url'=>array('action'=>'move'),
        'update'=>'content',
        'allowCache'=>false,
        'with'=>'Form.serialize(\'IssueMoveForm\')'
        )); ?>
    <p>
      <?php echo $form->label('tracker_id', __('Tracker', true).' :'); ?>
      <?php echo $form->input('tracker_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$trackers, 'empty'=>__('(No change)',true))); ?></p>
    </p>
  </div>
<?php echo $form->end(__('Move',true)); ?>
