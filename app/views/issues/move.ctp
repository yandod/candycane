<h2><?php $candy->html_title();__('Move'); ?></h2>

<ul>
  <li><?php echo $html->link($issue['Tracker']['name'].' #'.$issue['Issue']['id'], array('action'=>'show', 'id'=>$issue['Issue']['id'])).':'.h($issue['Issue']['subject']);?></li>
</ul>

<?php echo $form->create(array('url'=>array('action'=>'move', 'id'=>$issue['Issue']['id']), 'id'=>'IssueMoveForm')); ?>
  <div class="box tabular">
    <p>
      <?php echo $form->label('project_id', __('Project', true).' :'); ?>
      <?php echo $form->input('project_id', array('div'=>false, 'label'=>false, 'type'=>'select', 'options'=>$allowedProjects)); ?></p>
    </p>
    <?php echo $ajax->observeField('IssueProjectId', array(
        'url'=>array('action'=>'move', 'id'=>$issue['Issue']['id']),
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
