<?php
/**
 * ユーザー一覧画面のコントローラークラス
 * <pre>
 * </pre>
 * @author kaketari
 * @package /modules/default/controller
 */

class UserController extends Controller {

    const SESSION_KEY_SEARCH = 'search';
    const SESSION_KEY_PAGE = 'page';
    const SESSION_KEY_RETURN_DETAIL = 'return';

    /**
     * 初期表示のアクション
     * <pre>
     * 1)一覧表示アクションにフォワードする
     * </pre>
     */
    public function indexAction() {        
        $this->_forward('/list');
    }

    /**
     * 一覧表示処理
     * <pre>
     * 1)初期表示用データの取得
     *   検索条件からデータを取得
     * 2)1)で取得したデータをViewに返す。
     * </pre>
     */
    public function listAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        $this->session->removeData(self::SESSION_KEY_SEARCH);
        $this->session->removeData(self::SESSION_KEY_PAGE);
        $this->session->removeData(self::SESSION_KEY_RETURN_DETAIL);
        $page = null;
        if ($this->getRequest()->isPost()) {
            $where = $this->_input->getEscaped();

        } else {
            $where = $this->session->getData(self::SESSION_KEY_SEARCH);
            if (is_null($where)) {
                $where = array();
            }
            $page = isset($this->_input->page) ? $this->_input->page : $this->session->getData(self::SESSION_KEY_PAGE);
        }
        $this->getRequest()->setParams($where);
        $this->session->setModuleScope(self::SESSION_KEY_SEARCH, $where);
        $this->session->setModuleScope(self::SESSION_KEY_PAGE, $page);

        $select = MUsers::getInstance()->getListSelect($where);
        $this->view->paginator = Zynas_Paginator::factoryWithOptions($select, $page, $this->view);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * 登録画面の初期表示処理
     * <pre>
     * </pre>
     */
    public function addAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        $this->view->units = Zynas_Array_Util::extract(MUnits::getInstance()->getEntries()->toArray(), 'unit_name','id');
        $this->view->token = Csrf::getToken();
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * 登録確認画面表示処理
     * <pre>
     * </pre>
     */
    public function confirmAddAction() {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        $this->view->units = Zynas_Array_Util::extract(MUnits::getInstance()->getEntries()->toArray(), 'unit_name','id');
        $this->view->token = Csrf::getToken();
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * ユーザー登録処理のアクション
     * <pre>
     * 1)画面の入力値を元に、テーブルにレコードを作成する。
     * </pre>
     */
    public function doAddedAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $now = Zynas_Date::dbDatetime();
            $row = Musers::getInstance()->createRow();
            $row = $this->setUserData($row);
            $row->save();
            FlashMessenger::addSuccess('ID:'.$row->id . 'を登録しました。');
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        
        $this->_redirect('/user/list');
    }

    private function setUserData($row){
        $row->mail_adress = $this->_input->mail_adress;
        $row->password = $this->_input->password;
        $row->unit_id = $this->_input->unit_id;
        $row->first_name = $this->_input->first_name;
        $row->last_name = $this->_input->last_name;
        $row->first_kana = $this->_input->first_kana;
        $row->last_kana = $this->_input->last_kana;
        $row->gender = $this->_input->gender;
        $row->birth = $this->_input->birth;

        return $row;
    }

    /**
     * 変更画面の初期表示のアクション
     * <pre>
     * 1)Getのみ、以降を実施する。
     * 2)リクエストより渡されたIDを元に、ユーザーテーブルよりデータを取得する。
     * 3)2)で取得したデータを画面へ設定する。
     * </pre>
     */
    public function editAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        $this->view->units = Zynas_Array_Util::extract(MUnits::getInstance()->getEntries()->toArray(), 'unit_name','id');
        $this->view->token = Csrf::getToken();

        if ($this->getRequest()->isGet()){
            $id = $this->_input->id;
            $where['id'] = $id;

            $row = MUsers::getInstance()->getEntryById($id);
            $this->getRequest()->setParams($row->toArray());
        }
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
    }

    /**
     * ユーザー変更確認画面のアクション
     * <pre>
     * 1)変更画面で入力された内容を表示する
     * </pre>
     */
    public function confirmEditAction() {
        $this->view->token = Csrf::getToken();
    }

    /**
     * ユーザー変更処理のアクション
     * <pre>
     * 1)画面の入力値を元に、テーブル内のレコードを更新する。
     * </pre>
     */
    public function doEditAction(){
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        //変更画面から入力内容を取得する
        $id = $this->_input->id;

        //対象レコードを取得
        $userRow = MUsers::getInstance()->getEntryById($id);

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {
            $now = Zynas_Date::dbDatetime();
            $userRow = $this->setUserData($userRow);
            $userRow->update_date = $now;
            $userRow->save();
            $db->commit();
            FlashMessenger::addSuccess('ID:' .  $id . 'を更新しました。');
        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        
        return $this->_redirect('/user/list');
    }

    /**
     * 項目の「削除」アクション
     * <pre>
     * 1)POSTデータでない場合何もしない
     * 1-1)リクエストパラメータからIDを取得
     * </pre>
     */
    public function deleteAction() {
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';Start action');
        
        if (!$this->getRequest()->isPost()){
            FlashMessenger::addError(E062V);
            Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
            $this->_redirect('/user/list');
        }

        $id = $this->_input->id;
        $where['id'] = $id;

        $row = MUsers::getInstance()->getEntryById($id);

        if (is_Null($row)) {
            // error
            handleErrorDelete();
        }

        $db = Zynas_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        $now = Zynas_Date::dbDatetime();
        try {
            $row->delete_flg = MUsers::DELETE_FLG_ON;
            $row->delete_date = $now;
            $row->delete_flg_update_user = $id;
            $row->save();
            $db->commit();
            FlashMessenger::addSuccess('ID:' . $row->id . ',' . 'を削除しました。');

        } catch (Exception $e) {
            $db->rollBack();
            FlashMessenger::addError($e->getMessage());
        }
        
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number'.';End action');
        
        $this->_redirect('/user/list');
    }

    /**
     *ID取得失敗時の表示処理
     * <pre>
     * </pre>
     */
    public function handleErrorDelete() {
        FlashMessenger::addError(E075V);
        $this->_redirect('/user/list');
    }

    public function handleErrorEdit() {
        FlashMessenger::addError(E076V);
        $this->_redirect('/user/list');
    }
}
?>