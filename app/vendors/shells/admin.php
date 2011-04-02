<?php
/**
 *
 * @url http://c-brains.jp/blog/wsg/09/07/22-170044.php
 * @url http://d.hatena.ne.jp/yuhei_kagaya/20080730/1217421386
 * @url http://blog.takeda-soft.jp/blog/show/187
 */

App::import('Model', 'ConnectionManager');

class AdminShell extends Shell {

    //var $tasks = array('Menu1','Menu2','Menu3');

    //overrideでcakeメッセージ除去
    function startup(){}

    function main() {
        //メインメニュー作成
        $mainMenu = array(
            '1' => array('name' => 'Menu2','alt' => '初期設定を行う'),
            '2' => array('name' => 'Menu1','alt' => '指定ユーザをadminに昇格'),
            //'3' => array('name' => 'Menu3','alt' => 'メニュー3'),
            'q' => array('name' => null,'alt' => '終了'),
            );
        $mainMenuKeys = array_keys($mainMenu);

        //メインメニュー表示
        $value = "";
        $this->out("---------------------------------------------");
        foreach($mainMenu as $k => $v) {
            $this->out("[{$k}] {$v['alt']}");
        }
        $this->out("---------------------------------------------");
        while ($value <> "q") {
            $value = $this->in("実行するメニューの番号を選択してください", $mainMenuKeys, "q" );

            if ($value <> 'q') {
                //$this->$mainMenu[$value]['name']->execute();
            }

            switch ($value) {
            case '1':
                $this->install();
                break;
            case '2':
                $this->upgrade();
                break;
            }
        }
    }

    function install()
    {
        $db_config = new DATABASE_CONFIG;

        $db_config = $db_config->default;
        unset($db_config['persistent']);

        $keys = array_flip(array_keys($db_config));
        while (true) {
            $this->out("---------------------------------------------");
            $this->out("DATABASE_CONFIG:");
            foreach($db_config as $k => $v) {
                $this->out("[{$keys[$k]}] [{$k}] {$v}");
            }
            $this->out("---------------------------------------------");
            $value = $this->in("変更したい項目の番号を選択してください。これでよければyを押してください。", array_values($keys), "q");

            if ($value == 'q') {
                break;
            }

            if ($value == 'y') {

                // 接続確認
                $db = ConnectionManager::getDataSource('default');
                $db->config = array_merge($db->_baseConfig, $db_config);
                if ($db->connect() === false) {
                    $this->err("接続できません。データベースの設定が間違っている可能性があります。");
                    $db->disconnect();
                    continue;
                } else {
                    $db->disconnect();
                }

                // @TODO database.php.installの内容をもとにデータをいれる
                $this->rewriteConfig($db_config);

                // @TODO dump.sqlを実行
                $dump_file = APP . 'config' . DS . 'sql' . DS . 'dump.sql';
                $db->query(file_get_contents($dump_file));

                $this->out("Install finished!");
                exit;
                break;
            }

            if (in_array($value, array_values($keys))) {
                $buf = array_keys($db_config);
                $key_name = $buf[$value];
                $db_config_value = $this->in("{$key_name}を入力してください。");
                $db_config[$key_name] = $db_config_value;
            }
        }
    }

    private function rewriteConfig($db_config)
    {
        $base_file = APP . 'config' . DS . 'database.php.install';
        $target_file = APP . 'config' . DS . 'database.php';

        if (!file_exists($base_file)) {
            $this->err("database.php.install not found.");
            exit;
        }

        $text = file_get_contents($base_file);
        $text = str_replace('{default_host}', $db_config['host'], $text);
        $text = str_replace('{default_login}', $db_config['login'], $text);
        $text = str_replace('{default_password}', $db_config['password'], $text);
        $text = str_replace('{default_database}', $db_config['database'], $text);
        $text = str_replace('{default_prefix}', $db_config['prefix'], $text);

        file_put_contents($target_file, $text);
    }

    /**
     * upgrade
     * 指定ユーザをadminに昇格
     */
    function upgrade()
    {
        $db = ConnectionManager::getDataSource('default');
        $value = $this->in("昇格したいユーザ名を入力してください。");
        $result = $db->query('SELECT * FROM users WHERE login = ?;', array($value), false);
        if (count($result) == 0) {
            $this->err('入力されたユーザは存在しません。');
            return;
        }

        if ($result[0]['users']['admin'] === '1') {
            $this->err('入力されたユーザはすでにadminです。');
            return;
        }

        $db->query('UPDATE users SET admin = 1 WHERE login = ?', array($value));
        $this->out("ユーザの昇格に成功しました。");
    }
}
