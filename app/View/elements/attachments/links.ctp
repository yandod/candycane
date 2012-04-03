<div class="attachments">
<?php foreach($attachments as $attachment): ?>
<p><?php echo $this->Candy->link_to_attachment($attachment, array('class'=>'icon icon-attachment'));?>
<?php if($attachment['Attachment']['description'] != '') echo h(" - ".$attachment['Attachment']['description']); ?>
  <span class="size">(<?php echo $this->Number->toReadableSize($attachment['Attachment']['filesize']);?>)</span>
  <?php 
  if($options['deletable']){
    echo $this->Html->link($this->Html->image('delete.png'), 
        array('controller'=>'attachments', 'action'=>'destroy', 'id'=>$attachment['Attachment']['id']),
        array('method'=>'post', 'class'=>'delete', 'title'=>__('Delete')),
        __('Are you sure ?'), false);
  }
  ?>
  <?php if($options['Author']): ?>
    <span class="author"><?php echo $this->Candy->format_username($attachment['Author']); ?>, <?php echo $this->Candy->format_time($attachment['Attachment']['created_on']); ?></span>
  <?php endif; ?>
  </p>
<?php endforeach; ?>
</div>
