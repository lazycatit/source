<?php
/**
 * 入力フォームエラーメッセージ出力（マトリクス用）
 */
class View_Helper_FormErrorsMatrix extends Zynas_View_Helper {
    public function formErrorsMatrix($key, $linesKey, $errors = array(), $tag = null) {
        if (is_null($tag)) $tag = '<div style="color:#B8860B;font-weight:bold;padding: 3px 0 0 0;">%s</div>';
        $string = null;
        if (isset($errors[$key][$linesKey])) {
            if (!is_array($errors[$key][$linesKey])) {
                $string = htmlspecialchars($errors[$key][$linesKey]);
            }
            else {
                $tmp = array();
                foreach ($errors[$key][$linesKey] as $error) {
                    $tmp[] = htmlspecialchars($error);
                }
                $string = implode('<br />', $tmp);
            }
        }
        return !empty($string) ? sprintf($tag, $string) : '';
    }
}
?>