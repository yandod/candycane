<div class="contextual">
  <?php echo $this->Html->link(__('Personalize this page'), '/my/page_layout',aa('onclick','alert("not yet");return false;')); ?>
</div>

<h2><?php echo $this->Candy->html_title(__('My page'), true); ?></h2>

<div id="list-top">
  <?php if (isset($blocks['top'])):
  foreach($blocks['top'] as $b): ?>
  <div class="mypage-box">        
    <?php echo $this->renderElement("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<div id="list-left" class="splitcontentleft">
  <?php if (isset($blocks['left'])):
  foreach($blocks['left'] as $b): ?>
  <div class="mypage-box">
    <?php echo $this->renderElement("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>

<div id="list-right" class="splitcontentright">
  <?php if (isset($blocks['right'])):
  foreach($blocks['right'] as $b): ?>
  <div class="mypage-box">        
    <?php echo $this->renderElement("my/blocks/{$b}") ?>        
  </div>
  <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php echo $javascript->link(array('context_menu')) ?>
<?php echo $this->Html->css('context_menu')  ?>
<div id="context-menu" style="display: none;"></div>
<?php echo $javascript->codeBlock("new ContextMenu('".$this->Html->url(aa('controller','issues','action','context_menu'))."')") ?>
