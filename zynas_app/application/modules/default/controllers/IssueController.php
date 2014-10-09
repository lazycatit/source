<?php
/**
 * <pre>
 * </pre>
 * @author ThinhNh
 * @package /modules/default/controller
 */

class IssueController extends Controller {

    public function indexAction() {

    }

    public function requestAction(){
        $user = Auth_Info::getUser();
        Log::infoLog('method='.__FUNCTION__.';user_id='.$user->user_id.';control_number'.';Start action');
        if(strcmp($user->user_type, MUsers::DISCO_ADMIN) === 0) {
            $this->_redirect('/da-issue/request');
        } elseif(strcmp($user->user_type, MUsers::DISCO_USER) === 0){
            $this->_redirect('/du-issue/request');
        } elseif(strcmp($user->user_type, MUsers::END_USER) === 0){
            $this->_redirect('/eu-issue/request');
        }
        Log::infoLog('method='.__FUNCTION__.';user_id='.$user->user_id.';control_number'.';End action');
    }   
}
?>