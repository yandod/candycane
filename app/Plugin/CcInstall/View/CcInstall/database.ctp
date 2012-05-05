<div class="install form">
    <h2><?php echo $this->pageTitle; ?></h2>
    <p><?php echo __('Creating database conection setting. please type valid configuration for your database server.') ?></p>
    <?php
        echo $this->Form->create('Install', array('url' => array('plugin' => 'cc_install', 'controller' => 'cc_install', 'action' => 'database')));
        echo $this->Form->input('Install.host', array('label' => 'Host', 'value' => 'localhost'));
        echo $this->Form->input('Install.login', array('label' => 'User / Login', 'value' => 'root'));
        echo $this->Form->input('Install.password', array('label' => 'Password'));
        echo $this->Form->input('Install.database', array('label' => __('Exsisting database name'), 'value' => 'candycane'));
        echo $this->Form->input('Install.prefix', array('label' => __('Prefix for table name.(if you need)'), 'value' => '', 'disabled' => 'disabled'));
        echo $this->Form->end(__('Build database'));
    ?>
</div>