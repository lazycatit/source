<?php

class View_Helper_DumpFlashMessages extends Zynas_View_Helper {

    public function dumpFlashMessages() {
        $messages = Zynas_FlashMessenger::dumpMessages();
        $buff = array();
        foreach ($messages as $class => $msgs) {
            if (count($msgs) > 0) {
                foreach ($msgs as $msg) {
                    // side: メッセージ内に故意にHTMLが使用されている場合があるのでエスケープしません。
                    $buff[] = '<div id="box_msg" class="' . $class . '">' . $msg . '</div>';
                }
            }
        }
        return implode('', $buff);
    }

}

?>