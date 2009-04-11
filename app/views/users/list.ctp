<?php
$status_type = array(
  'anon', 'active', 'registered', 'locked'
);
?>
<div class="contextual">
  <?php echo $html->link(__('label_user_new', true), '/users/add', array('class' => 'icon icon-add')); ?>
</div>

<h2><?php __('label_filter_plural'); ?></h2>

<?php echo $form->create(null, array('type' => 'get', 'url' => '/users/index')); ?>
<fieldset>
  <legend><?php __('label_filter_plural'); ?></legend>

  <?php echo $form->input('status', array(
    'type' => 'select',
    'options' => $status_option,
    'selected' => array($status),
    'class' => 'small',
    'div' => false,
    'onchange' => 'this.form.submit(); return false;',
    )
  ); ?>

  <?php echo $form->input('name', array('class' => '30%', 'div' => false)); ?>

  <?php echo $form->submit(__('button_apply', true), array('class' => 'small', 'name' => null, 'div' => false)); ?>

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
    <?php echo $html->link(h($user['User']['login']), '/users/edit/'.$user['User']['id']); ?>
  </td>
  <td class="firstname"><?php e(h($user['User']['firstname'])); ?></td>
  <td class="lastname"><?php e(h($user['User']['lastname'])); ?></td>
  <td class="email"><%= mail_to(h(user.mail)) %></td>
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
  <%= pagination_links_full @user_pages, @user_count %>
</p>

<?php $candy->html_title(__('label_user_plural', true)); ?>
