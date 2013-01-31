<div class="contextual">
<?php
  if($addIssueWatchersAllowed) {
    echo $this->Js->link(
		__('Add'), array(
        'controller'=>'watchers','action'=>'add',
        'object_type'=>$object_type,
        'object_id'=>$watched
      ),
      array(
		'buffer' => false,
        'update'=>'watchers'
      )
    );
  }
?>
</div>

<p><strong><?php echo __('Watchers') ?></strong></p>
<?php echo $this->Watchers->watchers_list($list); ?>

  <?php
  if(!empty($members)) :
    $url = array(
		'controller' => 'watchers',
		'action' => 'add',
		'object_type' => $object_type,
		'object_id' => $watched
	);
    echo $this->Form->create(
		'Watcher',
		array(
            'id' => "new-watcher-form",
			'url' => $url,
		    'onsubmit'=> $this->Js->request(
				$url,
				array(
					'url' => $url,
					'form' => true,
					'update' => 'watchers',
					'after' => 'return false'
				)
			)
		)
    );
  ?>
  <p>
    <?php echo $this->Form->input('user_id', array('type'=>'select', 'options'=>$members, 'empty'=>'--- '.__('Please Select').' ---', 'div'=>false, 'label'=>false));?>
    <?php echo $this->Form->submit(__('Add'), array('div'=>false)); ?>
    <?php echo $this->Candy->toggle_link(__('Cancel'), 'new-watcher-form');?></p>
  </p>
  <?php echo $this->Form->end(); ?>
<?php endif; ?>
