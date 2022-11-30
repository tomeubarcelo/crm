

<?php

//ajustes vtiger_app2tab -> es el menu que se muestra en el crm

$v_query = 'UPDATE `vtiger_app2tab` SET `visible`=0 WHERE `appname` = "PROJECT"';
$adb->query($v_query);

$v_query = 'UPDATE `vtiger_app2tab` SET `appname`="TOOLS" WHERE `tabid` = 15';
$adb->query($v_query);

$v_query = 'UPDATE `vtiger_app2tab` SET `visible`=0 WHERE `appname` = "SUPPORT"';
$adb->query($v_query);

$v_query = 'UPDATE `vtiger_app2tab` SET `appname`="MARKETING" WHERE `tabid` = 61 OR `tabid` = 62';
$adb->query($v_query);

$v_query = 'UPDATE `vtiger_app2tab` SET `visible`=0 WHERE (`tabid` = 4 or `tabid` = 6) AND appname != "MARKETING"';
$adb->query($v_query);

$v_query = 'UPDATE `vtiger_app2tab` SET `visible`=0 WHERE `tabid` = 14 AND appname = "INVENTORY"';
$adb->query($v_query);

echo "--22-- updated vtiger_app2tab";
?>