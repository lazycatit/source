<?php

class DiscoAutoIssueController extends Controller
{

    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';

    public function indexAction()
    {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $this->session->removeData(self::SESSION_KEY_SEARCH);
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        $this->_forward('/autoissue');
    }

    public function autoissueAction()
    {

    }

    public function completeAction()
    {

    }

}

?>