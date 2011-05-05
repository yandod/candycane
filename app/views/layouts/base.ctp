<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php echo $title_for_layout; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo Configure::read('app_title'); ?>" />
<meta name="keywords" content="issue,bug,tracker" />
<?php echo $html->css('application')  ?>
<?php echo $javascript->link(a('prototype','effects','dragdrop','controls','application')) ?>
<!-- <%= heads_for_wiki_formatter %> -->
<?php echo $html->css('jstoolbar')  ?>
<!--[if IE]>
    <style type="text/css">
      * html body{ width: expression( document.documentElement.clientWidth < 900 ? '900px' : '100%' ); }
      body {behavior: url(<%= stylesheet_path "csshover.htc" %>);}
    </style>
<![endif]-->
<!-- <%= call_hook :view_layouts_base_html_head %> -->
<!-- page specific tags -->
<!-- <%= yield :header_tags -%> -->
<?php if (isset($header_tags)) echo $header_tags; ?>
    <?php echo $scripts_for_layout; ?>
</head>
<body>
<div id="wrapper">
<div id="top-menu">
    <div id="account">
        <!-- <%= render_menu :account_menu -%> -->
        <?php echo $this->renderElement('account_menu',aa('currentuser',$currentuser)) ?>
    </div>
    <?php if ($currentuser['logged']) echo $html->tag('div',__('Logged in as',true).' '.$candy->link($currentuser),array('id'=>'loggedas')); ?>
    <?php echo $this->renderElement('top_menu') ?>
</div>
      
<div id="header">
    <div id="quick-search">
        <?php echo $form->create(null,aa('url','/search/index','type','get')) ?>
        <!-- <% form_tag({:controller => 'search', :action => 'index', :id => @project}, :method => :get ) do %> -->
        <?php echo $html->link(__('Search',true).':','/search/index',$candy->accesskey('search')) ?>
        <?php echo $form->input('q',aa('type','text','size',20,'class','small','accesskey',$candy->accesskey('quick_search'),'div',false,'label',false)) ?>
<!--        <%= text_field_tag 'q', @question, :size => 20, :class => 'small', :accesskey => accesskey(:quick_search) %> -->
        <?php echo $form->end() ?>
        <?php if( !empty($currentuser['memberships']) ): ?>
        <?php echo $this->renderElement('project_selector',aa('currentuser',$currentuser)) ?>
        <?php endif; ?>
    </div>
         <h1><?php if (isset($main_project['Project']['name'])) { echo h($main_project['Project']['name']); } else { echo $Settings->app_title; } ?></h1>
<!--     <h1><%= h(@project && !@project.new_record? ? @project.name : Setting.app_title) %></h1> -->
    
    <div id="main-menu">
		<ul>
<?php foreach ($main_menu as $item): ?>
<?php
  $url = $item;
  unset($url['class']);
  unset($url['caption']);
  $option = aa('class',$item['class']); 
?>
			<li><?php echo $html->link(__($item['caption'],true),$url,$option); ?></li>
<?php endforeach; ?>
		</ul>
<!--         <%= render_main_menu(@project) %> -->
    </div>
</div>

<!-- <%= tag('div', {:id => 'main', :class => (has_content?(:sidebar) ? '' : 'nosidebar')}, true) %> -->
<?php echo $html->tag('div',null,aa('id','main','class',isset($Sidebar) ? '' : 'nosidebar')) ?>
    <div id="sidebar">        
        <?php if (isset($Sidebar)) echo $Sidebar; ?>
    </div>
    
    <div id="content">
		<?php $session->flash(); ?>
        <?php echo $content_for_layout; ?>
    </div>
</div>

<div id="ajax-indicator" style="display:none;"><span><?php __('Loading...') ?></span></div>
	
<div id="footer">
    Powered by <?php echo $html->link('candycane','https://www.ohloh.net/p/candycane') ?> &copy; 2009 - <?php echo date('Y')?> candycane team
			<br/><?php echo $html->link(
					$html->image('cake.power.gif', array('alt'=> __("CakePHP: the rapid development php framework", true), 'border'=>"0")),
					'http://www.cakephp.org/',
					array('target'=>'_blank'), null, false
				);
			?>
</div>
</div>
</body>
</html>
