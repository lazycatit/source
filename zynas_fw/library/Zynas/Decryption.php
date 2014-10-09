<?php
/**
 * 暗号化・復号クラス
 * <pre>
 * テキストデータを暗号化・復号処理をします。
 * </pre>
 * @author hirakawa
 * @package /modules/default/controller
 */

class Zynas_Decryption  {
    /**
     * 暗号化処理
     * <pre>
     * </pre>
     */
    public function encrypt($input, $key = 'dummy') {
        $td = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');

        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        $ivsize = mcrypt_enc_get_iv_size($td);
        $iv = substr(md5($key), 0, $ivsize);

        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $input);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return base64_encode($encrypted_data);
    }

    /**
    * 復号処理
    * <pre>
    * </pre>
    */
    public function decrypt($input, $key = 'dummy') {
        $td = mcrypt_module_open(MCRYPT_TRIPLEDES,'','cbc','');

        $ks = mcrypt_enc_get_key_size($td);
        $key = substr(md5($key), 0, $ks);

        $ivsize = mcrypt_enc_get_iv_size($td);
        $iv = substr(md5($key), 0, $ivsize);

        mcrypt_generic_init($td, $key, $iv);
        $decrypted_data = mdecrypt_generic($td, base64_decode($input));

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return rtrim($decrypted_data,"\0");
    }

}

?>