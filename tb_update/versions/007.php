<?php

// cargar el modulo de Participantes
$v_query = "SELECT * FROM vtiger_tab WHERE tablabel IN ('tbParticipantes')";
$v_res = $adb->query($v_query);
if ($adb->num_rows($v_res) === 0)
{
    // insertamos el modulo
    $a_files = array(
        'ins-todo-tbParticipantes.php',
    );
    
    $v_source_path = $root_directory.'tb_update/new_modules/';

    foreach ($a_files as $f)
    {
        if (file_exists($v_source_path.$f))
        {
            copy($v_source_path.$f, $root_directory.$f);
        }
    }
    
    include 'ins-todo-tbParticipantes.php';
    
    foreach ($a_files as $f)
    {
        if (file_exists($root_directory.$f))
        {
            unlink($root_directory.$f);
        }
    }
    
}

