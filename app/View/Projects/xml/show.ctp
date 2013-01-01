<?xml version="1.0" encoding="UTF-8"?>
<?php $project = $this->request->data['Project'] ?>
<project>
  <id><?php echo $project['id'] ?></id>
  <name><?php echo h($project['name']) ?></name>
  <identifier><?php echo h($project['identifier']) ?></identifier>
  <homepage><?php echo h($project['homepage']) ?></homepage>
  <description><?php echo h($project['description']) ?></description>
  <created_on><?php echo date('c', strtotime($project['created_on'])) ?></created_on>
  <updated_on><?php echo date('c', strtotime($project['updated_on'])) ?></updated_on>
</project>
