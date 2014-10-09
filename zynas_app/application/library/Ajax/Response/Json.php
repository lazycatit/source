<?php

class Ajax_Response_Json extends Ajax_Response_Abstract {
    private $_response = array();

    public static function create($status, $data = null) {
        $arj = new self();
        $arj->setStatus($status);
        if (!is_null($data)) {
            if ($arj->getStatus()) {
                $arj->setItems($data);
            }
            else {
                $arj->setMessage($data);
            }
        }
        return $arj->send();
    }

    public function setStatus($status) {
        $this->_response['status'] = (bool) $status;
    }

    public function getStatus() {
        if (!array_key_exists('status', $this->_response)) $this->_response['status'] = false;
        return $this->_response['status'];
    }

    public function setMessage($message) {
        $this->_response['message'] = is_array($message) ? implode("\n", $message) : $message;
    }

    public function getMessage() {
        if (!array_key_exists('message', $this->_response)) $this->_response['message'] = '';
        return $this->_response['message'];
    }

    public function appendItem($item) {
        array_push($this->getItems, $item);
        $this->_updateItemCount();
    }

    public function appendItems($items) {
        foreach ($items as $i) $this->appendItem($i);
    }

    public function setItems($items) {
        $this->_response['items'] = $items;
        $this->_updateItemCount();
    }

    public function getItems() {
        if (!array_key_exists('items', $this->_response)) $this->_response['items'] = array();
        return $this->_response['items'];
    }

    public function render() {
        return Zend_Json::encode($this->_response);
    }

    public function send() {
        // !!! side: Firefoxではapplication/jsonコンテントタイプが認識されないようなので。
        //header('Content-Type: application/json; charset=UTF-8');
        echo $this->render();
        die();
    }

    private function _updateItemCount() {
        $this->_response['count'] = count($this->getItems());
    }
}

?>