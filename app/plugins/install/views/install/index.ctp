<div class="install index">
    <?php
        $check = true;

        // tmp is writable
        if (is_writable(TMP)) {
            echo '<p class="success">' . __('Your tmp directory is writable.', true) . '('.TMP.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your tmp directory is NOT writable.', true) .'(chmod -R 777 '.TMP.')</p>';
        }

        // config is writable
        if (is_writable(APP.'config')) {
            echo '<p class="success">' . __('Your config directory is writable.', true) . '('.APP.'config'.')</p>';
        } else {
            $check = false;
            echo '<p class="error">' . __('Your config directory is NOT writable.', true) . '(chmod -R 777 '.APP.'config'.')</p>';
        }

        // php version
        // if (phpversion() > 5) {
        //     echo '<p class="success">' . sprintf(__('PHP version %s > 5', true), phpversion()) . '</p>';
        // } else {
        //     $check = false;
        //     echo '<p class="error">' . sprintf(__('PHP version %s < 5', true), phpversion()) . '</p>';
        // }

        if ($check) {
            echo '<p>' . $html->link('Click here to begin installation', array('action' => 'database')) . '</p>';
        } else {
            echo '<p>' . __('Installation cannot continue as minimum requirements are not met.', true) . '</p>';
        }
    ?>
</div>