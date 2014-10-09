<?php

class View_Helper_Partial extends Zend_View_Helper_Partial {

    // side:
    // Zend_View_Helper_Partialはpublicなviewプロパティへの直接的な参照がある
    // ので、Zynas_View_Helperのようにprivateな_viewプロパティではなく、publicな
    // viewプロパティにViewをアサインするようにしておきます。
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    public function partial($name = null, $module = null, $model = null) {
        if (is_null($module) || is_array($module)) {
            $module = array_merge(array('request' => $this->view->request,
                                        'config' => $this->view->config,
                                        'user' => (($this->view->request->getModuleName() == 'default' || $this->view->request->getModuleName() == 'mobile') ? $this->view->user : null),
                                        'adminUser' => ($this->view->request->getModuleName() == 'admin' ? $this->view->adminUser : null)
                                        ), (array) $module);
        }
        return parent::partial($name, $module, $model);
    }
}

?>