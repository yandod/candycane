<p><label><?php echo __('Hide my email address') ?></label><?php echo $this->Form->checkbox('UserPreference.hide_mail',array('value' => '1','checked' => $this->request->data['UserPreference']['hide_mail'])) ?></p>
<!-- <p><label><?php echo __('Time zone') ?></label></p> -->
<p><label><?php echo __('Display comments') ?></label><?php 
	echo $this->Form->select(
		'UserPreference.pref.comments_sorting',
		array(
			'asc' => __('In chronological order'),
			'desc' => __('In reverse chronological order')
		)) ?></p>
