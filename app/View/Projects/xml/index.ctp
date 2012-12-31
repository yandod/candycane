<?xml version="1.0" encoding="UTF-8"?>
<projects total_count="<?php echo count($project_tree) ?>" offset="" limit="" type="array">
  <?php foreach ($project_tree as $project) : ?>
  <project>
    <id><?php echo $project['id'] ?></id>
    <name><?php echo h($project['name']) ?></name>
    <identifier><?php echo h($project['identifier']) ?></identifier>
    <description><?php echo h($project['description']) ?></description>
    <created_on><?php echo $project['created_on'] ?></created_on>
    <updated_on><?php echo $project['updated_on'] ?></updated_on>
  </project>
  <?php endforeach ?>
</projects>
