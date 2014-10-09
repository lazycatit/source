<?php
/**
 * メール送信のコントローラークラス
 * <pre>
 * パラメータをキーにapplication.iniの設定値を読み、メールを送信します。
 * </pre>
 * @author hirakawa
 * @package /modules/default/controller
 */

class Mail {

    private $_configServer = null;
    private $_configMail = null;
    private $_transport = null;
    private $_view = null;

    private $_subject;
    private $_body;

    /**
     * ※誤送信の可能性があるため、インスタンスは保持しません
     * @param string $action
     * @param Zend_View_Interface
     * @return Mail
     */
    public static function getInstance($configKey, $view = null) {
        try {
            if (Zynas_String::isEmpty($configKey)) {
                throw new Zynas_Exception('Invalid argument.');
            }

            $thisObj = new self();
            $mailServer = isset(Zynas_Registry::getConfig()->system->mail->setting) ? Zynas_Registry::getConfig()->system->mail->setting->toArray() : null;
            if (!is_null($mailServer) && is_array($mailServer) && isset($mailServer['host']) ) {
                $thisObj->_configServer = $mailServer;
                $thisObj->_transport = new Zend_Mail_Transport_Smtp($mailServer['host'], $mailServer);
            } else {
                throw new Zynas_Exception('Invalid mail settings.');
            }

            $mailConfig = isset(Zynas_Registry::getConfig()->system->mail->{$configKey}) ? Zynas_Registry::getConfig()->system->mail->{$configKey}->toArray() : null;
            if (!is_null($mailConfig) && is_array($mailConfig) && isset($mailConfig['from']) && !Zynas_String::isEmpty($mailConfig['from'])) {
                $thisObj->_configMail = $mailConfig;
            } else {
                throw new Zynas_Exception('Invalid mail configs.');
            }

            $thisObj->_view = $view;
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
        return $thisObj;
    }

    /**
     * メール送信実行のアクション
     * <pre>
     * </pre>
     */
    public function send($to, $toName, $subject = null, $body = null,$replace = null) {
        try {
            $this->_subject = Zynas_String::isEmpty($subject) ? $this->_configMail['subject'] : $subject;
            $this->_body = Zynas_String::isEmpty($body) ? $this->_view->render($this->_configMail['template']) : $body;

            if(!is_null($replace) && is_array($replace)) {
                $this->_body = str_replace($replace['name'], $replace['value'], $this->_body);
            }

            $this->_configMail['to'] = $to;
            $this->_configMail['to_name'] = $toName;

            $this->doSend();
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }

    }
    
    public function sendFromName($to, $toName, $subject = null, $body = null,$replace = null, $from=null, $fromName=null) {
        try {
            $this->_subject = Zynas_String::isEmpty($subject) ? $this->_configMail['subject'] : $subject;
            $this->_body = Zynas_String::isEmpty($body) ? $this->_view->render($this->_configMail['template']) : $body;
    
            if(!is_null($replace) && is_array($replace)) {
                $this->_body = str_replace($replace['name'], $replace['value'], $this->_body);
            }
    
            $this->_configMail['to'] = $to;
            $this->_configMail['to_name'] = $toName;
    
            $this->doSendFromName($from, $fromName);
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
    
    }
    
    /**
     * メール送信実行のアクション
     * <pre>
     * </pre>
     */
    public function sendCc($to, $toName, $cc = array(), $subject = null, $body = null, $replace = null) {
        try {
            $this->_subject = Zynas_String::isEmpty($subject) ? $this->_configMail['subject'] : $subject;
            $this->_body = Zynas_String::isEmpty($body) ? $this->_view->render($this->_configMail['template']) : $body;
    
            if(!is_null($replace) && is_array($replace)) {
                $this->_body = str_replace($replace['name'], $replace['value'], $this->_body);
            }
    
            $this->_configMail['to'] = $to;
            $this->_configMail['to_name'] = $toName;
            $this->doSendCc($cc);           
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
    
    }
    
    

    /**
     * メール送信実行(複数送信者)のアクション
     * <pre>
     * </pre>
     */
    public function sendList($toList, $subject = null, $body = null,$replace = null) {
        try {
            foreach ($toList as $to => $toName) {
                $this->send($to, $toName, $subject, $body,$replace);
            }
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
    }


    private function doSend(){
        try {
            $mail = new Zynas_Mail();
            $mail->setFrom($this->_configMail['from'], $this->_configMail['from_name']);
            $mail->setReplyTo($this->_configMail['reply_to'], $this->_configMail['reply_to_name']);
            $mail->addTo($this->_configMail['to'], isset($this->_configMail['to_name']) ? $this->_configMail['to_name'] : '');
            $mail->addHeader('Sender', $this->_configMail['from']);
            $mail->setSubject($this->_subject);
            $mail->setBodyText($this->_body);
            $mail->send($this->_transport);
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }

    }
    
    private function doSendFromName($from, $fromName){
        try {
            $mail = new Zynas_Mail();
            $mail->setFrom($from, $fromName);
            $mail->setReplyTo($from, $fromName);
            $mail->addTo($this->_configMail['to'], isset($this->_configMail['to_name']) ? $this->_configMail['to_name'] : '');
            $mail->addHeader('Sender', $this->_configMail['from']);
            $mail->setSubject($this->_subject);
            $mail->setBodyText($this->_body);
            $mail->send($this->_transport);
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
    
    }
    
    private function doSendCc($cc = array()){
        try {
            $mail = new Zynas_Mail();
            $mail->setFrom($this->_configMail['from'], $this->_configMail['from_name']);
            $mail->setReplyTo($this->_configMail['reply_to'], $this->_configMail['reply_to_name']);
            $mail->addTo($this->_configMail['to'], isset($this->_configMail['to_name']) ? $this->_configMail['to_name'] : '');
            foreach($cc as $c) {
                $mail->addCc($c);
            }
            $mail->addHeader('Sender', $this->_configMail['from']);
            $mail->setSubject($this->_subject);
            $mail->setBodyText($this->_body);
            $mail->send($this->_transport);
        } catch (Exception $e) {
            Log::errorLog($e);
            throw $e;
        }
    
    }
}

?>