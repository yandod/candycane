<div class="contextual">
  <?php e($html->link(__('Personalize this page', true), '/my/page_layout',aa('onclick','alert("not yet");return false;'))); ?>
</div>

<h2><?php echo $candy->html_title(__('My page',true), true); ?></h2>

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
<?php echo $javascript->link(a('context_menu')) ?>
<?php echo $html->css('context_menu')  ?>
<div id="context-menu" style="display: none;"></div>
<?php echo $javascript->codeBlock("new ContextMenu('".$html->url(aa('controller','issues','action','context_menu'))."')") ?>
