<?php

class Auth_User extends Auth_Abstract implements Auth_Interface {

    /**
     * @var Auth_Abstruct
     */
    protected static $_instance = null;

    /**
     * @var User
     */
    private $User = null;

    /**
     * @return Auth_User
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        self::$_instance->_auth = Zend_Auth::getInstance();
        self::$_instance->_auth->setStorage(new Zend_Auth_Storage_Session('Auth', 'mUser'));
        return self::$_instance;
    }

    /**
     * 認証実施
     * @return boolean
     */
    public function authenticate($mailAdress, $password) {
        $adapter = new Zend_Auth_Adapter_DbTable(
        Zend_Db_Table::getDefaultAdapter(),
        MUsers::getInstance()->info('name'),
                 'user_id',
                 'passwd',
                 "? AND (delete_flg <> 'D' OR delete_flg IS NULL)");
        $adapter->setIdentity($mailAdress)
        ->setCredential($password);
        $result = $this->_auth->authenticate($adapter);

        if ($result->isValid()) {
            $obj = $adapter->getResultRowObject(array('id','user_id','name_en','name_jp','delete_flg'));
            $obj->time = time();
            $this->setData($obj);
        }
        return $result->isValid();
    }

    /**
     * ログインデータ取得
     * @return MUser
     */
    public function getUser() {
        if (is_null($this->User)) {
            if ($this->isLogged()) {
                $User = MUsers::getInstance()->getUserById($this->getData()->id);
                if ($User) $this->User = $User;
            }
        }
        return $this->User;
    }

    /**
     * ログイン時間取得
     * @return MUser
     */
    public function getLoginTime() {
        return $this->getData()->time;
    }


    public function clear() {
        parent::clear();
        $this->User = null;
    }

}