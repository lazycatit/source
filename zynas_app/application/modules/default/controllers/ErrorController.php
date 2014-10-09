<?php

class ErrorController extends Controller {

    public function init(){
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $this->_helper->layout->setLayout('layout-simple');
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                $this->_helper->viewRenderer->setNoRender(true);
                return $this->getResponse()->appendBody($this->view->render('error/404.phtml'));
                break;
            default:
                break;
        }

        // application error
        if ($errors->exception instanceof Zynas_Exception_404) {
            // 404 error -- controller or action not found
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->message = 'Page not found';
            $this->_helper->viewRenderer->setNoRender(true);
            return $this->getResponse()->appendBody($this->view->render('error/404.phtml'));
            break;
        }

        // ログ出力
        Log::errorLog($errors->exception, $this->getRequest()->getParams());

        try{
            // session clear
            Zynas_SessionManager::destory();

        }catch (Exception $e){
        }

        if (APPLICATION_ENV !== 'production' && APPLICATION_ENV !== 'staging') {
            // デバッグ用
            echo '<h1>System Error</h1>';
            echo '<h2>DEBUG:</h2>';
            echo '<h3>Exception information:</h3>';
            echo '<p><b>Message:</b> ' . $errors->exception->getMessage() . '</p>';
            echo '<h3>Request Parameters:</h3>';
            d($errors->request->getParams());
            echo '<h3>Stack Trace (Latest 10):</h3>';
            $i = 0;
            foreach($errors->exception->getTrace() as $t) {
                d($t);
                $i++;
                if ($i >= 10) break;
            }
            $this->_helper->viewRenderer->setNoRender(true);
            return;
        }

        $this->getResponse()->setHttpResponseCode(500);
        $this->view->message = 'Application error';
        $this->view->request   = $errors->request;
        return $this->getResponse()->appendBody($this->view->render('error/system.phtml'));
    }

}