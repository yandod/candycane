<p>
  <label for="UserFirstname"><?php echo __('Firstname') ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('firstname',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
</p>
<p>
  <label for="UserLastname"><?php echo __('Lastname') ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('lastname',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
</p>
<p>
  <label for="UserEmail"><?php echo __('Email') ?> <span class="required">*</span></label>
  <?php echo $this->Form->input('mail',array('div' => false,'label' => false,'size' => 30,'error' => false)); ?>
</p>
<p>
  <label for="UserLanguage"><?php echo __('Language'); ?></label>
  <?php echo $this->Form->select(
    'language',
    $this->Candy->lang_options_for_select(),
    array(
      'type' => 'select',
      'error' => false,
      'value' => $currentuser['language']
  )); ?>
</p>
