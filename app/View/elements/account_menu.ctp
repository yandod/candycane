<?php if ($currentuser['logged']): ?>
<ul>
  <li><?php echo($this->Html->link(__('My account'), '/my/account', array('class' => 'my-account'))); ?></li>
  <li><?php echo($this->Html->link(__('Sign out'), '/account/logout', array('class' => 'logout'))); ?></li>
</ul>
<?php else:  ?>
<ul>
  <li><?php echo($this->Html->link(__('Sign in'), '/account/login', array('class' => 'login'))); ?></li>
  <li><?php echo($this->Html->link(__('Register'), '/account/register', array('class' => 'register'))); ?></li>
</ul>
<?php endif; ?>
