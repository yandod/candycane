<h3><?php __('My account') ?></h3>

<p><?php __('Login') ?>: <strong><?php echo h($currentuser['login']) ?></strong><br />
<?php __('Created') ?>: <?php echo $candy->format_time($currentuser['created_on']) ?></p>
<?php if ( !empty($currentuser['RssToken']) ): ?>
<p><?php $candy->lwr_e('RSS access key created %s ago',$candy->distance_of_time_in_words(time(),$currentuser['RssToken']['created_on'])) ?>
(<?php echo $html->link(__('Reset',true),aa('action','reset_rss_key')) ?>)</p>
<?php else: ?>
<p><?php echo $html->link(__('Create RSS access key',true),aa('action','reset_rss_key')) ?></p>
<?php endif; ?>
