<?php

class Controller_Plugin_Default extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup() {
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');

        if ($this->getRequest()->getServer('HTTP_HOST') != Zynas_Registry::getConfig()->system->fqdn) {
            return $redirector->gotoUrl('http://' . Zynas_Registry::getConfig()->system->fqdn . '/error');
        }

        $mainteinFile = MAINTENANCE_PATH . Zynas_Registry::getConfig()->system->maintenance->fileName;
        if (file_exists($mainteinFile)){
            if ($this->isExclude(array('default/maintain/index'))) {
                $redirector->gotoUrl('/maintain/index');
            }
        }

        if (!Auth_Info::isLoggedUser()) {
            $loginRequired = true;
            $exclude = array(
                'default/auth/login',
                'default/auth/confirm-login',
                'default/recover/request',
                'default/recover/complete',
                'default/recover/confirm-request',
            	'default/maintain/index'
            );
            if ($this->isExclude($exclude)) {
                $redirector->gotoUrl('/auth/login');
            }
        }

        $configPath = pjoin(MODULE_PATH, 'default/configs/controllers') . '/' . $this->getRequest()->getControllerName() . '.ini';
        if(file_exists($configPath)) {
            $config = new Zend_Config_Ini($configPath);
            AX_Registry::setConfig(new Zend_Config($this->merge_recursive(AX_Registry::getConfig()->toArray(), $config->toArray())));
        }

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_partial/pagination.phtml');

    }

    private function isExclude($array){
        $requestResource = $this->getRequest()->getModuleName() . '/' . $this->getRequest()->getControllerName() . '/' . $this->getRequest()->getActionName();
        foreach ($array as $v) {
            if ($requestResource == $v) {
                return false;
            }
        }
        return true;
    }

    public function preDispatch() {
    }

    /**
     * 配列の値を再帰的に上書きする処理（Zend_Config用）
     * @param array $array1 元配列
     * @param array $array2
     * @return Ambigous <array, unknown>
     */
    private function merge_recursive(array $array1, array $array2) {
        $merge_arary = (array) $array1;
        foreach ($array2 as $key => $val) {
            if (!key_exists($key, $merge_arary)) {
                $merge_arary[$key] = $val;
            } else {
                $r_val = $merge_arary[$key];
                if (!is_array($r_val)) {
                    $merge_arary[$key] = $val;
                } else {
                    $merge_arary[$key] = $this->merge_recursive($r_val, $val);
                }
            }
        }
        return $merge_arary;
    }

}

?>