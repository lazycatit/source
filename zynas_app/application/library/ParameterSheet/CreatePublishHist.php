<?php
class PublishHistCreator {

    /**
     * Create a new record of t_publish_hist table from a record of t_publish_info table
     * @param TPublishInfo $row
     */
    public static function createFromRow($row) {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$row->control_number.';Start action');

        return self::setData($row);

        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$row->control_number.';End action');
    }

    /**
     * Create a record of t_publish_hist table from control number and publist type
     * @param String $control_number
     * @param String $publish_type
     */
    public static function createFromKey($controlNumber = null, $publishType = null) {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$controlNumber.';Start action');

        if($controlNumber == NULL || $publishType == NULL) {
            $e = new Zynas_Exception();
            $e->setErrors(E066V);
            throw $e;
        }

        $row = TPublishInfos::getInstance()->getRow($controlNumber, $publishType);
        if(!$row) {
            $e = new Zynas_Exception();
            $e->setErrors(E067V);
            throw $e;
        }
                
        return self::setData($row);
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$controlNumber.';End action');
    }

    /**
     * Set data from a row of t_publish_info to a row of t_publish_hist
     * @param TPublishInfo $row
     */
    private static function setData($row) {
        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$row->control_number.';Start action');

        $publishHistRow = TPublishHists::getInstance()->createRow();
        $publishHistRow->control_number = $row->control_number;
        $publishHistRow->publish_type = $row->publish_type;
        $publishHistRow->export_person = $row->export_person;
        $publishHistRow->is_coustomer_format = $row->is_coustomer_format;
        $publishHistRow->customer_code = $row->customer_code;
        $publishHistRow->customer_name = $row->customer_name;
        $publishHistRow->status = $row->status;
        $publishHistRow->do_option = $row->do_option;
        $publishHistRow->do_coustomer_designation = $row->do_coustomer_designation;
        $publishHistRow->do_coustomer_format = $row->do_coustomer_format;
        $publishHistRow->do_base_paper = $row->do_base_paper;
        $publishHistRow->delete_flg = $row->delete_flg;
        $publishHistRow->delete_date = $row->delete_date;
        $publishHistRow->delete_flg_update_user = $row->delete_flg_update_user;
        $publishHistRow->create_date = $row->create_date;
        $publishHistRow->create_user = $row->create_user;
        $publishHistRow->update_date = $row->update_date;
        $publishHistRow->update_user = $row->update_user;

        $createUser = MUsers::getInstance()->getUserByUserId($row->create_user);
        $publishHistRow->user_type_name = MCategorys::getInstance()->getUserTypeName($createUser->user_type)->name1;
        $publishHistRow->create_user_name = $createUser->name_jp;

        return $publishHistRow;

        Log::infoLog('method='.__FUNCTION__.';user_id='.Auth_Info::getUser()->user_id.';control_number='.$row->control_number.';End action');
    }
}