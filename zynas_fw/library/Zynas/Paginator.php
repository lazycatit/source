<?php

class Zynas_Paginator extends Zend_Paginator {

    /**
     * Factory.
     * @param  Zend_Db_Table_Select $select
     * @param  array $options
     * @param  int $current
     * @param  Zend_View $view
     * @return Zend_Paginator
     */
    public static function factoryWithOptions(Zend_Db_Select $select, $current, Zend_View $view, array $options = array()) {
        $instance = parent::factory($select);
        $options = array_merge(Zynas_Registry::getConfig()->paginator->toArray(), $options);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($instance, $method)) call_user_func(array($instance, $method), $value);
        }
        if (isset($options['defaultScrollingStyle']) && !empty($options['defaultScrollingStyle'])) Zynas_Paginator::setDefaultScrollingStyle($options['defaultScrollingStyle']);
        if (isset($options['defaultViewPartial']) && !empty($options['defaultViewPartial'])) Zend_View_Helper_PaginationControl::setDefaultViewPartial($options['defaultViewPartial']);
        $instance->setCurrentPageNumber($current);
        $instance->setView($view);
        return $instance;
    }

    /**
     * Factory.
     * @param  Zend_Db_Table_Select $select
     * @return Zend_Paginator
     */
    public static function factoryForCsv(Zend_Db_Select $select) {
        $instance = parent::factory($select);
        $options = Zynas_Registry::getConfig()->paginator->toArray();
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($instance, $method)) call_user_func(array($instance, $method), $value);
        }
        return $instance;
    }

}

?>