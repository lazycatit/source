<?php
/**
 * CSRF対策クラス
 * <pre>
 * CSRF対策としてトークンを発行し、チェックを行います
 * </pre>
 * @author hirakawa
 * @package /modules/default/controller
 */

class Csrf  {

    /**
     * CSRFトークン発行処理
     * <pre>
     * </pre>
     */
    public function GetToken() {
        $token = sha1(uniqid(mt_rand(), true));

        $_SESSION['token'][] = $token;

        return $token;
    }

    /**
     * CSRF対策用のトークンをチェック
     * <pre>
     * </pre>
     */
    public function checkToken($token) {
        $key = array_search($token, $_SESSION['token']);

        if ($key !== false) {
            unset($_SESSION['token'][$key]);
            return true;
        } else {
            return false;
        }
    }
}

?>