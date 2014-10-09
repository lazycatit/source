<?php

class View_Helper_FormWebdav extends Zynas_View_Helper {

    public function formWebdav($value = null, $class = null, $path = '/') {
        $value = is_null($value) ? '一括ファイルアップロード' : $value;
        $class = is_null($class) ? 'httpFolder' : $class;
        $url = Zynas_Registry::getConfig()->system->webdav->url;
        $webdavPath = $url . $path;

        $html  = '<p style="font-size:1.2em">アップロードするディレクトリのパス：' . $path . '</p>';
        $html .= '<p class="notice">※ファイルアップロードはIEのみ可能です。</p>';
        $html .= '<input type="button" value="' . $value . '" class="' . $class .'" onclick="fnOpenFolderView(\'' . $webdavPath . '\');" />';
        $html .= '<br><span class="alert" style="font-size:1.3em">※ アップ済のディレクトリ・ファイルの上書きと削除はすぐに本番へ反映されますので注意してください。</span>';

        return $html;
    }

}
