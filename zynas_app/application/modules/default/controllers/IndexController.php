<?php

class IndexController extends Controller {

    /**
     * トップページのコントローラークラス
     * <pre>
     * </pre>
     * @author foo
     * @package /modules/default/controller
     */

    /**
     * 初期化処理
     * <pre>
     * </pre>
     */
    public function init(){
        parent::init();
    }

    /**
     * インデックス
     * <pre>
     * </pre>
     */
    public function indexAction() {
        $cookie = new Zynas_CookieManager();
        echo $cookie->getData('userId');
        if ($cookie->getData('userId') !== 0)
        {
            return $this->_redirect('/top/list');
        } else {
            return $this->_redirect('/auth/login');
        }
    }

    /**
     *
     *
     * <pre>
     * </pre>
     */
    public function loginAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
    }
     
}
?>