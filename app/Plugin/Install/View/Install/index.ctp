<div class="install index">
    <?php
        $check = true;
		$cmd = "";
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

		// routing
		if (isset($file['status']) && $file['status'] === 'OK') {
            echo '<p class="success">' . __('Your routing  is working well.').'</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your routing is NOT working well.').'</p>';
			echo '<p class="error">' . __('Please activate mod_rewrite and .htaccess.').'</p>';
			echo '<p class="error">' . __('Or uncomment "//Configure::write(\'App.baseUrl\', env(\'SCRIPT_NAME\'));" in app/Config/core.php and remove all .htaccess.').'</p>';
        }


        // php version
        // if (phpversion() > 5) {
        //     echo '<p class="success">' . sprintf(__('PHP version %s > 5'), phpversion()) . '</p>';
        // } else {
        //     $check = false;
        //     echo '<p class="error">' . sprintf(__('PHP version %s < 5'), phpversion()) . '</p>';
        // }

        if ($check) {
            echo '<p>' . $this->Html->link(__('Click here to begin installation'), array('action' => 'database')) . '</p>';
        } else {
            echo '<p>' . __('Installation cannot continue as minimum requirements are not met.') . '</p>';
			echo '<textarea cols="60" rows="6">'.$cmd.'</textarea>';
        }
    ?>
</div>
