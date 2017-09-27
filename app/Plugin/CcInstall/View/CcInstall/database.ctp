<div class="install form">
    <h2><?php echo $pageTitle; ?></h2>
    <p><?php echo __('Creating database conection setting. please type valid configuration for your database server.') ?></p>
    <?php
        echo $this->Form->create('Install', array('url' => array('plugin' => 'cc_install', 'controller' => 'cc_install', 'action' => 'database')));
        echo $this->Form->input('Install.datasource', array('type' => 'select', 'label' => 'Driver', 'options' => array('mysql' => 'MySQL', 'postgres'=>'PostgreSQL', 'sqlite' => "Sqlite")));
        echo $this->Form->input('Install.host', array('label' => 'Host', 'value' => 'localhost'));
        echo $this->Form->input('Install.login', array('label' => 'User / Login', 'value' => 'root'));
        echo $this->Form->input('Install.password', array('label' => 'Password'));
        echo $this->Form->input('Install.database', array('label' => __('Exsisting database name'), 'value' => 'candycane'));
        echo $this->Form->input('Install.filename', array('label' => __('Filename. You may include relative path from app/webroot'), 'value' => 'candycane.sqlite'));
        echo $this->Form->input('Install.prefix', array('label' => __('Prefix for table name.(only for mysql, if you need)'), 'value' => ''));
        echo $this->Form->submit(__('Build database'),array('id' => 'database-submit'));
        echo $this->Form->end();
    ?>
</div>
<script>
    $(function(){
        $("#InstallFilename").parent().addClass('hidden');
        
        $("#InstallDatasource").change(function(){
            var isSqlite = $(this).val() == 'sqlite';

            $('#InstallPrefix').parent().toggleClass('hidden', isSqlite);
            $('#InstallPassword').parent().toggleClass('hidden', isSqlite);
            $('#InstallLogin').parent().toggleClass('hidden', isSqlite);
            $('#InstallHost').parent().toggleClass('hidden', isSqlite);
            $('#InstallDatabase').parent().toggleClass('hidden', isSqlite);
            $('#InstallFilename').parent().toggleClass('hidden', !isSqlite);
        });
    });
</script>