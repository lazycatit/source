<?php

class TPublishInfo extends Zynas_Db_Table_Row
{
    protected $product = array();
    public function setProducts($product){
        $this->product = $product;
    }

    public function getProducts(){
        return $this->product;
    }
}

?>