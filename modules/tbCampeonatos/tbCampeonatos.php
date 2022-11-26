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

class tbCampeonatos extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_tbcampeonatos';
	var $table_index= 'tbcampeonatosid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_tbcampeonatoscf', 'tbcampeonatosid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_tbcampeonatos', 'vtiger_tbcampeonatoscf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_tbcampeonatos' => 'tbcampeonatosid',
		'vtiger_tbcampeonatoscf'=>'tbcampeonatosid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
	    // tablename should not have prefix 'vtiger_'
	    'LBL_TBCAMPEONATOS_NAME' => Array('tbcampeonatos', 'name'),
	    'LBL_TBCAMPEONATOS_LUGAR' => Array('tbcampeonatos', 'lugar'),
	    'LBL_TBCAMPEONATOS_FECHA_CAMPEONATO' => Array('tbcampeonatos', 'fecha_campeonato'),
	    'LBL_TBCAMPEONATOS_FECHA_FIN_INSCRIPCION' => Array('tbcampeonatos', 'fecha_fin_inscripciones'),
	    'LBL_TBCAMPEONATOS_DISCIPLINAS' => Array('tbcampeonatos', 'disciplinas'),
	    'LBL_TBCAMPEONATOS_GRADOS' => Array('tbcampeonatos', 'grados'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
	    /* Format: Field Label => fieldname */
	    'LBL_TBCAMPEONATOS_NAME' => 'name',
	    'LBL_TBCAMPEONATOS_LUGAR' => 'lugar',
	    'LBL_TBCAMPEONATOS_FECHA_CAMPEONATO' => 'fecha_campeonato',
	    'LBL_TBCAMPEONATOS_FECHA_FIN_INSCRIPCION' => 'fecha_fin_inscripciones',
	    'LBL_TBCAMPEONATOS_DISCIPLINAS' => 'disciplinas',
	    'LBL_TBCAMPEONATOS_GRADOS' => 'grados',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'name';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
	    // tablename should not have prefix 'vtiger_'
	    'LBL_TBCAMPEONATOS_NAME' => Array('tbcampeonatos', 'name'),
	    'LBL_TBCAMPEONATOS_LUGAR' => Array('tbcampeonatos', 'lugar'),
	    'LBL_TBCAMPEONATOS_FECHA_CAMPEONATO' => Array('tbcampeonatos', 'fecha_campeonato'),
	    'LBL_TBCAMPEONATOS_FECHA_FIN_INSCRIPCION' => Array('tbcampeonatos', 'fecha_fin_inscripciones'),
	    'LBL_TBCAMPEONATOS_DISCIPLINAS' => Array('tbcampeonatos', 'disciplinas'),
	    'LBL_TBCAMPEONATOS_GRADOS' => Array('tbcampeonatos', 'grados'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
	    /* Format: Field Label => fieldname */
	    'LBL_TBCAMPEONATOS_NAME' => 'name',
	    'LBL_TBCAMPEONATOS_LUGAR' => 'lugar',
	    'LBL_TBCAMPEONATOS_FECHA_CAMPEONATO' => 'fecha_campeonato',
	    'LBL_TBCAMPEONATOS_FECHA_FIN_INSCRIPCION' => 'fecha_fin_inscripciones',
	    'LBL_TBCAMPEONATOS_DISCIPLINAS' => 'disciplinas',
	    'LBL_TBCAMPEONATOS_GRADOS' => 'grados',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('name');

	// For Alphabetical search
	var $def_basicsearch_col = 'name';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'name';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('name','assigned_user_id');

	var $default_order_by = 'name';
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
 	
 	function save_module($module)
 	{
 	    global $log,$adb,$upload_badext;
 	    
 	    $query = 'SELECT * from vtiger_tbcampeonatos where tbcampeonatosid = ?';
 	    $res = $adb->pquery($query,array($this->id));
 	    $num_licencia='';
 	    $num_campeonato='';
 	    if(isset($res)){
 	        $rowCount = $adb->num_rows($res);
 	        if($rowCount > 0){
 	            $num_campeonato=$adb->query_result($res,0,'tbcampeonatosno');
 	        }
 	    }
 	    $nombrefichero='cartel-'.$num_campeonato;
 	    
 	    if(count($_FILES)) {
 	        
 	        $filetype_fieldname = $this->getFileTypeFieldName();
 	        $filename_fieldname = $this->getFile_FieldName();
 	        
 	        if($_FILES[$filename_fieldname]['name'] != ''){
 	            $errCode=$_FILES[$filename_fieldname]['error'];
 	            if($errCode == 0){
 	                
 	                foreach($_FILES as $fileindex => $files)
 	                {
 	                    if($files['name'] != '' && $files['size'] > 0){
 	                        
 	                        $filename = $_FILES[$filename_fieldname]['name'];
 	                        $extension = pathinfo($filename, PATHINFO_EXTENSION);
 	                        $filename = $nombrefichero;
 	                        if ($extension<>'') $filename.='.'.$extension;
 	                        
 	                        $filename = from_html(preg_replace('/\s+/', '_', $filename));
 	                        $filetype = $_FILES[$filename_fieldname]['type'];
 	                        $filesize = $_FILES[$filename_fieldname]['size'];
 	                        $binFile = sanitizeUploadFileName($filename, $upload_badext);
 	                        $filename = ltrim(basename(" ".$binFile)); //allowed filename like UTF-8 characters
 	                    }
 	                }
 	                
 	                $query = "delete from vtiger_seattachmentsrel where crmid = ?";
 	                $qparams = array($this->id);
 	                $adb->pquery($query, $qparams);
 	                
 	                $query = "UPDATE vtiger_tbcampeonatos SET filename = ? WHERE tbcampeonatosid = ?";
 	                $re=$adb->pquery($query,array(decode_html($filename),$this->id));
 	                //Inserting into attachments table
 	                $this->insertIntoAttachment($this->id,'tbCampeonatos',$filename);
 	                
 	                //set the column_fields so that its available in the event handlers
 	                $this->column_fields['filename'] = $filename;
 	                
 	            }
 	        }
 	        
 	    }
 	    
 	    
 	}
 	
 	
 	/**
 	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
 	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
 	 *      @param string $module  - the current module name
 	 */
 	function insertIntoAttachment($id,$module,$filename)
 	{
 	    global $log, $adb;
 	    $log->debug("Entering into insertIntoAttachment($id,$module) method.");
 	    
 	    $file_saved = false;
 	    
 	    foreach($_FILES as $fileindex => $files)
 	    {
 	        if($files['name'] != '' && $files['size'] > 0)
 	        {
 	            $files['name']=$filename;
 	            $files['original_name'] = null;
 	            $file_saved = $this->uploadAndSaveFile($id,$module,$files);
 	        }
 	    }
 	    
 	    $log->debug("Exiting from insertIntoAttachment($id,$module) method.");
 	}
 	
 	// Function to get fieldname for uitype 27 assuming that documents have only one file type field
 	
 	function getFileTypeFieldName(){
 	    global $adb,$log;
 	    $query = 'SELECT fieldname from vtiger_field where tabid = ? and uitype = ?';
 	    $tabid = getTabid('tbCampeonatos');
 	    $filetype_uitype = 27;
 	    $res = $adb->pquery($query,array($tabid,$filetype_uitype));
 	    $fieldname = null;
 	    if(isset($res)){
 	        $rowCount = $adb->num_rows($res);
 	        if($rowCount > 0){
 	            $fieldname = $adb->query_result($res,0,'fieldname');
 	        }
 	    }
 	    return $fieldname;
 	    
 	}
 	
 	//	Function to get fieldname for uitype 28 assuming that doc has only one file upload type
 	
 	function getFile_FieldName(){
 	    global $adb,$log;
 	    $query = 'SELECT fieldname from vtiger_field where tabid = ? and uitype = ?';
 	    $tabid = getTabid('tbCampeonatos');
 	    $filename_uitype = 28;
 	    $res = $adb->pquery($query,array($tabid,$filename_uitype));
 	    $fieldname = null;
 	    if(isset($res)){
 	        $rowCount = $adb->num_rows($res);
 	        if($rowCount > 0){
 	            $fieldname = $adb->query_result($res,0,'fieldname');
 	        }
 	    }
 	    return $fieldname;
 	}
}