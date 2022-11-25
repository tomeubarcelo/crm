<?php
/*
 * +*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ******************************************************************************
 */
//ini_set('display_errors', 1);error_reporting(E_ALL);

// CUSTOM SERVICE CONTROLLER [BEGIN]
include 'config.inc.php';

$a_path = pathinfo(__FILE__);
$v_file_path = $a_path['dirname'];
// CUSTOM SERVICE CONTROLLER [END]

//echo "-09-$v_path_w34";exit;
ini_set("error_reporting", "0");

if (isset($_REQUEST['service'])) {
    if ($_REQUEST['service'] == "tbimage") {
        include ("soap/tbimage.php");
    } else{
        echo "No Service Configured for " . strip_tags($_REQUEST['service']);
    }
} else {
    echo "<h1>W34 Soap Services</h1>";
}

?>