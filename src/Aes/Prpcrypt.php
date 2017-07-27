<?php
/**
 * Prpcrypt class
 *
 */

namespace WechatBundle\OvertrueWechat\Wxapp\Sns\Aes;

use WechatBundle\OvertrueWechat\Wxapp\Sns\Aes\ErrorCode;
use WechatBundle\OvertrueWechat\Wxapp\Sns\Aes\PKCS7Encoder;

class Prpcrypt
{
    public $key;

    function __construct($k)
    {
        $this->key = $k;
    }

    /**
     * 对密文进行解密，php7.1以下的版本使用
     *
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV 解密的初始向量
     * @return string 解密得到的明文
     */
    /*
    public function decrypt($aesCipher, $aesIV)
    {
        try{
            $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            mcrypt_generic_init($module, $this->key, $aesIV);
            //解密
            $decrypted = mdecrypt_generic($module, $aesCipher);
            mcrypt_generic_deinit($module);
            mcrypt_module_close($module);
        }catch(\Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }

        try{
            //去除补位字符
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
        }catch(\Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }
        return array(0, $result);
    }
    */

    /**
     * 对密文进行解密，php7.1以上的版本
     *
     * @param string $aesCipher 需要解密的密文
     * @param string $aesIV 解密的初始向量
     * @return string 解密得到的明文
     */
    public function decrypt($aesCipher, $aesIV)
    {
        try {
            $decrypted = openssl_decrypt($aesCipher, 'AES-128-CBC', $this->key, OPENSSL_ZERO_PADDING, $aesIV);
        } catch (Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }

        try {
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
        } catch (Exception $e) {
            return array(ErrorCode::$IllegalBuffer, null);
        }
        return array(0, $result);
    }

}
