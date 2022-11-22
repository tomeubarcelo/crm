<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
********************************************************************************/

// Adjust error_reporting favourable to deployment.
version_compare(PHP_VERSION, '5.5.0') <= 0 ? error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED & E_ERROR) : error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED  & E_ERROR & ~E_STRICT); // PRODUCTION
//ini_set('display_errors','on'); version_compare(PHP_VERSION, '5.5.0') <= 0 ? error_reporting(E_WARNING & ~E_NOTICE & ~E_DEPRECATED) : error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);   // DEBUGGING
//ini_set('display_errors','on'); error_reporting(E_ALL); // STRICT DEVELOPMENT


include('vtigerversion.php');

include_once 'config.security.php';

$v_dir = dirname(__FILE__);

// Fichero config del cliente
$tb_custom = 'localtomeu';

//Set the default layout
$default_layout = 'v7';

// MY CUSTOM FOLDERS
$tb_custom_layout = $v_dir.'/layouts/'.$default_layout.'/custom/'.$tb_custom.'/';

// MY CONFIG FILES
$v_config_tb = $v_dir.'/tb_custom/tb/config_tb.inc.php';

if (isset($tb_custom) && ($tb_custom != '') && file_exists($v_config_tb)) {
    include_once $v_config_tb;
}

?>
