<?php

// Cargamos los nuevos módulos empleados, nominas, vacaciones y contratos
$v_query = "SELECT * FROM vtiger_tab WHERE tablabel IN ('tbEmployees', 'tbContratos', 'tbVacaciones', 'tbNominas')";
$v_res = $adb->query($v_query);
if ($adb->num_rows($v_res) === 0)
{
    // insertamos los módulos de menu
    $a_files = array(
        'ins-todo-tbEmployees.php',
        'ins-todo-tbContratos.php',
        'ins-todo-tbVacaciones.php',
        'ins-todo-tbNominas.php',
    );
    
    $v_source_path = $root_directory.'tb_update/new_modules/';

    foreach ($a_files as $f)
    {
        if (file_exists($v_source_path.$f))
        {
            copy($v_source_path.$f, $root_directory.$f);
        }
    }
    
    include 'ins-todo-tbEmployees.php';
    include 'ins-todo-tbContratos.php';
    include 'ins-todo-tbVacaciones.php';
    include 'ins-todo-tbNominas.php';
    
    foreach ($a_files as $f)
    {
        if (file_exists($root_directory.$f))
        {
            unlink($root_directory.$f);
        }
    }
    
}

