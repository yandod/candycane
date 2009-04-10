<?php if ($currentuser['logged']): ?>
<ul>
  <li><?php echo($html->link(__('My account',true), '/my/account', array('class' => 'my-account'))); ?></li>
  <li><?php echo($html->link(__('Sign out',true), '/account/logout', array('class' => 'logout'))); ?></li>
</ul>
<?php else:  ?>
<ul>
  <li><?php echo($html->link(__('Sign in',true), '/account/login', array('class' => 'login'))); ?></li>
  <li><?php echo($html->link(__('Register',true), '/account/register', array('class' => 'register'))); ?></li>
</ul>
<?php endif; ?>
