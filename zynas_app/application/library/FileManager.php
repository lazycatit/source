<?php

class FileManager {

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
            throw new Zynas_Exception('ファイルの移動に失敗しました。サーバ管理者に問い合わせてください。');
        }
    }

    /**
     * ディレクトリ作成処理
     * @param $dirPath ディレクトリパス
     */
    public static function createDirectory($dirPath) {
        if (!file_exists($dirPath)) {
            if (!mkdir($dirPath, 0777, true)) {
                throw new Zynas_Exception('ディレクトリの作成に失敗しました。サーバ管理者に問い合わせてください。');
            }
        }
    }

    public static function removeDirectory($dirPath) {
        if (file_exists($dirPath)) {
            if (!rmdir($dirPath)) {
                throw new Zynas_Exception('Fail to remove the folder。サーバ管理者に問い合わせてください。');
            }
        }
    }

    public static function copyFile($fileName, $fromPath, $toPath, $rename = null) {
        self::createDirectory($toPath);
        $rename = is_null($rename) ? $fileName : $rename;
        if (!copy($fromPath . $fileName, $toPath . $rename)) {
            throw new Zynas_Exception(' Can not copy from: ' . $fromPath . $fileName . ' to: ' . $toPath . $rename);
        }
    }

    public static function deleteDirAndFile($path) {
        return !empty($path) && is_file($path) ? @unlink($path) : (array_reduce(glob($path . '/*'), function($r, $i) {
            return $r && FileManager::deleteDirAndFile($i);
        }, TRUE)) && @rmdir($path);
    }

    public static function readfileChunked($filename, $retbytes = true) {
        $chunksize = 8 * 1024;
        // ~8k chunks
        $buffer = '';
        $cnt = 0;

        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }
        while (!feof($handle)) {
            $buffer = fread($handle, $chunksize);
            echo $buffer;
            ob_flush();
            flush();
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt;
        }
        return $status;
    }

}
