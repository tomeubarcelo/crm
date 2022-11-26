<?php

//ini_set("display_errors", 1);error_reporting(E_ALL);

require 'config.inc.php';
require_once 'include/utils/utils.php';
require_once 'include/utils/tbutils.php';
require_once('include/utils/CommonUtils.php');

global $adb,$site_URL;

$v_token = '';
$v_url = '';

if (isset($_GET['t']) && trim($_GET['t']) != '')
{
    $v_token = str_replace('-', '', str_replace('\\', '', str_replace('\'', '', str_replace('"', '', str_replace(' ', '', trim($_GET['t']))))));
}

if ($v_token != '')
{
    $v_qrsize = 300;
    $v_qrmargins = 10;
    
    // generamos el QR
    $v_path = '/'.trim(__DIR__, '/').'/test/qr/';
    if (!is_dir($v_path))
    {
        mkdir($v_path);
    }
    
    $v_filename = $v_path.$v_token.'.png';
    
    // obtener la información del qr
    $v_query = "
        SELECT *
        FROM vtiger_tbqr qr
        INNER JOIN vtiger_crmentity vce ON vce.crmid = qr.tbqrid
        WHERE token = ? ";
    $v_res = $adb->pquery($v_query, array($v_token));
    
    
    if ($v_res && $adb->num_rows($v_res) > 0)
    {
        $v_color_qr = $adb->query_result($v_res, 0, 'color_qr');
        $v_radio_qr = $adb->query_result($v_res, 0, 'radio_qr');
        $v_qr_url = $adb->query_result($v_res, 0, 'qr_url');
        $v_modo_qr = $adb->query_result($v_res, 0, 'modo_qr');
        $v_texto_qr = $adb->query_result($v_res, 0, 'texto_qr');
        $v_color_texto_qr = $adb->query_result($v_res, 0, 'color_texto_qr');
        $v_foto_qr = $adb->query_result($v_res, 0, 'foto_qr');
        $v_tipo_qr = $adb->query_result($v_res, 0, 'tipo_qr');
    }
    

    //tipo
    switch ($v_tipo_qr) { 
        case 'URL':
            
        /*
        * mode → campo modo
        * Mostrar texto → 2
        * Mostrar logo → 4
        * Por defecto → 0
        */
        $v_mode = 0;
        switch ($v_modo_qr) {
            case "Mostrar texto":
                $v_mode = 2;
                break;
            case "Mostrar logo":
                $v_mode = 4;
                break;
            default:
                $v_mode = 0;
        }
        
        $fontcolor = '#000';
        if($v_color_texto_qr != ''){
            $fontcolor = $v_color_texto_qr;
        }

        // para que se vea el logo mejor hay que poner el parámetro mode a 4
        $v_logo = base64_encode(file_get_contents("$site_URL/tbservice.php?service=tbimage&docofunction=image&imageid=$v_foto_qr"));
    
        ?>
        <html>
            <head>
                <title>QR Code</title>
                <img id="img-buffer" style="display: none;" src="data:image/png;base64,<?php echo $v_logo; ?>">
            </head>
            <body>
                <div id="qrcode"></div>
                <script type="text/javascript" src="libraries/jquery/jquery.min.js"></script>
                <script type="text/javascript" src="layouts/v7/resources/jquery-qrcode-0.18.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#qrcode').qrcode({
                            // render method: 'canvas', 'image' or 'div'
                            render: 'canvas',

                            // version range somewhere in 1 .. 40
                            minVersion: 1,
                            maxVersion: 40,

                            // error correction level: 'L', 'M', 'Q' or 'H'
                            ecLevel: 'Q',

                            // offset in pixel if drawn onto existing canvas
                            left: 0,
                            top: 0,

                            // size in pixel
                            size: '<?php echo $v_qrsize; ?>',

                            // code color or image element
                            fill: '<?php echo $v_color_qr; ?>',

                            // background color or image element, null for transparent background
                            background: null,

                            // content
                            text: '<?php echo $v_qr_url; ?>',

                            // corner radius relative to module width: 0.0 .. 0.5
                            radius: '<?php echo $v_radio_qr; ?>',

                            // quiet zone in modules
                            quiet: 0,

                            // modes
                            // 0: normal
                            // 1: label strip - NO USAR
                            // 2: label box
                            // 3: image strip - NO USAR
                            // 4: image box
                            mode: '<?php echo $v_mode; ?>',

                            mSize: 0.1,
                            mPosX: 0.5,
                            mPosY: 0.5,

                            label: '<?php echo $v_texto_qr; ?>',
                            fontname: 'sans',
                            fontcolor: '<?php echo $fontcolor; ?>',

                            image: $("#img-buffer")[0]
                        });
                    });
                </script>
            </body>
        </html>
        <?php
            
        exit;
        break;
    case 'VCARD':
        downloadVcard($v_res, $v_token);
        
        break;
    case 'EMAIL':
        
        break;
    case 'WIFI':
        
        break;
    case 'PDF':
        
        break;
    case 'HTML':
        $v_html = html_entity_decode($adb->query_result($v_res, 0, 'html'));
        $v_title = $adb->query_result($v_res, 0, 'title_html');
        if ($v_html != '')
        {
            echo "
                <html>
                    <head>
                        <title>$v_title</title>
                    </head>
                    <body>

                        $v_html
                    </body>
                </html>
            ";
            exit;
        }
        break;
    }

            
    }


function downloadVcard($p_values, $p_token) {
    global $adb, $site_URL;
    
    if ($p_token != '' && count($p_values) > 0)
    {
        $v_nombre = html_entity_decode($adb->query_result($p_values, 0, 'nombre'));
        $v_apellidos = html_entity_decode($adb->query_result($p_values, 0, 'apellidos'));
        $v_movil = $adb->query_result($p_values, 0, 'mobile');
        $v_telefono = $adb->query_result($p_values, 0, 'phone');
        $v_fax = $adb->query_result($p_values, 0, 'fax');
        $v_email = $adb->query_result($p_values, 0, 'email');
        $v_empresa = html_entity_decode($adb->query_result($p_values, 0, 'company'));
        $v_cargo = html_entity_decode($adb->query_result($p_values, 0, 'cargo'));
        $v_direccion = html_entity_decode($adb->query_result($p_values, 0, 'address'));
        $v_ciudad = html_entity_decode($adb->query_result($p_values, 0, 'city'));
        $v_cp = $adb->query_result($p_values, 0, 'cp');
        $v_provincia = html_entity_decode($adb->query_result($p_values, 0, 'state'));
        $v_pais = html_entity_decode($adb->query_result($p_values, 0, 'country'));
        $v_web = $adb->query_result($p_values, 0, 'website');
        // $v_image = $adb->query_result($p_values, 0, 'image');
        
        $v_function = '';
        if (isset($_GET['f']) && $_GET['f'] != '')
        {
            $v_function = $_GET['f'];
        }
        
        if ($v_function == '')
        { // mostramos el selector para descargar en excel o vcard o enviar por email
            // información de contacto
            $v_info = "";
            $v_buttons = "";
            if ($v_nombre != '' || $v_apellidos != '')
            {
                $v_info .= "<div class='field'><b>Nombre:</b> $v_nombre $v_apellidos</div>";
            }
            if ($v_empresa != '')
            {
                $v_info .= "<div class='field'><b>Empresa:</b> $v_empresa</div>";
            }
            if ($v_cargo != '')
            {
                $v_info .= "<div class='field'><b>Cargo:</b> $v_cargo</div>";
            }
            $v_enviar_email = '';
            if ($v_email != '')
            {
                $v_info .= "<div class='field'><b>Email:</b> <a href='mailto:$v_email'>$v_email</a></div>";
                $v_enviar_email = "<a href='mailto:$v_email'>Email</a>";
            }
            $v_llamar = "";
            if ($v_movil != '') {
                $v_info .= "<div class='field'><b>Móvil:</b> <a href='tel:".str_replace(" ", "", $v_movil)."'>$v_movil</a></div>";
                $v_buttons .= "<a href='tel:".str_replace(" ", "", $v_movil)."'>Llamar</a>";
            }
            if ($v_telefono != '') {
                $v_info .= "<div class='field'><b>Teléfono:</b> <a href='tel:".str_replace(" ", "", $v_telefono)."'>$v_telefono</a></div>";
                if ($v_llamar == "")
                {
                    $v_llamar = "<a href='tel:".str_replace(" ", "", $v_telefono)."'>Llamar</a>";
                }
            }
            if ($v_fax != '') {
                $v_info .= "<div class='field'><b>Fax:</b> $v_fax</div>";
            }
            if ($v_direccion != '') {
                $v_info .= "<div class='field'><b>Dirección:</b> $v_direccion<br />$v_cp - $v_ciudad<br />$v_provincia - $v_pais</div>";
            }
            if ($v_web != '') {
                $v_info .= "<div class='field'><b>Página web:</b> <a href='$v_web'>$v_web</a></div>";
                $v_website = '<a href="'.$v_web.'">Visitar Web</a>';
            }
            
            ?>
            <html>
            	<head>
            		<title><?php echo $v_nombre.' '.$v_apellidos?> - Tarjeta de visita</title>
            		<link type="text/css" rel="stylesheet" href="/layouts/v7/custom/tb/resources/tbstyles.css" media="screen">
            	</head>
            	<body>
            		<!-- mostrar imagen -->
            		<div class="qrview">
                		<div class="buttons">
                    		<a href="qr.php?f=vcard&t=<?php echo $p_token; ?>">Guardar VCard</a>
                    		<a href="qr.php?f=xls&t=<?php echo $p_token; ?>">Descargar XLS</a> 
                    		<a href="mailto:?subject=Contacto de <?php echo $v_nombre.' '.$v_apellidos?>&body=En el siguiente link encontrará los datos de contacto de <?php echo $v_nombre.' '.$v_apellidos?>%0D%0A%0D%0A<?php echo $site_URL.'qr.php?t='.$p_token;?>%0D%0A%0D%0APowered by TBMARKETING">Compartir</a>
                    		<?php echo $v_enviar_email; ?>
                    		<?php echo $v_llamar; ?>
                    		<?php echo $v_website; ?>
                		</div>
                		<div class="info">
                			<?php echo $v_info; ?>
                		</div>
                	</div>
            	</body>
            </html>
            <?php 
            exit;
        }
        elseif ($v_function == 'xls')
        {
            $delimiter = ",";
            $filename = $v_nombre."1_".date('Y-m-d') . ".csv";
            
            //create a file pointer
            $f = fopen('php://memory', 'w');
            
            //set column headers
            $fields = array('Nombre', 'Apellidos', 'Móvil', 'Teléfono', 'Fax', 'Email', 'Empresa', 'Cargo', 'Dirección', 'Ciudad', 'CP', 'Provincia', 'País', 'Web');
            fputcsv($f, $fields, $delimiter);                       
            
            $lineData = array($v_nombre, $v_apellidos, $v_movil, $v_telefono, $v_fax, $v_email, $v_empresa, $v_cargo, $v_direccion, $v_ciudad, $v_cp, $v_provincia, $v_pais, $v_web);
            fputcsv($f, $lineData, $delimiter);
            
            //move back to beginning of file
            fseek($f, 0);
            
            //set headers to download file rather than displayed
            header('Content-Encoding: UTF-8');
            header('Content-type: text/csv; charset=UTF-8');
            echo "\xEF\xBB\xBF"; // UTF-8 BOM
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            
            //output all remaining data on a file pointer
            fpassthru($f);
            
            exit();
        }
        elseif ($v_function == 'vcard')
        {
            
            ob_clean();
            header('Content-Type: text/x-vcard');
            header('Content-Disposition: inline; filename= "'.tbRemoveSpecialCharacters($v_nombre." ".$v_apellidos).'.vcf"');
            
            echo "BEGIN:VCARD".PHP_EOL;
            echo "VERSION:3.0".PHP_EOL;
            echo "FN;CHARSET=UTF-8:$v_nombre $v_apellidos".PHP_EOL;
            echo "N;CHARSET=UTF-8:$v_nombre;;;".PHP_EOL;
            echo "EMAIL;CHARSET=UTF-8;type=WORK,INTERNET:$v_email".PHP_EOL;
            echo "TEL;TYPE=CELL:".str_replace(" ", "", $v_movil).PHP_EOL;
            echo "TEL;TYPE=WORK,VOICE:".str_replace(" ", "", $v_telefono).PHP_EOL;
            echo "LABEL;CHARSET=UTF-8;TYPE=WORK:$v_empresa".PHP_EOL;
            echo "ADR;CHARSET=UTF-8;TYPE=WORK:;;$v_direccion;$v_ciudad;$v_provincia;$v_cp;$v_pais".PHP_EOL;
            echo "TITLE;CHARSET=UTF-8:$v_cargo".PHP_EOL;
            echo "ORG;CHARSET=UTF-8:$v_empresa".PHP_EOL;
            echo "URL;type=WORK;CHARSET=UTF-8:$v_web".PHP_EOL;
            echo "REV:".date("Y-m-d\TH:i:s.000\Z", strtotime($adb->query_result($p_values, 0, 'modifiedtime'))).PHP_EOL;
            echo "END:VCARD";
            
            exit;
        }
        
        
    }
}

function QRLog($p_id) {
    global $adb;
    if ($p_id > 0)
    {
        $v_ip = $_SERVER['REMOTE_ADDR'];
        $v_query = 'INSERT INTO tbqr_log (tbqrid, ip) VALUES (?,?);';
        $adb->pquery($v_query, array($p_id, $v_ip));
    }
}

 ?>
