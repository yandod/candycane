<h3><?php echo __('My account') ?></h3>

<p><?php echo __('Login') ?>: <strong><?php echo h($currentuser['login']) ?></strong><br />
<?php echo __('Created') ?>: <?php echo $this->Candy->format_time($currentuser['created_on']) ?></p>

<?php if ( !empty($currentuser['RssToken']) ): ?>
  <p><?php $this->Candy->lwr_e('RSS access key created %s ago',$this->Candy->distance_of_time_in_words(time(),$currentuser['RssToken']['created_on'])) ?>
  (<?php echo $this->Html->link(__('Reset'),array('action' => 'reset_rss_key')) ?>)</p>
<?php else: ?>
  <p><?php echo $this->Html->link(__('Create RSS access key'),array('action' => 'reset_rss_key')) ?></p>
<?php endif; ?>

<h4><?php echo __('API access key') ?></h4>
<?php if ( !empty($currentuser['ApiToken']) ): ?>
  <div>
    <a href="#" onclick="$('api-access-key').toggle(); return false;"><?php echo __('Show') ?></a>
    <pre id='api-access-key' class='autoscroll' style="display: none;"><?php echo h($currentuser['ApiToken']['value']) ?></pre>
  </div>
  <p><?php $this->Candy->lwr_e('API access key created %s ago',$this->Candy->distance_of_time_in_words(time(),$currentuser['ApiToken']['created_on'])) ?>
  (<?php echo $this->Html->link(__('Reset'),array('action' => 'reset_api_key')) ?>)</p>
<?php else: ?>
  <p><?php echo $this->Html->link(__('Create API access key'),array('action' => 'reset_api_key')) ?></p>
<?php endif; ?>
