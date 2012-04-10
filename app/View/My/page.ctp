<div class="contextual">
  <?php echo $this->Html->link(__('Personalize this page'), '/my/page_layout',array(
	'onclick' => 'alert("not yet");return false;')); ?>
</div>

<h2><?php echo $this->Candy->html_title(__('My page'), true); ?></h2>

<div id="list-top">
  <?php if (isset($blocks['top'])):
  foreach($blocks['top'] as $b): ?>
  <div class="mypage-box">        
    <?php echo $this->element("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<div id="list-left" class="splitcontentleft">
  <?php if (isset($blocks['left'])):
  foreach($blocks['left'] as $b): ?>
  <div class="mypage-box">
    <?php echo $this->element("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<div id="list-right" class="splitcontentright">
  <?php if (isset($blocks['right'])):
  foreach($blocks['right'] as $b): ?>
  <div class="mypage-box">        
    <?php echo $this->element("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php echo $this->Html->script(array('context_menu')) ?>
<?php echo $this->Html->css('context_menu')  ?>
<div id="context-menu" style="display: none;"></div>
<?php echo $this->Html->scriptBlock("new ContextMenu('".$this->Html->url(array(
	'controller' => 'issues',
	'action' => 'context_menu'
))."')") ?>
