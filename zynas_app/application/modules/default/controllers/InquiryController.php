<?php

/**
 * Class InquiryController
 * <pre>
 * </pre>
 * @author AnhNV
 * @package /modules/default/controller
 *
 */

class InquiryController extends Controller
{

    /**
     * Index action
     */
    public function indexAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_forward('/inquiry');
    }

    /**
     * Inquiry action
     */
    public function inquiryAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Form action
     */
    public function formAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Confirm form action
     *
     *
     */
    public function confirmFormAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        if ($this->_request->isPost('btSave'))
        {
            $data = array('email'         => $this->_input->email,
		                  'phone'         => $this->_input->phone,
		                  'department'    => $this->_input->department,
		                  'content'       => $this->_input->content
            );            
            $replace = array('name' => array('{$email}', '{$phone}', '{$department}', '{$content}'),
		                        'value' => array($data['email'], $data['phone'], $data['department'], $data['content']));
            $to = Zynas_Registry::getConfig()->system->mail->admin_to_email;
            $toName = Zynas_Registry::getConfig()->system->mail->admin_to_name;
            Mail::getInstance('contact', $this->view)->sendFromName($to, $toName, null, null, $replace, $data['email'], $data['department']);
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * Complete action
     * Display result when send inquiry completed
     */
    public function completeAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

}

?>
