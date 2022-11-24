<?php

//actualizar vtiger_organizationdetails

$v_query = "UPDATE `vtiger_organizationdetails` SET `city` = 'Palma', `phone` = '680968942', `logoname` = 'logo_tbm.png' WHERE `vtiger_organizationdetails`.`organization_id` = 1;";

echo "--22-- updated vtiger_organizationdetails";
?>