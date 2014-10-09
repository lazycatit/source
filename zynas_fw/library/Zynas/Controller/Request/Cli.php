<?php

class Zynas_Controller_Request_Cli {
    public static function create() {
        try {
            $opts = array(
                'controller|c=s' => 'コントローラ名です。',
                'action|a=s' => 'アクション名です。',
                'module|m=s' => 'モジュール名です。デフォルトは"cli"です。',
                'parameters|p=s' => 'パラメータ名です。e.g. foo=bar&hoge=eeha',
                'help|h' => 'このメッセージを表示します。'
            );
            $opts = new Zend_Console_Getopt($opts);
            $opts->parse();
        }
        catch (Zend_Console_Getopt_Exception $e) {
            echo $e->getUsageMessage();
            die();
        }
        if (isset($opts->h)) {
            echo $opts->getUsageMessage();
            die();
        }
        $controller = isset($opts->c) ? $opts->c : null;
        $action = isset($opts->a) ? $opts->a : null;
        $module = isset($opts->m) ? $opts->m : 'cli';
        $parameters = array();
        if (isset($opts->p)) parse_str($opts->p, $parameters);
        return new Zend_Controller_Request_Simple($action, $controller, $module, $parameters);
    }
}

?>
