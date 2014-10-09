<?php

class Controller_Plugin_SslRedirect extends Zend_Controller_Plugin_Abstract {

//画面毎で、https or httpを切り分ける処理。
//     public function dispatchLoopStartup() {
//         $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
//         if (Zynas_Registry::getConfig()->system->ssl->available) {
//             $sslPort = Zynas_Registry::getConfig()->system->ssl->port;
//             $appPort = $this->getRequest()->getServer('APPLICATION_PORT');
//             $controllerName = $this->getRequest()->getControllerName();
//             $sslActions = isset(Zynas_Registry::getConfig()->sslRequired->{$controllerName}) ? Zynas_Registry::getConfig()->sslRequired->{$controllerName}->toArray() : array();
//             $isSsl = in_array($this->getRequest()->getActionName(), $sslActions) ? true : false;
//             if ($isSsl && $appPort != $sslPort) {
//                 return $redirector->gotoUrl('https://' . Zynas_Registry::getConfig()->system->fqdn . $this->getRequest()->getServer('REQUEST_URI'));
//             }
//             else if (!$isSsl && $appPort == $sslPort) {
//                 return $redirector->gotoUrl('http://' . Zynas_Registry::getConfig()->system->fqdn . $this->getRequest()->getServer('REQUEST_URI'));
//             }
//         }
//     }

    public function dispatchLoopStartup() {
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        if (Zynas_Registry::getConfig()->system->ssl->available) {
            $sslPort = Zynas_Registry::getConfig()->system->ssl->port;
            $appPort = $this->getRequest()->getServer('APPLICATION_PORT');
            if ($appPort != $sslPort) {
                return $redirector->gotoUrl('https://' . Zynas_Registry::getConfig()->system->fqdn . $this->getRequest()->getServer('REQUEST_URI'));
            }
        }
    }

}

?>