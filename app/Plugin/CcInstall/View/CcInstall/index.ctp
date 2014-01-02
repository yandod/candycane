<div class="install index">
    <?php
        $check = true;
		$cmd = "";

        // pdo extension check
        if (extension_loaded('pdo')) {
            echo '<p class="success">' . __('PDO extension is loaded.').'</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('PDO extension is NOT loaded.').'</p>';
        }

        // tmp is writable
        if (is_writable(TMP)) {
            echo '<p class="success">' . __('Your tmp directory is writable.') . '('.TMP.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your tmp directory is NOT writable.').'</p>';
			$cmd .= 'chmod -R 777 '.TMP."\n";
        }

        // Config is writable
        if (is_writable(APP.'Config')) {
            echo '<p class="success">' . __('Your Config directory is writable.') . '('.APP.'Config'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your Config directory is NOT writable.').'</p>';
			$cmd .= 'chmod -R 777 '.APP.'Config'."\n";
        }

        // files is writable
        if (is_writable(APP.'files')) {
            echo '<p class="success">' . __('Your files directory is writable.') . '('.APP.'files'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your files directory is NOT writable.').'</p>';
			$cmd .= 'chmod -R 777 '.APP.'files'."\n";
        }

        // Plugin is writable
        if (is_writable(APP.'Plugin')) {
            echo '<p class="success">' . __('Your Plugin directory is writable.') . '('.APP.'Plugin'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your Plugin directory is NOT writable.').'</p>';
			$cmd .= 'chmod -R 777 '.APP.'Plugin';
        }

        // allow_url_fopen
        $allow_url_fopen = ini_get('allow_url_fopen');
        if ($allow_url_fopen === '1') {
            echo '<p class="success">' . __('Your allow_url_fopen  is working well.').'</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your allow_url_fopen is NOT working well.').'<br/>';
            echo __('Please enable allow_url_fopen on php.ini').'<br/>';
            echo __('Or you can not install plugin from remote.').'</p>';
        }

		// routing
        echo '<p class="success" id="routing-success" style="display:none">' . __('Your routing  is working well.').'</p>';
        echo '<p class="error" id="routing-error">' . __('Your routing is NOT working well.').'<br/>';
        echo __('Please activate mod_rewrite and .htaccess.').'<br/>';
        echo __('Or uncomment "//Configure::write(\'App.baseUrl\', env(\'SCRIPT_NAME\'));" in app/Config/core.php and remove all .htaccess.').'</p>';


        // php version
        // if (phpversion() > 5) {
        //     echo '<p class="success">' . sprintf(__('PHP version %s > 5'), phpversion()) . '</p>';
        // } else {
        //     $check = false;
        //     echo '<p class="error">' . sprintf(__('PHP version %s < 5'), phpversion()) . '</p>';
        // }

        if ($check) {
            echo '<p id="next-success" style="display:none">' . $this->Html->link(__('Click here to begin installation'), array('action' => 'database'), array('id' => 'next-link')) . '</p>';
        } else {
            echo '<p id="next-error">' . __('Installation cannot continue as minimum requirements are not met.');
			echo '<textarea cols="60" rows="6">'.$cmd.'</textarea></p>';
        }
    ?>
</div>
<script>
    $(function(){
        // Document is ready
        $.getJSON("<?php echo $route_url;?>", function(json){
            if (json.status =='OK') {
            $('#routing-error').hide();
            $('#routing-success').show();

            <?php if ($check ==true):?>
                $('#next-error').hide();
                $('#next-success').show();
            <?php endif; ?>
            }
        });

    });
</script>
