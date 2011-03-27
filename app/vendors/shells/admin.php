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
            '1' => array('name' => 'Menu1','alt' => '指定ユーザをadminに昇格'),
            //'2' => array('name' => 'Menu2','alt' => 'メニュー2'),
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

            if ($value === '1') {
                $this->upgrade();
            }
        }
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
