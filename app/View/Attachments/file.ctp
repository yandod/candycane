<h2><?php echo $attachment['filename'] ?></h2>

<div class="attachments">
<p><?php if (!empty($attachment['description'])) { echo h("{$attachment['description']} - "); } ?>
  <span class="author"><?php echo $this->Candy->format_username($author) ?>, <?php echo $this->Candy->format_time($attachment['created_on']) ?></span></p>
<p><?php echo $this->Candy->link_to_attachment( array('Attachment'=>$attachment), array('text' => __('Download'), 'download' => true)); ?>
   <span class="size">(<?php echo $this->Number->toReadableSize($attachment['filesize']) ?>)</span></p>

</div>
&nbsp;
<?php echo $this->element('file', array('content'=>$content)); ?>

<?php $this->Candy->html_title() ?>
<?php $this->Html->css("scm", null, array(), false); ?>
