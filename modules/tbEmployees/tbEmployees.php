<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

include_once 'modules/Vtiger/CRMEntity.php';
vimport('modules.Emails.models.Mailer');
vimport('modules.Emails.mail');
vimport('include.utils.tbutils');

class tbEmployees extends Vtiger_CRMEntity {
    var $table_name = 'vtiger_tbemployees';
    var $table_index= 'tbemployeesid';
    
    /**
     * Mandatory table for supporting custom fields.
     */
    var $customFieldTable = Array('vtiger_tbemployeescf', 'tbemployeesid');
    
    /**
     * Mandatory for Saving, Include tables related to this module.
     */
    var $tab_name = Array('vtiger_crmentity', 'vtiger_tbemployees', 'vtiger_tbemployeescf');
    
    /**
     * Mandatory for Saving, Include tablename and tablekey columnname here.
     */
    var $tab_name_index = Array(
        'vtiger_crmentity' => 'crmid',
        'vtiger_tbemployees' => 'tbemployeesid',
        'vtiger_tbemployeescf'=>'tbemployeesid');
    
    /**
     * Mandatory for Listing (Related listview)
     */
    var $list_fields = Array (
        /* Format: Field Label => Array(tablename, columnname) */
        // tablename should not have prefix 'vtiger_'
        'LBL_TBEMPLOYEES_NAME' => Array('vtiger_tbemployees', 'nombre_tbemployees'),
        'LBL_TBEMPLOYEES_SURNAME' => Array('vtiger_tbemployees', 'apellidos_tbemployees'),
        //'LBL_TBEMPLOYEES_TLF' => Array('vtiger_tbemployees', 'tlf_tbemployees'),
        'LBL_TBEMPLOYEES_EMAIL' => Array('vtiger_tbemployees', 'email_tbemployees'),
        'LBL_TBEMPLOYEES_DNI' => Array('vtiger_tbemployees', 'dni_tbemployees'),
        'LBL_TBEMPLOYEES_COD_GESTORIA' => Array('vtiger_tbemployees', 'cod_gestoria_tbemployees'),
        'Assigned To' => Array('crmentity','smownerid')
    );
    var $list_fields_name = Array (
        /* Format: Field Label => fieldname */
        'LBL_TBEMPLOYEES_NAME' => 'nombre_tbemployees',
        'LBL_TBEMPLOYEES_SURNAME' => 'apellidos_tbemployees',
        //'LBL_TBEMPLOYEES_TLF' => 'tlf_tbemployees',
        'LBL_TBEMPLOYEES_EMAIL' => 'email_tbemployees',
        'LBL_TBEMPLOYEES_DNI' => 'dni_tbemployees',
        'LBL_TBEMPLOYEES_COD_GESTORIA' => 'cod_gestoria_tbemployees',
        'Assigned To' => 'assigned_user_id',
    );
    
    // Make the field link to detail view
    var $list_link_field = 'nombre_tbemployees';
    
    // For Popup listview and UI type support
    var $search_fields = Array(
        /* Format: Field Label => Array(tablename, columnname) */
        // tablename should not have prefix 'vtiger_'
        'LBL_TBEMPLOYEES_NAME' => Array('vtiger_tbemployees', 'nombre_tbemployees'),
        'LBL_TBEMPLOYEES_SURNAME' => Array('vtiger_tbemployees', 'apellidos_tbemployees'),
        //'LBL_TBEMPLOYEES_TLF' => Array('vtiger_tbemployees', 'tlf_tbemployees'),
        'LBL_TBEMPLOYEES_EMAIL' => Array('vtiger_tbemployees', 'email_tbemployees'),
        'LBL_TBEMPLOYEES_DNI' => Array('vtiger_tbemployees', 'dni_tbemployees'),
        'LBL_TBEMPLOYEES_COD_GESTORIA' => Array('vtiger_tbemployees', 'cod_gestoria_tbemployees'),
        'Assigned To' => Array('crmentity','smownerid')
    );
    var $search_fields_name = Array (
        /* Format: Field Label => fieldname */
        'LBL_TBEMPLOYEES_NAME' => 'nombre_tbemployees',
        'LBL_TBEMPLOYEES_SURNAME' => 'apellidos_tbemployees',
        //'LBL_TBEMPLOYEES_TLF' => 'tlf_tbemployees',
        'LBL_TBEMPLOYEES_EMAIL' => 'email_tbemployees',
        'LBL_TBEMPLOYEES_DNI' => 'dni_tbemployees',
        'LBL_TBEMPLOYEES_COD_GESTORIA' => 'cod_gestoria_tbemployees',
        'Assigned To' => 'assigned_user_id',
    );
    
    // For Popup window record selection
    var $popup_fields = Array ('nombre_tbemployees');
    
    // For Alphabetical search
    var $def_basicsearch_col = 'nombre_tbemployees';
    
    // Column value to use on detail view record text display
    var $def_detailview_recname = 'nombre_tbemployees';
    
    // Used when enabling/disabling the mandatory fields for the module.
    // Refers to vtiger_field.fieldname values.
    var $mandatory_fields = Array('nombre_tbemployees','assigned_user_id');
    
    var $default_order_by = 'nombre_tbemployees';
    var $default_sort_order='ASC';
    
    /**
     * Invoked when special actions are performed on the module.
     * @param String Module name
     * @param String Event Type
     */
    function vtlib_handler($moduleName, $eventType) {
        global $adb;
        if($eventType == 'module.postinstall') {
            // TODO Handle actions after this module is installed.
        } else if($eventType == 'module.disabled') {
            // TODO Handle actions before this module is being uninstalled.
        } else if($eventType == 'module.preuninstall') {
            // TODO Handle actions when this module is about to be deleted.
        } else if($eventType == 'module.preupdate') {
            // TODO Handle actions before this module is updated.
        } else if($eventType == 'module.postupdate') {
            // TODO Handle actions after this module is updated.
        }
    }
    
    
    function save_module($module) {
        $this->saveAttachment($this->id, $module);
        
    }
    
    function saveAttachment($id, $module) {
        global $log, $adb, $current_user;
        $log->debug("Entering into saveAttachment($id,$module) method.");
        
        $file_saved = false;
        
        if ($_FILES) {
            //This is to added to store the existing attachment id of the contact where we should delete this when we give new image
            foreach ($_FILES as $fileindex => $files) {
                if ($files['name'] != '' && $files['size'] > 0) {
                    $files['original_name'] = vtlib_purify($files['name']);
                    $file_saved = $this->uploadAndSaveFile($id, $module, $files);
                    
                    if ($file_saved > 0 && $this->id > 0)
                    {
                        $v_query = "UPDATE vtiger_tbemployees SET $fileindex = ? WHERE tbemployeesid = ?";
                        $adb->pquery($v_query, array($file_saved, $this->id));
                        
                        // creamos la nota para este documento
                        $current_id = $adb->getUniqueID("vtiger_crmentity");
                        //Add entry to crmentity
                        $sql1 = "INSERT INTO vtiger_crmentity (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $params1 = array($current_id, $current_user->id, $current_user->id, "Documents", "", $adb->formatDate(date('Y-m-h H:i:s'), true), $adb->formatDate(date('Y-m-h H:i:s'), true));
                        $adb->pquery($sql1, $params1);
                        
                        // aumentamos el contador del módulo
                        $v_query = "UPDATE vtiger_modentity_num SET cur_id = (cur_id + 1) WHERE semodule = 'Documents'";
                        $adb->query($v_query);
                        $v_query = "SELECT concat(prefix,cur_id) as num FROM vtiger_modentity_num WHERE semodule = 'Documents'";
                        $v_res = $adb->query($v_query);
                        $v_num = '';
                        if ($adb->num_rows($v_res) > 0)
                        {
                            $v_num = $adb->query_result($v_res, 0, 'num');
                        }
                        
                        //Add entry to notes
                        $sql2 = "INSERT INTO vtiger_notes (notesid,note_no,title,filename,notecontent,folderid,filetype,filelocationtype,filedownloadcount,filestatus,filesize,fileversion,tags) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
                        $params2 = array($current_id, $v_num, $files['original_name'], $files['name'], '', 1,$files['type'],'I',1,1,$files['size'],'','');
                        $adb->pquery($sql2, $params2);
                        
                        // Add entry to notescf
                        $sql3 = "INSERT INTO vtiger_notescf (notesid) VALUES (?)";
                        $params3 = array($current_id);
                        $adb->pquery($sql3, $params3);
                        
                        // creamos la relación con el Agreement
                        $sql4 = "INSERT INTO vtiger_senotesrel (crmid, notesid) VALUES (?,?)";
                        $params4 = array($id, $current_id);
                        $adb->pquery($sql4, $params4);
                        
                        // creamos la relación con el notes
                        $sql51 = "select * from vtiger_seattachmentsrel where crmid = ? AND attachmentsid = ?";
                        $v_res_ver = $adb->pquery($sql51, array($id, $file_saved));
                        if ($adb->num_rows($v_res_ver) === 0)
                        {
                            $sql5 = "INSERT INTO vtiger_seattachmentsrel (crmid, attachmentsid) VALUES (?,?)";
                            $params5 = array($id, $file_saved);
                            $adb->pquery($sql5, $params5);
                        }
                        
                        //echo "<br>--99,10--$fileindex";exit;
                    }
                }
            }
        }
        
        
        $log->debug("Exiting from saveAttachment($id,$module) method.");
    }
}
