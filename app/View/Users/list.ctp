<?php
/**
 * list.ctp
 *
 */

if( $currentuser[ 'admin' ] )
{
	$this->set( 'Sidebar', $this->element( 'admin/sidebar' ) );
}

// const
$status_type = array('anon', 'active', 'registered', 'locked');
?>
<div class="contextual">
  <?php echo $this->Html->link(__('New user'), '/users/add', array('class' => 'icon icon-add')); ?>
</div>

<h2><?php echo __('Filters'); ?></h2>

<?php
echo $this->Form->create(null, array('type' => 'get', 'url' => '/users/list'));
?>
<fieldset>
  <legend><?php echo __('Filters'); ?></legend>

  <?php echo $this->Form->input('status', array(
    'type' => 'select',
    'options' => $status_option,
    'selected' => array($status),
    'class' => 'small',
    'div' => false,
    'onchange' => 'this.form.submit(); return false;',
    )
  ); ?>

  <?php echo $this->Form->input('name', array(
    'type'  => 'text',
    'class' => '30%',
    'div'   => false,
    'value' => $name,
    )
  ); ?>

  <?php echo $this->Form->submit(__('Apply'), array('class' => 'small', 'name' => null, 'div' => false)); ?>

</fieldset>
<?php echo $this->Form->end(); ?>

&nbsp;

<?php
// echo $this->Sort->sort_link('login', null, 'asc');
/*
echo $ajax->link(__('login'),
  '?sort_key=login&amp;sort_order=desc',
  array(
  ),
  array('update' => 'post')
);
*/
?>
<table class="list">
  <thead>
  <?php echo $this->Html->tableHeaders(
  array(
    'login', 'firstname', 'lastname', 'mail', 'admin', 'created_on', 'last_login_on', ''
  )
  ); ?>
  </thead>
  <tbody>

  <?php foreach($user_list as $user): ?>
  <tr class="user <?php echo $this->Candy->cycle();?> <?php echo $status_type[$user['User']['status']]; ?>">
  <td class="username">
    <?php echo $this->Candy->avatar($user, array('size' => "14")); ?>
    <?php echo $this->Html->link($user['User']['login'], '/users/edit/'.$user['User']['id']); ?>
  </td>
  <td class="firstname"><?php echo h($user['User']['firstname']); ?></td>
  <td class="lastname"><?php echo h($user['User']['lastname']); ?></td>
  <td class="email"><?php echo $this->Text->autoLinkEmails($user['User']['mail']); ?></td>
  <td align="center">
    <?php if ($user['User']['admin'] == '1'): ?>
    <?php echo $this->Html->image('true.png'); ?>
    <?php endif; ?>
  </td>
  <td class="created_on" align="center">
    <?php echo $this->Candy->format_time($user['User']['created_on']); ?>
  </td>
  <td class="last_login_on" align="center">
    <?php echo $this->Candy->format_time($user['User']['last_login_on']); ?>
  </td>
  <td>
    <small><?php echo $this->Users->change_status_link($user); ?></small>
  </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<p class="pagination">
  <!--<%= pagination_links_full @user_pages, @user_count %>-->
</p>
<?php
  echo $this->Js->writeBuffer();
?>
<?php $this->Candy->html_title(__('Users')); ?>