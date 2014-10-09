<?php

class View_Helper_DumpMessages extends Zynas_View_Helper {
    public function dumpMessages() {
        $messages = App_FlashMessenger::dumpMessages();
        $buff = array();
        foreach ($messages as $class => $msgs) {
            if (count($msgs) > 0) {
                foreach ($msgs as $msg) {
                    // side: メッセージ内に故意にHTMLが使用されている場合があるのでエスケープしません。
                    $buff[] = '<div class="info ' . $class . '">' . $msg . '</div>';
                }
            }
        }
        return implode('', $buff);
    }
}

?>