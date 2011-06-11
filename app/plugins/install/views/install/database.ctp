<div class="install form">
    <h2><?php echo $this->pageTitle; ?></h2>
    <p><?php __('Creating database conection setting. please type valid configuration for your database server.') ?></p>
    <?php
        echo $form->create('Install', array('url' => array('plugin' => 'install', 'controller' => 'install', 'action' => 'database')));
        echo $form->input('Install.host', array('label' => 'Host', 'value' => 'localhost'));
        echo $form->input('Install.login', array('label' => 'User / Login', 'value' => 'root'));
        echo $form->input('Install.password', array('label' => 'Password'));
        echo $form->input('Install.database', array('label' => __('Exsisting database name',true), 'value' => 'candycane'));
        echo $form->input('Install.prefix', array('label' => __('Prefix for table name.(if you need)',true), 'value' => '', 'disabled' => 'disabled'));
        echo $form->end(__('Build database',true));
    ?>
</div>