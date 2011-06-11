<div class="install">
    <h2><?php echo $this->pageTitle; ?></h2>
    <p><?php echo __('Loading initial data to database which you configured.',true)?></p>
    <?php
        echo $html->link(__('Click here to build your database', true), array(
            'plugin' => 'install',
            'controller' => 'install',
            'action' => 'data',
            'run' => 1,
        ));
    ?>
</div>