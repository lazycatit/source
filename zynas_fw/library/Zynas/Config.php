<?php

class Zynas_Config extends Zend_Config {

    // !!! side:
    // Zend_Configのものをそのままコピーしてオーバーライドしています。
    // 再帰的にインスタンスを作成する場合、new self()ではそれがコールされたメソッド定義のあるクラスのインスタンスを作成してしまうため、
    // これをここに定義しておかないと、２階層以降にインスタンスが必要な場合、Zynas_ConfigでなくてZend_Configが作成されてしまうためです。
    // PHP5.3以降のLate static bindingにZend_Configが対応してくれればこの処理は消すことができます。
    // 尚、コピペだということを強調するため、コーディング規約無視でそのまま貼りつけています。
    public function __construct(array $array, $allowModifications = false)
    {
        $this->_allowModifications = (boolean) $allowModifications;
        $this->_loadedSection = null;
        $this->_index = 0;
        $this->_data = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->_data[$key] = new self($value, $this->_allowModifications);
            } else {
                $this->_data[$key] = $value;
            }
        }
        $this->_count = count($this->_data);
    }

    public function __set($name, $value)
    {
        if ($this->_allowModifications) {
            if (is_array($value)) {
                $this->_data[$name] = new self($value, true);
            } else {
                $this->_data[$name] = $value;
            }
            $this->_count = count($this->_data);
        } else {
            /** @see Zend_Config_Exception */
            require_once 'Zend/Config/Exception.php';
            throw new Zend_Config_Exception('Zend_Config is read only');
        }
    }

    public function merge(Zend_Config $merge)
    {
        foreach($merge as $key => $item) {
            if(array_key_exists($key, $this->_data)) {
                if($item instanceof Zynas_Config && $this->$key instanceof Zynas_Config) {
                    $this->$key = $this->$key->merge(new Zynas_Config($item->toArray(), !$this->readOnly()));
                } else {
                    $this->$key = $item;
                }
            } else {
                if($item instanceof Zynas_Config) {
                    $this->$key = new Zynas_Config($item->toArray(), !$this->readOnly());
                } else {
                    $this->$key = $item;
                }
            }
        }

        return $this;
    }

    // !!! side: Zend_Configのメソッドのオーバーライドここまで

    public static function loadFile($path, $allowModifications = false) {
        if (file_exists($path)) {
            return new Zynas_Config(require($path), $allowModifications);
        }
        else {
            throw new Zynas_Config_Exception('File not found: ' . $path);
        }
    }

}

?>