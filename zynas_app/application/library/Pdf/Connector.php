<?php
/**
 * PDF作成処理を呼び出す。
 */
class Pdf_Connector {

    /** 同期メソッドのロック */
    private static $callSyncLocked = false;

    /**
     * 同期処理でPDF作成処理を呼び出す。
     * @param controlNumber : 管理番号
     * @param publishType : 発行方法
     * @return string : PDF file name
     * @thorw Pdf_Connector_Exception
     */
    public static function callSync($controlNumber, $publishType) {
        // 引数チェック
        // 処理中のフラグのチェック、処理中の場合は待つ
        // Windows側のRESTサービスに接続
        // 印刷リクエスト
        // 戻り値の判断
        // 処理中のフラグを下げる
        
        $pdf_file_name = $controlNumber."_parameter_sheet.pdf";
        return $pdf_file_name;
    }

    /**
     * 非同期処理でPDF作成処理を呼び出す。
     * @param controlNumber : 管理番号
     * @param publishType : 発行方法
     * @thorw Zynas_Pdf_Connector_Exception
     */
    public static function callAsync($controlNumber, $publishType) {
        // 引数チェック
        // Windows側のRESTサービスに接続
        // 印刷リクエスト
        // 戻り値の判断
    }
}

?>