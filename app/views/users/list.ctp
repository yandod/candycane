<?php
/**
 * list.ctp
 *
 */

// const
$status_type = array('anon', 'active', 'registered', 'locked');
?>
<div class="contextual">
  <?php echo $html->link(__('New user', true), '/users/add', array('class' => 'icon icon-add')); ?>
</div>

<h2><?php __('Filters'); ?></h2>

<?php
echo $form->create(null, array('type' => 'get', 'url' => '/users/list'));
?>
<fieldset>
  <legend><?php __('Filters'); ?></legend>

  <?php echo $form->input('status', array(
    'type' => 'select',
    'options' => $status_option,
    'selected' => array($status),
    'class' => 'small',
    'div' => false,
    'onchange' => 'this.form.submit(); return false;',
    )
  ); ?>

  <?php echo $form->input('name', array(
    'type'  => 'text',
    'class' => '30%',
    'div'   => false,
    'value' => $name,
    )
  ); ?>

  <?php echo $form->submit(__('Apply', true), array('class' => 'small', 'name' => null, 'div' => false)); ?>

</fieldset>
<?php echo $form->end(); ?>

&nbsp;

<?php
// echo $sort->sort_link('login', null, 'asc');
/*
echo $ajax->link(__('login', true),
  '?sort_key=login&amp;sort_order=desc',
  array(
  ),
  array('update' => 'post')
);
*/
?>
<table class="list">
  <thead>
  <?php echo $html->tableHeaders(
  array(
    'login', 'firstname', 'lastname', 'mail', 'admin', 'created_on', 'last_login_on', ''
  )
  ); ?>
  </thead>
  <tbody>

  <?php foreach($user_list as $user): ?>
  <tr class="user <?php echo $candy->cycle();?> <?php echo $status_type[$user['User']['status']]; ?>">
  <td class="username">
    <?php echo $candy->avatar($user, array('size' => "14")); ?>
    <?php echo $html->link($user['User']['login'], '/users/edit/'.$user['User']['id']); ?>
  </td>
  <td class="firstname"><?php e(h($user['User']['firstname'])); ?></td>
  <td class="lastname"><?php e(h($user['User']['lastname'])); ?></td>
  <td class="email"><?php echo $text->autoLinkEmails($user['User']['mail']); ?></td>
  <td align="center">
    <?php if ($user['User']['admin'] == '1'): ?>
    <?php echo $html->image('true.png'); ?>
    <?php endif; ?>
  </td>
  <td class="created_on" align="center">
    <?php echo $candy->format_time($user['User']['created_on']); ?>
  </td>
  <td class="last_login_on" align="center">
    <?php echo $candy->format_time($user['User']['last_login_on']); ?>
  </td>
  <td>
    <small><?php echo $users->change_status_link($user); ?></small>
  </td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<p class="pagination">
  <!--<%= pagination_links_full @user_pages, @user_count %>-->
</p>

<?php $candy->html_title(__('Users', true)); ?>
