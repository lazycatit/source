<?php

//文字列操作クラス
class Zynas_String {

	/**
	* 値が空であるかどうかのチェックを行う
	*
	* @param Stirng $check_str チェックする文字列
	* @return Boolean 空の場合はTrue、空でない場合はFalse
	*/
	public static function isEmpty($check_str) {
		if (!isset($check_str) || is_null($check_str) || strcmp($check_str, "") === 0){
			return true;
		}
		return false;
	}

	/**
	 * 変数が空の場合、NULLに変換する
	 *
	 * @param Stirng $str チェックする文字列
	 * @return 空の場合はnull、空でない場合は$str
	 */
	public static function convertNull($str) {
		return self::isEmpty($str) ? null : $str;
	}

}

?>