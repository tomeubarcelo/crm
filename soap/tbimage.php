<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
/*
 * devuelve una imagen comprimida a partir de una url UTILIZADO PARA LOS EXPOSES PDF
 * parametros url (https://www.domusofis.com/w34service.php?)
 * -- service=docoservic
 * -- docofunction=compressimage
 * -- imageur=URL_A_COMPRIMIR
 * -- quality=[1-99] -> calidad con la que queremos la imagen
 */
if (isset($_REQUEST['docofunction'])) {
    if ($_REQUEST['docofunction'] == "image") {
        require_once ('include/utils/CommonUtils.php');
       
        global $adb;
        
        $v_imageid = (int) $_REQUEST['imageid'];

        $v_quality = 'NULL';
        if (isset($_REQUEST['quality']) && ($_REQUEST['quality'] > 0)) {
            $v_quality = (int) $_REQUEST['quality'];
        }
        
        $p_height = 0;
        if (isset($_REQUEST['height']) && ($_REQUEST['height'] > 0)) {
            $p_height = (int) $_REQUEST['height'];
        }
        
        $p_width = 0;
        if (isset($_REQUEST['width']) && ($_REQUEST['width'] > 0)) {
            $p_width = (int) $_REQUEST['width'];
        }
        
        $v_ruta = $root_directory . '/w34_custom_xCvM5gb3v9xWsAi9VULnLepab/w34/img/default-no-image.png';
        $v_type = 'image/png';
        $v_name = 'default-no-image.png';
        if ($v_imageid > 0) {
            $v_query = " SELECT *
						FROM `vtiger_attachments` va
						INNER JOIN vtiger_crmentity vce ON vce.crmid = va.`attachmentsid`
						AND vce.deleted =0
						WHERE `attachmentsid` =?
						AND (
						(
						`type` LIKE 'image/png'
						)
						OR (
						`type` LIKE 'image/jpeg'
						)
						OR (
						`type` LIKE 'image/gif'
						)
						OR (
						`type` LIKE 'application/pdf'
						)
						OR (
						`type` LIKE 'application/octet-stream'
						)
						)
						LIMIT 1 ";
            $v_result = $adb->pquery($v_query, array(
                $v_imageid
            ));
            if ($adb->num_rows($v_result) > 0) {
                $v_type = $adb->query_result($v_result, 0, 'type');
                $v_name = $adb->query_result($v_result, 0, 'name');
                $v_path = $adb->query_result($v_result, 0, 'path');
                $v_storedname = $adb->query_result($v_result, 0, 'storedname');
                $v_imageid = $adb->query_result($v_result, 0, 'attachmentsid');
                
                if ($v_name != '')
                    $v_ruta = $v_path . $v_imageid . '_' . $v_storedname;
            }
        }
        
        if (file_exists($v_ruta)) {
            
            if ($v_type == 'application/pdf' || $v_type == 'application/octet-stream')
            {
                ob_clean();
                header('Content-Type: image/jpeg');
                header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header ("Cache-Control: no-cache, must-revalidate");
                header ("Pragma: no-cache");
                echo file_get_contents($root_directory.'w34_custom_xCvM5gb3v9xWsAi9VULnLepab/w34/img/logo-pdf.jpg');
                exit;
            }
            
            if (strpos($v_ruta, '.gif') > 0)
            {
                ob_clean();
                
                header('Content-Type: image/gif');
                header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                header ("Cache-Control: no-cache, must-revalidate");
                header ("Pragma: no-cache");
                echo file_get_contents($v_ruta);
                exit;
            }
            
            // redimensionamos la imagen
            if ($p_width > 0 && $p_height > 0) {
                list ($v_width, $v_height) = getimagesize($v_ruta);
                
                // por donde recortar la imagen para conservar la nueva proporcion
                $v_h1 = (($v_width * $p_height) / $p_width);
                $v_w1 = (($v_height * $p_width) / $p_height);
                $v_y1 = 0;
                $v_x1 = 0;
                
                if ($v_h1 > $v_height) {
                    $v_x1 = ($v_width - $v_w1) / 2;
                    $v_h1 = $v_height;
                }
                if ($v_w1 > $v_width) {
                    $v_y1 = ($v_height - $v_h1) / 2;
                    $v_w1 = $v_width;
                }
            }
            
            $v_path_dst = $root_directory . 'cache/bwm/';
            $a_path_info = pathinfo($v_ruta);
            
            if (! file_exists($v_path_dst)) {
                mkdir($v_path_dst);
            }
            if ($p_width > 0 && $p_height > 0) {
                $v_dst_filename = $v_path_dst . $a_path_info['filename'] . '_' . $p_width . 'x' . $p_height . '.' . $a_path_info['extension'];
            } else {
                $v_dst_filename = $v_path_dst . $a_path_info['filename'] . '_0x0.' . $a_path_info['extension'];
            }
            
            $v_time_dst = 0;
            $v_time_src = 1;
            if (file_exists($v_dst_filename)) {
                $v_time_dst = filemtime($v_dst_filename);
                $v_time_src = filemtime($v_ruta);
            }
            
            ob_clean();
            
            if ($v_type == "image/jpeg") {
                header("Content-Type: image/jpeg");
            } else {
                header('Content-Type: image/png');
            }
            
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            
            if ($p_height == 0 && $p_width == 0) {
                // Servimos la foto original
                if ($v_type == "image/jpeg") {
                    $v_img = imagecreatefromjpeg($v_ruta);
                    imagejpeg($v_img);
                } else {
                    $v_img = imagecreatefrompng($v_ruta);
                    imageAlphaBlending($v_img, true);
                    imageSaveAlpha($v_img, true);
                    imagepng($v_img);
                }
            } elseif ($v_time_dst > $v_time_src) {
                if ($v_type == "image/jpeg") {
                    $v_img = imagecreatefromjpeg($v_dst_filename);
                    imagejpeg($v_img);
                } else {
                    $v_img = imagecreatefrompng($v_dst_filename);
                    imagepng($v_img);
                }
            } elseif ($v_type == "image/jpeg") {
                if ($p_width > 0 && $p_height > 0) {
                    $v_img = imagecreatefromjpeg($v_ruta);
                    $thumb = imagecreatetruecolor($p_width, $p_height);
                    imagecopyresampled($thumb, $v_img, 0, 0, $v_x1, $v_y1, $p_width, $p_height, $v_w1, $v_h1);
                    imagejpeg($thumb, $v_dst_filename, $v_quality);
                    imagejpeg($thumb);
                } else {
                    $v_img = imagecreatefromjpeg($v_ruta);
                    imagejpeg($v_img, $v_dst_filename, $v_quality);
                    imagejpeg($v_img);
                }
            } elseif ($v_type == "image/png") {
                
                if ($p_width > 0 && $p_height > 0) {
                    $v_img = imagecreatefrompng($v_ruta);
                    $thumb = imagecreatetruecolor($p_width, $p_height);
                    imagecopyresampled($thumb, $v_img, 0, 0, $v_x1, $v_y1, $p_width, $p_height, $v_w1, $v_h1);
                    imagepng($thumb, $v_dst_filename);
                    imagepng($thumb);
                } else {
                    $v_img = imagecreatefrompng($v_ruta);
                    imagepng($v_img, $v_dst_filename);
                    imagepng($v_img);
                }
            }
        }
    }
}

?>