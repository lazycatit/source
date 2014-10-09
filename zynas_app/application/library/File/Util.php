<?php

class File_Util {

    public static function exists($path) {
        return file_exists((string) $path);
    }

    public static function delete($path) {
        return unlink((string) $path);
    }

    public static function copy($from, $to) {
        return copy((string) $from, (string) $to);
    }

    public static function move($from, $to) {
        return rename((string) $from, (string) $to);
    }

    public static function read($path) {
        if (!self::exists($path)) throw new Zynas_Exception('File is not exists: ' . $path);
        return readfile((string) $path);
    }

    public static function getContents($path) {
        if (!self::exists($path)) throw new Zynas_Exception('File is not exists: ' . $path);
        return file_get_contents((string) $path);
    }

    public static function explode($path) {
        return file((string) $path);
    }

    public static function mkdir($path, $mode = '775', $recursive = false) {
        return mkdir((string) $path, $mode, $recursive);
    }

    public static function putContents($path, $contents, $flags = 0, $lf = true) {
        $dirname = dirname($path);
        if (!is_dir($dirname)) throw new Zynas_Exception('Directory not found: ' . $dirname);
        if (!is_writable($dirname)) throw new Zynas_Exception('Directory not writable: ' . $dirname);
        if (file_exists($path) && !is_writable($path)) throw new Zynas_Exception('File not writable: ' . $path);
        if ($lf) $contents = preg_replace('/\r\n/', "\n", $contents);
        return file_put_contents((string) $path, $contents, $flags);
    }

    public static function setTemporaryFileName($key, $name) {
        $_FILES[$key]['name'] = $name;
    }

    public static function getUploadName($origName, $prefix, $uid, $dir = '/') {
        $filePath = $dir . '/' . $origName;
        $fileData = pathinfo($filePath);
        $name = sprintf('%s_%s_%s_%s.%s', $prefix, $fileData['filename'], $uid, mktime() . mt_rand(100, 999), $fileData['extension']);
        $filePath = $dir . '/' . $name;
        if (File_Util::exists($filePath)) {
            return false;
        }
        return $name;
    }

    public static function createTemporaryUniqueName($inDir, $extension = '', $prefix = '') {
        $filename = (!empty($prefix) ? $prefix . '-' : '') . md5(microtime() . mt_rand(1, 9999)) . (!empty($extension) ? '.' . $extension : '');
        return (self::exists($inDir . $filename)) ? self::createTemporaryUniqueName($inDir, $extension, $prefix) : $filename;
    }

    public static function moveUploadedFilesUnique($destDir, $prefix = '', $ignoreEmpty = true, $validates = array(), $isExToLower = false, $files = null) {
        if (empty($_FILES)) return array();
        $newFileNames = array();
        foreach ($_FILES as $k => $v) {
            if (!empty($v['name'])) {
                $ex = substr($v['name'], (strrpos($v['name'], '.') + 1));
                if ($isExToLower) $ex = strtolower($ex);
                $_FILES[$k]['name'] = self::createTemporaryUniqueName($destDir, $ex, $prefix);
                $newFileNames[$k] = $_FILES[$k]['name'];
            }
        }
        $adapter = new Zynas_File_Transfer_Adapter_Http();
        $adapter->setDestination($destDir);
        $adapter->setIgnoreNoFileOption($ignoreEmpty);
        $adapter->clearValidators();
        $adapter->addValidator('Upload');

        if ($validates instanceof Zynas_Config) $validates = $validates->toArray();
        foreach ($validates as $v) {
            call_user_func_array(array($adapter, 'addValidator'), (array) $v);
        }
        if (!$adapter->receive($files)) {
            $e = new Zynas_Exception();
            $e->setErrors($adapter->getMessages());
            throw $e;
        }
        return $newFileNames;
    }

    public static function removeDirectory($destDir) {
        if($handle = opendir($destDir)) {
            while( false != ($item = readdir($handle))) {
                if($item != "." && $item != "..") {
                    if(is_dir($destDir . '/'. $item)) {
                        removeDirectory($destDir . '/'. $item);
                    }
                    else {
                        chmod($destDir . '/'. $item, 0777);
                        unlink($destDir . '/'. $item);
                    }
                }
            }
            closedir($handle);
            return rmdir($destDir);
        }
        return false;
    }

    public static function fgetCsvReg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++){
            $_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
            $_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : array_map('trim', $_csv_data);
    }
}

?>