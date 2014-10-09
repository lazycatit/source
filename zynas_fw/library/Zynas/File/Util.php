<?php

class Zynas_File_Util {

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
        if (!self::exists($path)) throw new Zynas_Exception('File does not exist: ' . $path);
        return readfile((string) $path);
    }

    public static function getContents($path) {
        if (!self::exists($path)) throw new Zynas_Exception('File does not exist: ' . $path);
        return file_get_contents((string) $path);
    }

    public static function explode($path) {
        return file((string) $path);
    }

    public static function putContents($path, $contents, $flags = 0, $lf = true) {
        $dirname = dirname($path);
        if (!is_dir($dirname)) throw new Zynas_Exception('Directory not found: ' . $dirname);
        if (!is_writable($dirname)) throw new Zynas_Exception('Directory not writable: ' . $dirname);
        if (file_exists($path) && !is_writable($path)) throw new Zynas_Exception('File not writable: ' . $path);
        if ($lf) $contents = preg_replace('/\r\n/', "\n", $contents);
        return file_put_contents((string) $path, $contents, $flags);
    }

    public static function createTemporaryUniqueName($inDir, $extension = '', $prefix = '') {
        $filename = (!empty($prefix) ? $prefix . '_' : '') . md5(microtime() . mt_rand(1, 9999)) . '.' . $extension;
        return (self::exists($inDir . $filename)) ? self::createTemporaryUniqueName($inDir, $extension, $prefix) : $filename;
    }

    public static function moveUploadedFilesUnique($destDir, $prefix = '', $ignoreEmpty = true, $validates = array(), $isExToLower = false) {
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
        if (!$adapter->receive()) {
            $e = new Exception_FileUpload();
            $e->setErrors($adapter->getMessages());
            throw $e;
        }
        return $newFileNames;
    }

}

?>
