<?php

/**
 * Guardamos un fichero
 * - p_module
 * - p_id --> id del registro al que queremos asociar el fichero
 * - p_file_url
 * - p_filename
 * - p_assigned_user_id
 * - p_descripcion
 * - p_table --> tabla donde se guarda la relación
 * - p_fieldname --> nombre del campo que guarda la relación
 */ 
function w34UploadFile($p_module, $p_crmid, $p_file_url, $p_filename, $p_assigned_user_id = 1, $p_description = '', $p_table = '', $p_field_attachemnt = '', $p_field_id = '') {
    global $adb, $root_directory, $upload_badext;
    $v_result = 0;
    if ($p_file_url != '')
    {
        //decide the file path where we should upload the file in the server
        $upload_filepath = decideFilePath();
        
        $v_result = $adb->getUniqueID("vtiger_crmentity");
        
        $a_file_info = pathinfo($p_file_url);
        if ($p_filename != '')
        {
            $filename = sanitizeUploadFileName(tbRemoveSpecialCharacters($p_filename), $upload_badext);
        }
        else 
        {
            $filename = sanitizeUploadFileName(tbRemoveSpecialCharacters($a_file_info['basename']), $upload_badext);
        }
        
        $new_filename = $v_result.'_'.$filename.'.'.$a_file_info['extension'];
        
        // descargamos la imagen
        $v_newfilepath = $root_directory.$upload_filepath.$new_filename;
        file_put_contents($v_newfilepath,file_get_contents($p_file_url));
        
        //Now store this file information in db and relate with the ticket
        $date_var = $adb->formatDate(date('Y-m-d H:i:s'), true);
        
        $crmquery = "insert into vtiger_crmentity (crmid,setype,description,createdtime, modifiedtime, smcreatorid, smownerid, modifiedby) values(?,?,?,?,?,?,?,?)";
        $adb->pquery($crmquery, array($v_result, $p_module.' Attachment', $p_description, $date_var, $date_var, $p_assigned_user_id, $p_assigned_user_id, $p_assigned_user_id));
        
        $attachmentquery = "insert into vtiger_attachments(attachmentsid,name,description,type,path) values(?,?,?,?,?)";
        $adb->pquery($attachmentquery, array($v_result, $filename.'.'.$a_file_info['extension'], $p_description, image_type_to_mime_type(exif_imagetype($v_newfilepath)), $upload_filepath));
        
        if ($v_result > 0 && $p_table != '' && $p_field_attachemnt != '')
        {
            $v_query = "UPDATE $p_table SET $p_field_attachemnt = ? WHERE $p_field_id = ?";
            $adb->pquery($v_query, array($v_result, $p_crmid));
        }
        
        
    }
    return $v_result;
}

/**
 * Permite crear un nuevo registro automáticamente
 * return new id or false
 * p_module --> module name (vtiger_tab)
 * p_fields
 *  K- columnname
 *  V- value
 */
function w34createNewRegistry($p_module, $p_fields) {
    require_once('modules/Users/Users.php');
    
    global $adb, $root_directory, $domusofis_proyecto, $current_user, $currentModule;
    
    
    if ($p_module != '' && count($p_fields) > 0)
    {
        $currentModule = $p_module;
        if (isset($p_fields['assigned_user_id']) && $p_fields['assigned_user_id'] > 0)
        {
            $v_user = new Users();
            $current_user = $v_user->retrieveCurrentUserInfoFromFile($p_fields['assigned_user_id']);
        }
        
        $v_query = "
            SELECT *
            FROM vtiger_tab vt
            INNER JOIN vtiger_field vf ON vf.tabid = vt.tabid
            WHERE vt.name = ? AND vt.presence != 1 AND vf.presence != 1
            ";
        $v_res = $adb->pquery($v_query, array($p_module));
        if ($v_res && $adb->num_rows($v_res) > 0)
        {
            $v_module = $adb->query_result($v_res, 0, 'name');
            
            $v_path = 'modules/'.$v_module.'/'.$v_module.'.php';
            if (file_exists($root_directory.$v_path))
            {
                include_once $v_path;
            }
            
            $focus = CRMEntity::getInstance($v_module);
            
            foreach ($focus->column_fields as $k=>$value)
            {
                if (isset($p_fields[$k]))
                {
                    $focus->column_fields[$k] = $p_fields[$k];
                }
            }
            //echo "--87,1--$v_module".var_dump($focus->column_fields);
            $focus->save($v_module);
            //echo "--995--".var_dump($focus->column_fields).$focus->id;
            return $focus->id;
            exit;
        }
    }
    
    return false;
}

/**
 * actualiza un registro
 *  - p_module --> Nombre del módulo
 *  - p_id --> id del registro
 *  - p_fields --> array con los nuevos valores
 */
function w34updateRegistry($p_module, $p_id, $p_fields) {
    require_once('modules/Users/Users.php');
    
    global $adb, $root_directory, $current_user;
    
    if (isset($p_fields['assigned_user_id']) && $p_fields['assigned_user_id'] > 0)
    {
        $v_user = new Users();
        $current_user = $v_user->retrieveCurrentUserInfoFromFile($p_fields['assigned_user_id']);
    }
    
    if ($p_id > 0 && count($p_fields) > 0)
    {
        $currentModule = $p_module;
        if (isset($p_fields['assigned_user_id']) && $p_fields['assigned_user_id'] > 0)
        {
            $v_user = new Users();
            $current_user = $v_user->retrieveCurrentUserInfoFromFile($p_fields['assigned_user_id']);
        }
        $v_query = "
            SELECT *
            FROM vtiger_tab vt
            INNER JOIN vtiger_field vf ON vf.tabid = vt.tabid
            WHERE vt.name = ? AND vt.presence != 1 AND vf.presence != 1";
        $v_res = $adb->pquery($v_query, array($p_module));
        if ($v_res && $adb->num_rows($v_res) > 0)
        {
            $v_module = $adb->query_result($v_res, 0, 'name');
            
            $v_path = 'modules/'.$v_module.'/'.$v_module.'.php';
            if (file_exists($root_directory.'custom/'.$domusofis_proyecto.'/'.$v_path))
            {
                include_once 'custom/'.$domusofis_proyecto.'/'.$v_path;
            }
            elseif (file_exists($root_directory.$v_path))
            {
                include_once $v_path;
            }
            
            $focus = CRMEntity::getInstance($v_module);
            $focus->id = $p_id;
            $focus->mode = "edit";
            $focus->retrieve_entity_info($p_id,$v_module);
            
            
            foreach ($focus->column_fields as $k=>$value)
            {
                if (isset($p_fields[$k]))
                {
                    $focus->column_fields[$k] = $p_fields[$k];
                }
            }
            
            $focus->save($v_module);
        }
    }
}

/**
 * devuelve un string sin caracteres especiales
 */
function tbStringEncode($p_cad)
{
    
    $a_caracteres_especiales = array(
        'á'=>'a',
        'à'=>'a',
        'â'=>'a',
        'ã'=>'a',
        'ª'=>'a',
        'ä'=>'a',
        'Á'=>'A',
        'À'=>'A',
        'Â'=>'A',
        'Ã'=>'A',
        'Ä'=>'A',
        'é'=>'e',
        'è'=>'e',
        'ê'=>'e',
        'ë'=>'e',
        'É'=>'E',
        'È'=>'E',
        'Ê'=>'E',
        'Ë'=>'E',
        'í'=>'i',
        'ì'=>'i',
        'î'=>'i',
        'ï'=>'i',
        'Í'=>'I',
        'Ì'=>'I',
        'Î'=>'I',
        'Ï'=>'I',
        'ó'=>'o',
        'ò'=>'o',
        'ô'=>'o',
        'õ'=>'o',
        'º'=>'o',
        'ö'=>'o',
        'Ó'=>'O',
        'Ò'=>'O',
        'Ô'=>'O',
        'Õ'=>'O',
        'Ö'=>'O',
        'ú'=>'u',
        'ù'=>'u',
        'û'=>'u',
        'ü'=>'u',
        'Ú'=>'U',
        'Ù'=>'U',
        'Û'=>'U',
        'Ü'=>'U',
        'ß'=>'ss',
        'ç'=>'c',
        'Ç'=>'C',
        'ñ'=>'n',
        'Ñ'=>'n',
        'Ÿ'=>'y',
        '|'=>'',
        ';'=>'',
        ':'=>'',
        ','=>'',
        '('=>'',
        ')'=>'',
        '¨'=>'',
        '+'=>'',
        '¡'=>'',
        '¿'=>'',
        '?'=>'',
        '!'=>'',
        '%'=>'',
        '"'=>'',
        '&ndash;'=>'-',
        '&ldquo;'=>'-',
        '&rdquo;'=>'-',
        '&lsquo;'=>'-',
        '&rsquo;'=>'-',
        '&#039;'=>'-',
        ' '=>'-',
        "'"=>'-',
        '´'=>'-',
        '`'=>'-',
        '’'=>'-',
        '\\' =>'',
        '&amp;'=>'',
        '&'=>'',
        '/'=>'-',
        '--'=>'-',
    );
    
    $v_c = utf8_decode(html_entity_decode(strip_tags($p_cad)));
    $v_c = trim($v_c);
    
    $a_enc = array();
    $a_no_enc = array();
    
    if (count($a_caracteres_especiales) > 0)
        foreach ($a_caracteres_especiales as $c => $v) {
            /*
             * if (normalizer_is_normalized($c, Normalizer::FORM_C))
             * {
             */
             $a_enc[$c] = $v;
             $a_no_enc[utf8_decode($c)] = $v;
             /*
              * }
              * else
              * {
              * $a_no_enc[$c] = $v;
              * $a_enc[normalizer_normalize($c, Normalizer::FORM_C)] = $v;
              * }
              */
        }
    
    
    if (count($a_enc) > 0)
        foreach ($a_enc as $c => $v)
            $v_c = str_replace($c, $v, $v_c);
            
            
            if (count($a_no_enc) > 0)
                foreach ($a_no_enc as $c => $v)
                    $v_c = str_replace($c, $v, $v_c);
                    
                    $v_c = str_replace("--", "-", $v_c);
                    $v_c = strtolower($v_c);
                    
                    // maximo 255 caracteres con palabras completas
                    $a_words = explode("-", $v_c);
                    $v_alias = '';
                    $v_num_words = count($a_words);
                    $i = 0;
                    if ($v_num_words > 0)
                        while (($v_num_words > $i) && (strlen($v_alias . '-' . $a_words[$i]) < 255)) {
                            if ($v_alias != '')
                                $v_alias .= '-';
                                $v_alias .= $a_words[$i];
                                $i ++;
                        }
                    
                    return $v_alias;
}



/* devuelve la cadena con los siguientes cambios
 - espacios --> '-'
 - apostrofes --> '-'
 - caracteres especiales --> equivalente ingles
 - minusculas
 */
function tbRemoveSpecialCharacters($p_cad) {
    $v_c = $p_cad;
    $a_caracteres_especiales = array(
        "º"=>"",
        "ª"=>"",
        "á"=>"a",
        "à"=>"a",
        "â"=>"a",
        "ã"=>"a",
        "ª"=>"a",
        "ä"=>"a",
        "Á"=>"A",
        "À"=>"A",
        "Â"=>"A",
        "Ã"=>"A",
        "Ä"=>"A",
        "é"=>"e",
        "è"=>"e",
        "ê"=>"e",
        "ë"=>"e",
        "É"=>"E",
        "È"=>"E",
        "Ê"=>"E",
        "Ë"=>"E",
        "í"=>"i",
        "ì"=>"i",
        "î"=>"i",
        "ï"=>"i",
        "Í"=>"I",
        "Ì"=>"I",
        "Î"=>"I",
        "Ï"=>"I",
        "ó"=>"o",
        "ò"=>"o",
        "ô"=>"o",
        "õ"=>"o",
        "º"=>"o",
        "ö"=>"o",
        "Ó"=>"O",
        "Ò"=>"O",
        "Ô"=>"O",
        "Õ"=>"O",
        "Ö"=>"O",
        "ú"=>"u",
        "ù"=>"u",
        "û"=>"u",
        "ü"=>"u",
        "Ú"=>"U",
        "Ù"=>"U",
        "Û"=>"U",
        "Ü"=>"U",
        "ß"=>"ss",
        "ç"=>"c",
        "Ç"=>"C",
        "ñ"=>"n",
        "Ñ"=>"n",
        "Ÿ"=>"y",
        "|"=>"",
        ";"=>"",
        ":"=>"",
        "."=>"",
        ","=>"",
        "("=>"",
        ")"=>"",
        "¨"=>"",
        "+"=>"",
        "¡"=>"",
        "¿"=>"",
        "?"=>"",
        "!"=>"",
        "%"=>"",
        '"'=>"",
        "&ndash;"=>"-",
        "&ldquo;"=>"-",
        "&rdquo;"=>"-",
        "&lsquo;"=>"-",
        "&rsquo;"=>"-",
        "&#039;"=>"-",
        " "=>"-",
        "´"=>"-",
        "`"=>"-",
        "’"=>"-",
        "\\" =>"",
        "&amp;"=>"",
        "/"=>"-",
        "--"=>"-",
    );
    
    foreach ($a_caracteres_especiales as $k=>$v)
    {
        $v_c = str_replace($k, $v, $v_c);
    }
    
    $v_c = utf8_decode(html_entity_decode(strip_tags($v_c), ENT_COMPAT, 'UTF-8'));
    $v_c = strtolower($v_c);
    $v_c = trim($v_c);
    
    foreach ($a_caracteres_especiales as $k=>$v)
    {
        $v_c = str_replace($k, $v, $v_c);
    }
    $v_c = str_replace("&", "", $v_c);
    
    return $v_c;
}


?>
