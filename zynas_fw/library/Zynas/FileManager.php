<?php

class Zynas_FileManager {

    /**
     * ファイル移動
     * @param string $fileName ファイル名
     * @param string $fromPath 移動元パス
     * @param string $toPath 移動先パス
     * @param string $rename 変更ファイル名
     */
    public static function move($fileName, $fromPath, $toPath, $rename = null) {
        self::createDirectory($toPath);
        $rename = is_null($rename) ? $fileName : $rename;
        if (!rename(pjoin($fromPath, $fileName), pjoin($toPath, $rename))) {
           Zynas_Logger::errorLog('ファイルの移動に失敗しました。（fileName=' . $fileName . ', fromPath=' . $fromPath . ', toPath=' . $toPath . ', rename=' . $rename . ')');
           throw new Zynas_Exception('ファイルの移動に失敗しました。サーバ管理者に問い合わせてください。');
        }
    }

    /**
     * ディレクトリ作成処理
     * @param $dirPath ディレクトリパス
     */
    protected static function createDirectory($dirPath) {
        if (!file_exists($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                Zynas_Logger::errorLog('ディレクトリの作成に失敗しました。（path=' . $dirPath . ')');
                throw new Zynas_Exception('ディレクトリの作成に失敗しました。サーバ管理者に問い合わせてください。');
            }
        }
    }
}
