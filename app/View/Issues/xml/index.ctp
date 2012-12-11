<?xml version="1.0" encoding="UTF-8"?>
<issues total_count="" offset="" limit="" type="array">
  <?php foreach ($issue_list as $issue) : ?>
  <issue>
    <id><?php echo $issue['Issue']['id'] ?></id>
    <project id="<?php echo $issue['Project']['id'] ?>" name="<?php echo h($issue['Project']['name']) ?>" />
    <tracker id="<?php echo $issue['Tracker']['id'] ?>" name="<?php echo h($issue['Tracker']['name']) ?>" />
    <status id="<?php echo $issue['Status']['id'] ?>" name="<?php echo h($issue['Status']['name']) ?>" />
    <priority id="<?php echo $issue['Priority']['id'] ?>" name="<?php echo h($issue['Priority']['name']) ?>" />
    <author id="<?php echo $issue['Author']['id'] ?>" name="<?php echo $this->Candy->format_username($issue['Author']) ?>"/>
    <subject><?php echo h($issue['Issue']['subject']) ?></subject>
    <description><?php echo h($issue['Issue']['description']) ?></description>
    <start_date><?php echo $issue['Issue']['start_date'] ?></start_date>
    <due_date><?php echo $issue['Issue']['due_date'] ?></due_date>
    <done_ratio><?php echo $issue['Issue']['done_ratio'] ?></done_ratio>
    <estimated_hours></estimated_hours>
    <created_on><?php echo $issue['Issue']['created_on'] ?></created_on>
    <updated_on><?php echo $issue['Issue']['updated_on'] ?></updated_on>
  </issue>
  <?php endforeach ?>
</issues>
