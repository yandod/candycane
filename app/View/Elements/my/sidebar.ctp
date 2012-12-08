<h3><?php echo __('My account') ?></h3>

<p><?php echo __('Login') ?>: <strong><?php echo h($currentuser['login']) ?></strong><br />
<?php echo __('Created') ?>: <?php echo $this->Candy->format_time($currentuser['created_on']) ?></p>

<?php if ( !empty($currentuser['RssToken']) ): ?>
  <p><?php $this->Candy->lwr_e('RSS access key created %s ago',$this->Candy->distance_of_time_in_words(time(),$currentuser['RssToken']['created_on'])) ?>
  (<?php echo $this->Html->link(__('Reset'),array('action' => 'reset_rss_key')) ?>)</p>
<?php else: ?>
  <p><?php echo $this->Html->link(__('Create RSS access key'),array('action' => 'reset_rss_key')) ?></p>
<?php endif; ?>

<?php if ( !empty($currentuser['ApiToken']) ): ?>
  <p><?php $this->Candy->lwr_e('API access key created %s ago',$this->Candy->distance_of_time_in_words(time(),$currentuser['ApiToken']['created_on'])) ?>
  (<?php echo $this->Html->link(__('Reset'),array('action' => 'reset_api_key')) ?>)</p>
<?php else: ?>
  <p><?php echo $this->Html->link(__('Create API access key'),array('action' => 'reset_api_key')) ?></p>
<?php endif; ?>
