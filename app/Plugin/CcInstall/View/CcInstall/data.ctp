<div class="install">
    <h2><?php echo $this->pageTitle; ?></h2>
    <p><?php echo __('Loading initial data to database which you configured.')?></p>
    <?php
        echo $this->Html->link(__('Click here to build your database'), array(
            'plugin' => 'cc_install',
            'controller' => 'cc_install',
            'action' => 'data',
            'run' => 1,
        ));
    ?>
</div>