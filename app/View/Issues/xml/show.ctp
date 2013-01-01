<?xml version="1.0" encoding="UTF-8"?>
<issue>
  <id><?php echo $issue['Issue']['id'] ?></id>
  <project id="<?php echo $issue['Project']['id'] ?>" name="<?php echo h($issue['Project']['name']) ?>" />
  <tracker id="<?php echo $issue['Tracker']['id'] ?>" name="<?php echo h($issue['Tracker']['name']) ?>" />
  <status id="<?php echo $issue['Status']['id'] ?>" name="<?php echo h($issue['Status']['name']) ?>" />
  <priority id="<?php echo $issue['Priority']['id'] ?>" name="<?php echo h($issue['Priority']['name']) ?>" />
  <author id="<?php echo $issue['Author']['id'] ?>" name="<?php echo $this->Candy->format_username($issue['Author']) ?>" />

  <subject><?php echo h($issue['Issue']['subject']) ?></subject>
  <description><?php echo h($issue['Issue']['description']) ?></description>

  <start_date><?php echo h($issue['Issue']['start_date']) ?></start_date>
  <due_date><?php echo h($issue['Issue']['due_date']) ?></due_date>
  <done_ratio><?php echo h($issue['Issue']['done_ratio']) ?></done_ratio>
  <estimated_hours><?php echo h($issue['Issue']['estimated_hours']) ?></estimated_hours>

  <?php if(!empty($issue['CustomValue'])): ?>
    <custom_fields type="array">
      <?php foreach($issue['CustomValue'] as $value): ?>
        <custom_field id="<?php echo $value['CustomField']['id'] ?>" name="<?php echo h($value['CustomField']['name']) ?>">
          <value><?php echo h($this->CustomField->value($value)) ?></value>
        </custom_field>
      <?php endforeach ?>
    </custom_fields>
  <?php endif ?>

  <created_on><?php echo date('c', strtotime($issue['Issue']['created_on'])) ?></created_on>
  <updated_on><?php echo date('c', strtotime($issue['Issue']['updated_on'])) ?></updated_on>
</issue>
