<?php

// cargar el modulo de campeonatos
$v_query = "SELECT * FROM vtiger_tab WHERE tablabel IN ('tbCampeonatos')";
$v_res = $adb->query($v_query);
if ($adb->num_rows($v_res) === 0)
{
    // insertamos los módulos de menu
    $a_files = array(
        'ins-todo-tbCampeonatos.php',
    );
    
    $v_source_path = $root_directory.'tb_update/new_modules/';

    foreach ($a_files as $f)
    {
        if (file_exists($v_source_path.$f))
        {
            copy($v_source_path.$f, $root_directory.$f);
        }
    }
    
    include 'ins-todo-tbCampeonatos.php';
    
    foreach ($a_files as $f)
    {
        if (file_exists($root_directory.$f))
        {
            unlink($root_directory.$f);
        }
    }
    
}

