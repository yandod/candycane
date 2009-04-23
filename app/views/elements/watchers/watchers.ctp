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
    $url = array('controller'=>'watchers','action'=>'add', 'object_type'=>$object_type, 'object_id'=>$watched);
    echo $form->create('Watcher', array(
        'id'=>"new-watcher-form", 'url'=>$url,
        'onsubmit'=>$ajax->remoteFunction(array('url'=>$url, 'form'=>true, 'update'=>'watchers', 'after'=>'return false'))
      )
    );
  ?>
  <p>
    <?php echo $form->input('user_id', array('type'=>'select', 'options'=>$members, 'empty'=>'--- '.__('Please Select', true).' ---', 'div'=>false, 'label'=>false));?>
    <?php echo $form->submit(__('Add',true), array('div'=>false)); ?>
    <?php echo $candy->toggle_link(__('Cancel',true), 'new-watcher-form');?></p>
  </p>
  <?php echo $form->end(); ?>
<?php endif; ?>
