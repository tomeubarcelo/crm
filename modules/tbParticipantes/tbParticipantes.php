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

class tbParticipantes extends Vtiger_CRMEntity {
	var $table_name = 'vtiger_tbparticipantes';
	var $table_index= 'tbparticipantesid';

	/**
	 * Mandatory table for supporting custom fields.
	 */
	var $customFieldTable = Array('vtiger_tbparticipantescf', 'tbparticipantesid');

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 */
	var $tab_name = Array('vtiger_crmentity', 'vtiger_tbparticipantes', 'vtiger_tbparticipantescf');

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 */
	var $tab_name_index = Array(
		'vtiger_crmentity' => 'crmid',
		'vtiger_tbparticipantes' => 'tbparticipantesid',
		'vtiger_tbparticipantescf'=>'tbparticipantesid');

	/**
	 * Mandatory for Listing (Related listview)
	 */
	var $list_fields = Array (
		/* Format: Field Label => Array(tablename, columnname) */
	    // tablename should not have prefix 'vtiger_'
	    'LBL_TBPARTICIPANTES_CAMPEONATO' => Array('tbparticipantes', 'campeonato'),
	    'LBL_TBPARTICIPANTES_AFILIADO' => Array('tbparticipantes', 'afiliado'),
	    'LBL_TBPARTICIPANTES_CLUB' => Array('tbparticipantes', 'club'),
	    'LBL_TBPARTICIPANTES_DISCIPLINA' => Array('tbparticipantes', 'disciplina'),
	    'LBL_TBPARTICIPANTES_GRADO' => Array('tbparticipantes', 'grado'),
	    'LBL_TBPARTICIPANTES_SELECCIONADO' => Array('tbparticipantes', 'seleccionado'),
		'Assigned To' => Array('crmentity','smownerid')
	);
	var $list_fields_name = Array (
	    /* Format: Field Label => fieldname */
	    'LBL_TBPARTICIPANTES_CAMPEONATO' => 'campeonato',
	    'LBL_TBPARTICIPANTES_AFILIADO' => 'afiliado',
	    'LBL_TBPARTICIPANTES_CLUB' => 'club',
	    'LBL_TBPARTICIPANTES_DISCIPLINA' => 'disciplina',
	    'LBL_TBPARTICIPANTES_GRADO' => 'grado',
	    'LBL_TBPARTICIPANTES_SELECCIONADO' => 'seleccionado',
		'Assigned To' => 'assigned_user_id',
	);

	// Make the field link to detail view
	var $list_link_field = 'afiliado';

	// For Popup listview and UI type support
	var $search_fields = Array(
		/* Format: Field Label => Array(tablename, columnname) */
	    // tablename should not have prefix 'vtiger_'
	    'LBL_TBPARTICIPANTES_CAMPEONATO' => Array('tbparticipantes', 'campeonato'),
	    'LBL_TBPARTICIPANTES_AFILIADO' => Array('tbparticipantes', 'afiliado'),
	    'LBL_TBPARTICIPANTES_CLUB' => Array('tbparticipantes', 'club'),
	    'LBL_TBPARTICIPANTES_DISCIPLINA' => Array('tbparticipantes', 'disciplina'),
	    'LBL_TBPARTICIPANTES_GRADO' => Array('tbparticipantes', 'grado'),
	    'LBL_TBPARTICIPANTES_SELECCIONADO' => Array('tbparticipantes', 'seleccionado'),
		'Assigned To' => Array('vtiger_crmentity','assigned_user_id'),
	);
	var $search_fields_name = Array (
	    /* Format: Field Label => fieldname */
	    'LBL_TBPARTICIPANTES_CAMPEONATO' => 'campeonato',
	    'LBL_TBPARTICIPANTES_AFILIADO' => 'afiliado',
	    'LBL_TBPARTICIPANTES_CLUB' => 'club',
	    'LBL_TBPARTICIPANTES_DISCIPLINA' => 'disciplina',
	    'LBL_TBPARTICIPANTES_GRADO' => 'grado',
	    'LBL_TBPARTICIPANTES_SELECCIONADO' => 'seleccionado',
		'Assigned To' => 'assigned_user_id',
	);

	// For Popup window record selection
	var $popup_fields = Array ('afiliado');

	// For Alphabetical search
	var $def_basicsearch_col = 'afiliado';

	// Column value to use on detail view record text display
	var $def_detailview_recname = 'afiliado';

	// Used when enabling/disabling the mandatory fields for the module.
	// Refers to vtiger_field.fieldname values.
	var $mandatory_fields = Array('afiliado','assigned_user_id');

	var $default_order_by = 'afiliado';
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

 	      $query = 'SELECT contact_no,campeonato from vtiger_tbparticipantes inner join vtiger_contactdetails on vtiger_tbparticipantes.afiliado=vtiger_contactdetails.contactid where tbparticipantesid = ?';
 	      $res = $adb->pquery($query,array($this->id));
 	      $num_licencia='';
 	      $num_campeonato='';
 	      if(isset($res)){
 	          $rowCount = $adb->num_rows($res);
 	          if($rowCount > 0){
 	              $num_licencia = $adb->query_result($res,0,'contact_no');
 	              $num_campeonato=$adb->query_result($res,0,'campeonato');
 	          }
 	      }
 	      $nombrefichero='MOD101-'.$num_licencia.'-'.$num_campeonato;

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

 	                  $query = "UPDATE vtiger_tbparticipantescf SET filename = ? WHERE tbparticipantesid = ?";
 	                  $re=$adb->pquery($query,array(decode_html($filename),$this->id));
 	                  //Inserting into attachments table
 	                  $this->insertIntoAttachment($this->id,'tbParticipantes',$filename);

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
 	    $tabid = getTabid('tbParticipantes');
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
 	    $tabid = getTabid('tbParticipantes');
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

	/**	function used to get the list of contacts which are related to the product
	 *	@param int $id - product id
	 *	@return array - array which will be returned from the function GetRelatedList
	 */
	function get_contacts($id, $cur_tab_id, $rel_tab_id, $actions=false) {
		global $log, $singlepane_view,$currentModule,$current_user;
		$log->debug("Entering get_contacts(".$id.") method ...");
		$this_module = $currentModule;

		$related_module = vtlib_getModuleNameById($rel_tab_id);
		require_once("modules/$related_module/$related_module.php");
		$other = new $related_module();
		vtlib_setup_modulevars($related_module, $other);
		$singular_modname = vtlib_toSingular($related_module);

		$parenttab = getParentTab();

		if($singlepane_view == 'true')
			$returnset = '&return_module='.$this_module.'&return_action=DetailView&return_id='.$id;
		else
			$returnset = '&return_module='.$this_module.'&return_action=CallRelatedList&return_id='.$id;

		$button = '';

		if($actions) {
			if(is_string($actions)) $actions = explode(',', strtoupper($actions));
			if(in_array('SELECT', $actions) && isPermitted($related_module,4, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_SELECT')." ". getTranslatedString($related_module). "' class='crmbutton small edit' type='button' onclick=\"return window.open('index.php?module=$related_module&return_module=$currentModule&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid=$id&parenttab=$parenttab','test','width=640,height=602,resizable=0,scrollbars=0');\" value='". getTranslatedString('LBL_SELECT'). " " . getTranslatedString($related_module) ."'>&nbsp;";
			}
			if(in_array('ADD', $actions) && isPermitted($related_module,1, '') == 'yes') {
				$button .= "<input title='".getTranslatedString('LBL_ADD_NEW'). " ". getTranslatedString($singular_modname) ."' class='crmbutton small create'" .
					" onclick='this.form.action.value=\"EditView\";this.form.module.value=\"$related_module\"' type='submit' name='button'" .
					" value='". getTranslatedString('LBL_ADD_NEW'). " " . getTranslatedString($singular_modname) ."'>&nbsp;";
			}
		}

		$query = "SELECT vtiger_contactdetails.firstname, vtiger_contactdetails.lastname, vtiger_contactdetails.title, vtiger_contactdetails.accountid, vtiger_contactdetails.email, vtiger_contactdetails.phone, vtiger_crmentity.crmid, case when (vtiger_users.user_name not like \"\") then vtiger_users.user_name else vtiger_groups.groupname end as user_name, vtiger_crmentity.smownerid, vtiger_tbparticipantes.* ,vtiger_account.accountname
			FROM vtiger_contactdetails
			INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_contactdetails.contactid
			INNER JOIN vtiger_contactaddress ON vtiger_contactdetails.contactid = vtiger_contactaddress.contactaddressid
			INNER JOIN vtiger_contactsubdetails ON vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid
			INNER JOIN vtiger_customerdetails ON vtiger_contactdetails.contactid = vtiger_customerdetails.customerid
			INNER JOIN vtiger_contactscf ON vtiger_contactdetails.contactid = vtiger_contactscf.contactid
			INNER JOIN vtiger_tbparticipantes ON vtiger_tbparticipantes.afiliado = vtiger_contactdetails.contactid
			LEFT JOIN vtiger_users ON vtiger_users.id = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_groups ON vtiger_groups.groupid = vtiger_crmentity.smownerid
			LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_contactdetails.accountid
			WHERE vtiger_crmentity.deleted = 0 AND vtiger_tbparticipantes.tbparticipantesid = ".$id;

		$return_value = GetRelatedList($this_module, $related_module, $other, $query, $button, $returnset);

		if($return_value == null) $return_value = Array();
		$return_value['CUSTOM_BUTTON'] = $button;

		$log->debug("Exiting get_contacts method ...");
		return $return_value;
	}

}
