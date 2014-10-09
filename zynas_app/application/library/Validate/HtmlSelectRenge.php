<?php
/**
 * 選択範囲チェック
 * <pre>
 * 入力値が、引数で渡されたstartからendの間にあるかをチェックします。
 * また、入力値が設定されていない場合は、チェックを行いません。
 * </pre>
 * @param start 任意。値が未設定の場合0(ゼロ)を設定します。
 * @param end 必須
 * @author toshimitsu
 * @package application/library/Validate
 */
class Validate_HtmlSelectRenge extends Zend_Validate_Abstract
{
    const RANGE_OVER = 'outsideOfRange';

    protected $_messageTemplates = array(
    self::RANGE_OVER => "選択範囲外です。"
    );

    protected $_messageVariables = array(
        'start' => '_start',
    	'end' => '_end',
    );
    protected $_start;

    protected $_end;

    public function __construct($range = array())
    {
        if (!array_key_exists('start', $range)){
            $this->setStart(0);
        }else{
            $this->setStart($range['start']);
        }
        if (!array_key_exists('end', $range)) {
            require_once 'Zynas/Validate/Exception.php';
            throw new Zynas_Validate_Exception("Missing option end");
        }
        $this->setEnd($range['end']);

    }
    public function getStart()
    {
        return $this->_start;
    }
    public function setStart($start)
    {
        $this->_start = $start;
    }
    public function getEnd()
    {
        return $this->_end;
    }

    public function setEnd($end)
    {
        $this->_end = $end;
    }
    public function isValid($value)
    {
        if (is_null($value) || strcmp($value, '')===0) return true;

        if (($value < $this->_start) || ($value > $this->_end)) {
            $this->_error(self::RANGE_OVER);
            return false;
        }
        return true;
    }
}
