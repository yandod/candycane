<?php
if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}
?>

<div class="contextual">
  <?php echo $this->Users->change_status_link($user); ?>
</div>

<h2><?php echo __('User'); ?>: <?php echo h($user['User']['login']); ?></h2>

<?php $selected_tab = isset($this->request->params['named']['tab']) ? $this->request->params['named']['tab'] : 'general'; ?>
<div class="tabs">
	<ul>
		<?php foreach ($settings_tabs as $tab): ?>
			<?php $selected = ($selected_tab == $tab['name']) ? "selected" : ""; ?>
			<!-- <% administration_settings_tabs.each do |tab| -%> -->
			<li><?php echo $this->Html->link(
				$tab['label'],
				array('tab' => $tab['name']),
				array(
					'id' => "tab-" . $tab['name'],
					'class' => $selected,
					'onclick' => "showTab('{$tab['name']}'); this.blur(); return false;",
					'escape' => false,
				)
			); ?></li>
			<!--    <li><%= link_to l(tab[:label]), { :tab => tab[:name] },
				:id => "tab-#{tab[:name]}",
				:class => (tab[:name] != selected_tab ? nil : 'selected'),
				:onclick => "showTab('#{tab[:name]}'); this.blur(); return false;" %></li> -->
			<!-- <% end -%> -->
		<?php endforeach; ?>
	</ul>
</div>

<?php foreach($settings_tabs as $tab): ?>
	<?php $disp = ($selected_tab != $tab['name']) ? 'display:none' : ''; ?>
	<?php echo $this->Html->tag('div',
			$this->element(
					$tab['partial'],
					array(
						'user' => $user,
						'projects' => $projects,
						'roles' => $roles,
					)
			),
		array(
			'id' => 'tab-content-' . $tab['name'],
			'style' => $disp,
			'class' => 'tab-content'
		)
	); ?>
<?php endforeach; ?>

<?php
  echo $this->Js->writeBuffer();
?>

<?php $this->Candy->html_title(__('User')); ?>
