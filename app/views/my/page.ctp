<div class="contextual">
  <?php e($html->link(__('Personalize this page', true), '/my/page_layout')); ?>
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
<!-- 
<% content_for :header_tags do %>
    <%= javascript_include_tag 'context_menu' %>
    <%= stylesheet_link_tag 'context_menu' %>
<% end %>
-->
<div id="context-menu" style="display: none;"></div>
<%= javascript_tag "new ContextMenu('#{url_for(:controller => 'issues', :action => 'context_menu')}')" %>

