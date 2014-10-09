<?php

class Zynas_PaginatorAdapter implements Zend_Paginator_Adapter_Interface {

    protected $_items;
    protected $_count;

    public function __construct($items, $count)
    {
        $this->_items = $items;
        $this->_count = clone $count;
    }

    public function getItems($offset, $itemsPerPage)
    {
        return $this->_items;
    }

    public function count()
    {
        return $this->_count;
    }
}

?>