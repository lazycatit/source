<?php

class RecoverController extends Controller{

    public function indexAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
        $this->_forward('/request');
    }

    public function confirmRequestAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
        $email = $this->_input->mailAddress;

        if(strcmp($email, '') !== 0) {
            $user = MUsers::getInstance()->getUserByUserId($email);
            if ($user) {
                $fqdn = Zynas_Registry::getConfig()->system->fqdn;
                $contact = Zynas_Registry::getConfig()->constants->contact_text;
                $password = $user->passwd;
                $replace = array('name' => array('{$passwd}', '{$fqdn}', '{$contact}'), 'value' => array($password, $fqdn, $contact));
                Mail::getInstance('recover_password', $this->view)->send($email, $user->name_jp, null, null, $replace);
                 
            }
            Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
            $this->_redirect('/recover/complete');
        } else {
            Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
            $this->_redirect('/recover/confirm-request');
        }
    }

    public function completeAction()
    {
         Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
         Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
    }

    public function requestAction()
    {
         Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';Start action');
         Log::infoLog('method='.__FUNCTION__.';user_id='.';control_number'.';End action');
    }
}

?>