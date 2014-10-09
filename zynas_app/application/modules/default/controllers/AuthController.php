<?php
/**
 * ログイン画面のコントローラークラス
 * <pre>
 * </pre>
 * @author nojiri
 * @package /modules/default/controller
 */

class AuthController extends Controller {

    const COOKIE_KEY_USER_ID = 'user_id';
    /**
     * 初期化処理
     * <pre>
     * </pre>
     */
    public function loginAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        if ($this->getRequest()->isGet()) {
            $cookie = new Zynas_CookieManager();
            if ($cookie->getData(self::COOKIE_KEY_USER_ID)) {
                $this->view->mailAdress = $cookie->getData(self::COOKIE_KEY_USER_ID);
            }        
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
    }

    /**
     * ログイン処理
     * <pre>
     * </pre>
     */
    public function confirmLoginAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        $auth = Auth_User::getInstance();        
        $cookie = new Zynas_CookieManager();
        $mailAdress = $this->_input->mailAdress;
        $password = $this->_input->password;
        $remember = $this->_input->remember;
        if ($auth->authenticate($mailAdress, $password)) {
            if ($remember == MUsers::REMEMBER) {
                $cookie->setData($mailAdress, self::COOKIE_KEY_USER_ID, '', 30, false);
            }
            Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
            return $this->_redirect('/top/list');
        } else {
            return $this->handleErrorDoLogin();
        }
    }

    /**
     * ログアウト処理
     * <pre>
     *
     * </pre>
     */
    public function logoutAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        Auth_User::getInstance()->clear();
        $cookie = new Zynas_CookieManager();
        Zynas_SessionManager::destory();
        $cookie->removeData(self::COOKIE_KEY_USER_ID);
        $this->_redirect('/auth/login?out=' . (isset($_GET['out']) ? $_GET['out'] : '1'));
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
    }

    public function handleErrorDoLogin() {
        $this->view->errors = array('login' => E068V);
        return $this->_forward('login');
    }   
}
?>