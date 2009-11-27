<div class="install">
    <h2><?php echo $this->pageTitle; ?></h2>

    <p>
        Welcome page: <?php echo $html->link(Router::url('/', true), Router::url('/', true)); ?><br />
        Username: admin<br />
        Password: admin
    </p>

    <br />
    <br />

    <p>
        <?php __('Delete the installation directory <strong>/app/plugins/install</strong>.') ?>
    </p>

    <br />
    <br />

    <?php
        echo $html->link(__('Click here to delete installation files', true), array(
            'plugin' => 'install',
            'controller' => 'install',
            'action' => 'finish',
            'delete' => 1,
        ));
    ?>
</div>