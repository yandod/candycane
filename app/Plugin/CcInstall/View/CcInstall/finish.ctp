<div class="install">
    <h2><?php echo $this->pageTitle; ?></h2>

    <p>
        Welcome page: <?php echo $this->Html->link(Router::url('/', true), Router::url('/', true)); ?><br />
        Username: admin<br />
        Password: admin
    </p>

    <br />
    <br />

    <p>
        <?php echo __('Delete the installation directory <strong>/app/Plugin/install</strong>.') ?>
    </p>

    <br />
    <br />

    <?php
        echo $this->Html->link(__('Click here to delete installation files'), array(
            'plugin' => 'cc_install',
            'controller' => 'cc_install',
            'action' => 'finish',
            'delete' => 1,
        ));
    ?>
</div>