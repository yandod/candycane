<?php $this->Form->setEntity('Issue', true) ?>
<?php echo $this->element('issues/attribute', array(
    'statuses', 'priorities', 'assignable_users', 'issue_categories',
    'fixed_versions', 'custom_field_values',
)) ?>
