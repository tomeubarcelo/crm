<?php

/*
 * Dentro del módulo tbqr hay que crear los siguientes campos dentro de un nuevo bloque de estilos:
 * Color QR (#000000) → (input type=colorpicker)
 * Radio [0..50] → valores posibles entr 0 y 50
 * Modo → listado con los valores:
 *      - Sin texto ni logo
 *      - Mostrar texto
 *      - Mostrar logo
 * Texto QR → que se puede mostrar en el QR
 * Logo (png) → Subir fichero con el logo
 * Texto color  (#000000) → (input type=colorpicker)
 */

/*tbQR - crear campos[BEGIN]*/
$v_tabid = 66;
$v_blockid = 0;
$v_table = 'vtiger_tbqr';


// nuevo bloque
$v_query = "SELECT * FROM vtiger_blocks WHERE blocklabel = 'LBL_STYLES_QR'";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    $v_query = "UPDATE vtiger_blocks_seq SET id = id+1 where 1;";
    $v_res = $adb->query($v_query);
    
    if ($v_res)
    {
        $v_query = "SELECT id FROM vtiger_blocks_seq;";
        $v_res = $adb->query($v_query);
        if ($adb->num_rows($v_res) > 0)
        {
            $v_blockid = $adb->query_result($v_res, 0, 'id');
            
            $v_query = "SELECT * FROM vtiger_tab WHERE name = 'tbqr'";
            $v_res = $adb->query($v_query);
            
            $v_tabid = 0;
            if ($adb->num_rows($v_res) > 0)
            {
                $v_tabid = $adb->query_result($v_res, 0, 'tabid');
            }
            
            if ($v_blockid > 0 && $v_tabid > 0)
            {
                
                $v_query = "SELECT (MAX(sequence) + 1) as seq FROM vtiger_blocks WHERE tabid = ?";
                $v_res = $adb->pquery($v_query, array($v_tabid));
                $v_seq = 1;
                if ($adb->num_rows($v_res) > 0)
                {
                    $v_tmp = $adb->query_result($v_res, 0, 'seq');
                    if ($v_tmp > 0)
                    {
                        $v_seq = $v_tmp;
                    }
                }
                
                $v_query = "INSERT INTO `vtiger_blocks` (`blockid`, `tabid`, `blocklabel`, `sequence`, `show_title`, `visible`, `create_view`, `edit_view`, `detail_view`, `display_status`, `iscustom`) VALUES
                            (?, ?, 'LBL_STYLES_QR', ?, 0, 0, 0, 0, 0, 1, 0);";
                $v_res = $adb->pquery($v_query, array($v_blockid, $v_tabid, $v_seq));
            }
            
        }
        
    }
    
}



/*Color QR[begin]*/
$v_field_name = 'color_qr';
$v_label = 'LBL_COLOR_QR';
$v_uitype = 1;
$v_typeofdata = 'V~O';
$v_defaultvalue = '#000000';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                            ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 1, 2, '$v_defaultvalue', 100, 401, $v_blockid, 1, '$v_typeofdata', 1, NULL, 'BAS',1, NULL);
                            ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                            ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                            ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                            SELECT `profileid` ,
                            '$v_tabid' AS tab,
                            (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                            '0' AS visible,
                            '0' AS readonly
                            FROM `vtiger_profile`
                            WHERE 1
                            );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` varchar(1000) default '';",
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Color QR[end]*/

/*Radio QR[begin]*/
$v_field_name = 'radio_qr';
$v_label = 'LBL_RADIO_QR';
$v_uitype = 7;
$v_typeofdata = 'N~O';
$v_defaultvalue = '0';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                            ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 1, 2, '$v_defaultvalue', 100, 402, $v_blockid, 1, '$v_typeofdata', 1, NULL, 'BAS',1, NULL);
                            ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                            ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                            ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                            SELECT `profileid` ,
                            '$v_tabid' AS tab,
                            (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                            '0' AS visible,
                            '0' AS readonly
                            FROM `vtiger_profile`
                            WHERE 1
                            );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` varchar(1000) default '';",
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Radio QR[end]*/


/*Modo → listado con los valores [begin]*/
$v_field_name = 'modo_qr';
$v_label = 'LBL_MODO_QR';
$v_uitype = 15;
$v_sequence = 403;
$v_typeofdata = 'V~O';
$v_defaultvalue = 'Sin texto ni logo';
$v_mostrarTexto = 'Mostrar texto';
$v_mostrarLogo = 'Mostrar logo';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                            ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 1, 2, '$v_defaultvalue', 100, $v_sequence, $v_blockid, 1, 'V~O', 1, NULL, 'BAS',1, NULL);
                            ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                            ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                            ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                            SELECT `profileid` ,
                            '$v_tabid' AS tab,
                            (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                            '0' AS visible,
                            '0' AS readonly
                            FROM `vtiger_profile`
                            WHERE 1
                            );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` varchar(255) default '';",
        "CREATE TABLE IF NOT EXISTS `vtiger_$v_field_name` (
              `".$v_field_name."id` int(19) NOT NULL,
              `$v_field_name` varchar(200) NOT NULL,
              `presence` int(1) NOT NULL default '1',
              `picklist_valueid` int(19) NOT NULL default '0',
              sortorderid int(11) NULL default '0',
              color varchar(10) NULL default ''
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;",
        "CREATE TABLE IF NOT EXISTS `vtiger_".$v_field_name."_seq` (
                id int(11) NOT NULL default 1
            );",
        "ALTER TABLE `vtiger_$v_field_name`
            ADD PRIMARY KEY (`".$v_field_name."id`);",
        "ALTER TABLE `vtiger_".$v_field_name."`
            MODIFY `".$v_field_name."id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;",
        
        "UPDATE  `vtiger_picklist_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "INSERT INTO `vtiger_picklist` (`picklistid`, `name`) VALUES
            ((SELECT `id` FROM `vtiger_picklist_seq` LIMIT 1), '$v_field_name');",
        
        "UPDATE  `vtiger_picklistvalues_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "INSERT INTO `vtiger_$v_field_name` (`".$v_field_name."`, `presence`, `picklist_valueid`, sortorderid, color) VALUES
            ('$v_defaultvalue', 1, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1), 1, '');",
        "INSERT INTO vtiger_role2picklist (roleid, picklistvalueid, picklistid, sortid)
            (SELECT roleid, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1),(SELECT `id` FROM `vtiger_picklist_seq` LIMIT 1), 1  FROM vtiger_role);",
        
        "UPDATE  `vtiger_picklistvalues_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "INSERT INTO `vtiger_$v_field_name` (`".$v_field_name."`, `presence`, `picklist_valueid`, sortorderid, color) VALUES
            ('$v_mostrarTexto', 1, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1), 1, '');",
        "INSERT INTO vtiger_role2picklist (roleid, picklistvalueid, picklistid, sortid)
            (SELECT roleid, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1),(SELECT `id` FROM `vtiger_picklist_seq` LIMIT 1), 1  FROM vtiger_role);",
        
        "UPDATE  `vtiger_picklistvalues_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "INSERT INTO `vtiger_$v_field_name` (`".$v_field_name."`, `presence`, `picklist_valueid`, sortorderid, color) VALUES
            ('$v_mostrarLogo', 1, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1), 1, '');",
        "INSERT INTO vtiger_role2picklist (roleid, picklistvalueid, picklistid, sortid)
            (SELECT roleid, (SELECT `id` FROM `vtiger_picklistvalues_seq` LIMIT 1),(SELECT `id` FROM `vtiger_picklist_seq` LIMIT 1), 1  FROM vtiger_role);",        
        
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Modo → listado con los valores [end]*/


/*Texto QR[begin]*/
$v_field_name = 'texto_qr';
$v_label = 'LBL_TEXTO_QR';
$v_uitype = 1;
$v_typeofdata = 'V~O';
$v_defaultvalue = '#000000';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                        ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 0, 0, '', 100, 404, $v_blockid, 1, 'V~O', 1, NULL, 'BAS',0, NULL);
                        ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                        ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                        ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                        SELECT `profileid` ,
                        '$v_tabid' AS tab,
                        (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                        '0' AS visible,
                        '0' AS readonly
                        FROM `vtiger_profile`
                        WHERE 1
                        );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` VARCHAR(255) default null;",
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Texto QR[end]*/


/*Foto QR[begin]*/
$v_field_name = 'foto_qr';
$v_label = 'LBL_FOTO_QR';
$v_uitype = 901;
$v_typeofdata = 'V~O';
$v_defaultvalue = '';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                            ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 1, 2, '$v_defaultvalue', 100, 405, $v_blockid, 1, '$v_typeofdata', 1, NULL, 'BAS',1, NULL);
                            ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                            ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                            ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                            SELECT `profileid` ,
                            '$v_tabid' AS tab,
                            (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                            '0' AS visible,
                            '0' AS readonly
                            FROM `vtiger_profile`
                            WHERE 1
                            );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` varchar(1000) default '';",
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Foto QR[end]*/


/*Texto color[begin]*/
$v_field_name = 'color_texto_qr';
$v_label = 'LBL_COLOR_TEXTO_QR';
$v_uitype = 1;
$v_typeofdata = 'V~O';
$v_defaultvalue = '';

$v_query = "SELECT * FROM vtiger_field WHERE fieldname = '$v_field_name' AND tabid = $v_tabid";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    
    $a_queries = array(
        "UPDATE  `vtiger_field_seq` SET  `id` = `id` + 1  LIMIT 1 ;",
        "DROP TEMPORARY TABLE IF EXISTS tmp_pot_sequence;",
        "CREATE TEMPORARY TABLE tmp_pot_sequence SELECT max( `sequence` ) +1 AS sequence FROM vtiger_field WHERE `block` = $v_blockid AND `tabid` = $v_tabid;",
        "INSERT INTO `vtiger_field` (`tabid`, `fieldid`, `columnname`, `tablename`, `generatedtype`, `uitype`, `fieldname`, `fieldlabel`, `readonly`, `presence`, `defaultvalue`, `maximumlength`, `sequence`, `block`, `displaytype`, `typeofdata`, `quickcreate`, `quickcreatesequence`, `info_type`, `masseditable`, `helpinfo`) VALUES
                            ($v_tabid, (SELECT id FROM vtiger_field_seq LIMIT 1), '$v_field_name', '$v_table', 1, '$v_uitype', '$v_field_name', '$v_label', 1, 2, '$v_defaultvalue', 100, 406, $v_blockid, 1, '$v_typeofdata', 1, NULL, 'BAS',1, NULL);
                            ",
        "INSERT INTO `vtiger_def_org_field` (`tabid`, `fieldid`, `visible`, `readonly`) VALUES
                            ('$v_tabid', (SELECT `id` FROM `vtiger_field_seq` LIMIT 1), '0', '1');
                            ",
        "INSERT INTO `vtiger_profile2field` ( `profileid` , `tabid` , `fieldid` , `visible` , `readonly` ) (
                            SELECT `profileid` ,
                            '$v_tabid' AS tab,
                            (SELECT `id` FROM `vtiger_field_seq` LIMIT 1) AS id,
                            '0' AS visible,
                            '0' AS readonly
                            FROM `vtiger_profile`
                            WHERE 1
                            );",
        "ALTER TABLE `$v_table` ADD `$v_field_name` varchar(1000) default '';",
    );
    
    foreach($a_queries as $q)
    {
        if ($q != '')
        {
            $adb->query($q);
        }
    }
    echo "<br>--99--$v_field_name";
    
}
/*Texto color[end]*/


/*tbQR - crear campos[END]*/