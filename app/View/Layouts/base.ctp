<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php echo $title_for_layout; ?></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo Configure::read('app_title'); ?>" />
<meta name="keywords" content="issue,bug,tracker" />
<?php echo $this->Html->css('application'); ?>
<?php echo $this->element('ui_theme'); ?>
<?php echo $this->Html->script(array('prototype','effects','dragdrop','controls','application')); ?>
<?php echo $this->Html->script('https://raw.github.com/cognitom/StaffRoll.net-Libraries-and-Themes/master/include.staffroll.net/github/script/1.0/load.js?theme=underground');?>
<?php echo $this->Html->css('jstoolbar');  ?>
<?php if (isset($header_tags)) echo $header_tags; ?>
<?php echo $this->fetch('meta'); ?>
<?php echo $this->fetch('css'); ?>
<?php echo $this->fetch('script'); ?>
</head>
<body>
<div id="wrapper">
<div id="top-menu">
	<div id="account">
		<?php echo $this->element('account_menu', array('currentuser' => $currentuser)); ?>
	</div>
	<?php if ($currentuser['logged']) echo $this->Html->tag('div',__('Logged in as').' '.$this->Candy->link($currentuser),array('id'=>'loggedas')); ?>
	<?php echo $this->element('top_menu'); ?>
</div>

<div id="header">
	<div id="quick-search">
		<?php echo $this->Form->create(null, array('url' => '/search/index', 'type' => 'get', 'id' => 'searchForm')); ?>
		<?php echo $this->Html->link(__('Search').':','/search/index',$this->Candy->accesskey('search')); ?>
		<?php echo $this->Form->input('q', array(
			'type' => 'text',
			'size' => 20,
			'class' => 'small',
			'accesskey' => $this->Candy->accesskey('quick_search'),
			'div' => false,
			'label' => false
		)); ?>
		<?php echo $this->Form->end(); ?>

		<?php if (!empty($currentuser['memberships'])): ?>
			<?php echo $this->element('project_selector', array('currentuser' => $currentuser)); ?>
		<?php endif; ?>
	</div>

	<h1><?php
	if (isset($main_project['Project']['name'])) {
		echo h($main_project['Project']['name']);
	} else {
		echo $Settings->app_title;
	} ?></h1>

    <?php if(!empty($main_menu)) :?>
        <div id="main-menu">
            <ul>
                <?php foreach ($main_menu as $item): ?>
                    <?php
                    $url = $item;
                    unset($url['class']);
                    unset($url['caption']);
                    $option = array('class' => $item['class']);
                    ?>
                    <li><?php echo $this->Html->link(__($item['caption']),$url,$option); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<?php echo $this->Html->tag('div', null, array('id' => 'main', 'class' => isset($Sidebar) ? '' : 'nosidebar')); ?>
	<div id="sidebar">        
		<?php if (isset($Sidebar)) {
			echo $this->element('sidebar',array('Sidebar' => $Sidebar));
		}?>
	</div>

	<div id="content">
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->fetch('content'); ?>
	</div>
</div>

<div id="ajax-indicator" style="display:none;"><span><?php echo __('Loading...'); ?></span></div>

<div id="footer">
	<?php echo sprintf(
		'Powered by %s &copy 2009 - %s',
		$this->Html->link('CandyCane','https://github.com/yandod/candycane'),
		date('Y')
	); ?><br/>
	
	<?php echo $this->Html->link(__('Report Bug'),'http://my.candycane.jp/'); ?> -
	<?php echo $this->Html->link(
		__('Contributors'),
		'https://github.com/yandod/candycane/contributors',
		array('class' => 'staffroll')
	); ?> -
	<?php echo $this->Html->link(__('Discussion'),'https://groups.google.com/group/candycane-users'); ?><br/>
	
<?php
echo $this->Html->link(
	$this->Html->image(
		'cake.power.gif',
		array(
			'alt' => __("CakePHP: the rapid development php framework"),
			'border' => "0"
		)
	),
	'http://www.cakephp.org/',
	array(
		'target' => '_blank',
		'escape' => false
	)
);
?>
</div>
</div>
</body>
</html>
