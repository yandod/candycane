<div class="install index">
    <?php
        $check = true;
		$cmd = "";
        // tmp is writable
        if (is_writable(TMP)) {
            echo '<p class="success">' . __('Your tmp directory is writable.', true) . '('.TMP.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your tmp directory is NOT writable.', true).'</p>';
			$cmd .= 'chmod -R 777 '.TMP."\n";
        }

        // config is writable
        if (is_writable(APP.'config')) {
            echo '<p class="success">' . __('Your config directory is writable.', true) . '('.APP.'config'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your config directory is NOT writable.', true).'</p>';
			$cmd .= 'chmod -R 777 '.APP.'config'."\n";
        }

        // files is writable
        if (is_writable(APP.'files')) {
            echo '<p class="success">' . __('Your files directory is writable.', true) . '('.APP.'files'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your files directory is NOT writable.', true).'</p>';
			$cmd .= 'chmod -R 777 '.APP.'files';
        }
        // php version
        // if (phpversion() > 5) {
        //     echo '<p class="success">' . sprintf(__('PHP version %s > 5', true), phpversion()) . '</p>';
        // } else {
        //     $check = false;
        //     echo '<p class="error">' . sprintf(__('PHP version %s < 5', true), phpversion()) . '</p>';
        // }

        if ($check) {
            echo '<p>' . $html->link(__('Click here to begin installation',true), array('action' => 'database')) . '</p>';
        } else {
            echo '<p>' . __('Installation cannot continue as minimum requirements are not met.', true) . '</p>';
			echo '<textarea cols="60" rows="6">'.$cmd.'</textarea>';
        }
    ?>
</div>