<h2><?php echo $attachment['filename'] ?></h2>

<div class="attachments">
<p><?php if (!empty($attachment['description'])) { echo h("{$attachment['description']} - "); } ?>
  <span class="author"><?php echo $candy->format_username($author) ?>, <?php echo $candy->format_time($attachment['created_on']) ?></span></p>
<p><?php echo $candy->link_to_attachment( array('Attachment'=>$attachment), array('text' => __('Download',true), 'download' => true)); ?>
   <span class="size">(<?php echo $number->toReadableSize($attachment['filesize']) ?>)</span></p>

</div>
&nbsp;
<?php echo $this->renderElement('diff', array('diff'=>$diff, 'diff_type'=>$diff_type)); ?>

<?php $candy->html_title(); ?>
<?php $html->css("scm", null, array(), false); ?>
