<div class="attachments">
<?php foreach($attachments as $attachment): ?>
<p><?php echo $candy->link_to_attachment($attachment, array('class'=>'icon icon-attachment'));?>
<?php if($attachment['Attachment']['description'] != '') echo h(" - ".$attachment['Attachment']['description']); ?>
  <span class="size">(<?php echo $number->toReadableSize($attachment['Attachment']['filesize']);?>)</span>
  <?php 
  if($options['deletable']){
    echo $html->link($html->image('delete.png'), 
        array('controller'=>'attachments', 'action'=>'destroy', 'id'=>$attachment['Attachment']['id']),
        array('method'=>'post', 'class'=>'delete', 'title'=>__('Delete',true)),
        __('Are you sure ?',true), false);
  }
  ?>
  <?php if($options['Author']): ?>
    <span class="author"><?php echo $candy->format_username($attachment['Author']); ?>, <?php echo $candy->format_time($attachment['Attachment']['created_on']); ?></span>
  <?php endif; ?>
  </p>
<?php endforeach; ?>
</div>
