<?php

class Zynas_View_Mobile extends Zynas_View {
    public function render($name, $encode = true) {
        $buffer = parent::render($name);
        if ($encode) {
            $buffer = str_replace("\n", '', $buffer);
            $buffer = str_replace("\r", '', $buffer);
            $buffer = str_replace("\t", '', $buffer);
            $buffer = str_replace('action=""', 'action="'.$_SERVER['REQUEST_URI'].'"', $buffer);
            $buffer = mb_convert_kana($buffer, 'a', 'UTF-8');
            $buffer = mb_convert_kana($buffer, 'k', 'UTF-8');
            $buffer = mb_convert_encoding($buffer, 'SJIS', 'UTF-8');
        }
        return $buffer;
    }
}

?>