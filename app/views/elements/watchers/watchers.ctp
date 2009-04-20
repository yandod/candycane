<div class="contextual">
<?php
  if($addIssueWatchersAllowed) {
    echo $ajax->link(__('Add',true), array(
        'controller'=>'watchers','action'=>'add',
        'object_type'=>$object_type,
        'object_id'=>$watched
      ),
      array(
        'update'=>'watchers'
      )
    );
  }
?>
</div>

<p><strong><?php __('Watchers') ?></strong></p>
<?php echo $watchers->watchers_list($list); ?>

  <?php
  if(!empty($members)) :
    echo $ajax->form('Watcher', 'post',
      array('id'=>'new-watcher-form', 'url'=>array('controller'=>'watchers','action'=>'add',
        'object_type'=>$object_type,
        'object_id'=>$watched
      ),
    ));
  ?>
  <p>
    <?php echo $ajax->Form->input('user_id', array('type'=>'select', 'options'=>$members, 'empty'=>'--- '.__('Please Select', true).' ---', 'div'=>false, 'label'=>false));?>
    <?php echo $ajax->Form->submit(__('Add',true), array('div'=>false)); ?>
    <?php echo $candy->toggle_link(__('Cancel',true), 'new-watcher-form');?></p>
  </p>
  <?php echo $ajax->Form->end(); ?>
<?php endif; ?>
