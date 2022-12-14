<?php
$v_dir = dirname(__FILE__);
$v_config_tb = $v_dir.'/tb_custom/tb/config_tb.inc.php';

if (isset($v_config_tb) && ($v_config_tb != '') && file_exists($v_config_tb)) {
    include_once $v_config_tb;
    require_once 'config.inc.php';
    include_once 'vtlib/Vtiger/Module.php';
} else {
    echo "No existe el fichero de configuración personalizado $v_config_tb";
    exit;
}


require_once 'tb-version.php';
$v_version = $g_tb_version;



// insertamos la tabla de control

$v_query = "SHOW TABLES LIKE 'tb_db'";
$v_res = $adb->query($v_query);

if ($adb->num_rows($v_res) === 0)
{
    $v_query = "CREATE TABLE `tb_db` (
              `id` int(11) NOT NULL,
              `version` decimal(10,3) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $adb->query($v_query);
    
    $v_query = "ALTER TABLE `tb_db` ADD PRIMARY KEY (`id`);";
    $adb->query($v_query);
    
    $v_query = "ALTER TABLE `tb_db` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
    $adb->query($v_query);
    
    $v_query = "INSERT INTO `tb_db` (`id`, `version`) VALUES ('1', '0');";
    $adb->query($v_query);
}


// comprobamos que la version de la BDD no es la actual

$v_query = "SELECT * FROM tb_db";
$v_res = $adb->query($v_query);

$v_db_version = 0;
if ($adb->num_rows($v_res) > 0)
{
    if ($adb->query_result($v_res, 0, 'version') > 0)
    {
        $v_db_version = $adb->query_result($v_res, 0, 'version');
    }
}

if ($v_db_version <= $v_version)
{
    // 1.101 primera version
    if ($v_db_version == "0.001") {include('tb_update/versions/001.php');}

    // 1.102 modulos para la carta online
    if ($v_db_version == "0.002") {include('tb_update/versions/002.php');}

    // 1.103 actualizaciones tabla vtiger_app2tab
    if ($v_db_version == "0.003") {include('tb_update/versions/003.php');}

    // 1.104 crear qr
    if ($v_db_version == "0.004") {include('tb_update/versions/004.php');}

    // 1.105 nuevos campos en el qr
    if ($v_db_version == "0.005") {include('tb_update/versions/005.php');}

    // 1.106 crear modulo campeonato
    if ($v_db_version == "0.006") {include('tb_update/versions/006.php');}

    // 1.107 crear modulo participantes
    if ($v_db_version == "0.007") {include('tb_update/versions/007.php');}

    // 1.107 crear modulos empleados, vacaciones, nominas y contratos
    if ($v_db_version == "0.008") {include('tb_update/versions/008.php');}
}

// ACTUALIZAMOS EL VALOR DE LA NUEVA VERSIÓN DE BDD
$v_query = "UPDATE tb_db SET version = ?";
$adb->pquery($v_query, array($v_version));

echo "<br><br>FINALIZADO CORRECTAMENTE";

echo "<br><a href='$site_URL'>Volver al CRM</a>";

?>