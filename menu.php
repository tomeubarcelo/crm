<?php

// ini_set('display_errors', 1);
session_start();
ob_start();
require_once 'config.inc.php';
include_once 'vtlib/Vtiger/Module.php';
include_once 'include/utils/tbutils.php';
global $adb, $site_URL, $root_directory;

$a_scripts = array(
    'https://code.jquery.com/jquery-latest.js',
    'https://code.jquery.com/jquery-1.11.0.min.js',
    'https://code.jquery.com/jquery-migrate-1.2.1.min.js',
    $site_URL . 'layouts/v7/resources/glider/glider.js',
    $site_URL . 'tb_custom/tb/js/menu/custom_menu.js' // siempre el último script
);

$a_html_lang = array(
    'spanish' => 'es',
    'catala' => 'ca',
    'english' => 'en',
    'german' => 'de',
    'french' => 'fr',
    'italian' => 'it',
    'russian' => 'ru',
    'portuguese' => 'pt'
);

$a_currency = array(
    'Euro' => '€',
    'Dolar' => '$',
    'British pound' => '£'
);


$v_lang = 'english';
if (isset($_GET['l']) && $_GET['l'] != '') {
    $v_lang = trim($_GET['l']);
}

$id = 0;
if (isset($_GET['i']) && (int) $_GET['i'] > 0) {
    $id = (int) $_GET['i'];
} else {
    // mostrar un mensaje de advertencia de que el menú no existe
    echo "El menú no existe";
    die();
    exit();
}

// elementos favoritos
$g_favoritos = '';
if (isset($_COOKIE['tbFavElements']) && $_COOKIE['tbFavElements'] != '') {
    $g_favoritos = trim(str_replace('#', ',', str_replace('"', '', str_replace('#', ',', str_replace('-', '', $_COOKIE['tbFavElements'])))));
}

$pageFavoritos = false;
$v_where = '';
if (isset($_GET['favorites']) && $_GET['favorites'] == "1") {
    $v_where = "AND vme.tbmenuselementsid IN ($g_favoritos)";
    $pageFavoritos = true;
}


$g_menu = array();

// W34MENUS
$v_query = '
SELECT * FROM vtiger_tbmenus vm
INNER JOIN vtiger_tbmenuscf vmcf ON vmcf.tbmenusid = vm.tbmenusid
INNER JOIN vtiger_crmentity vce ON vce.crmid = vm.tbmenusid and vce.deleted = 0
WHERE vm.tbmenusid LIKE ?';

$v_res = $adb->pquery($v_query, array(
    $id
));

if ($v_res) {
    if (count($v_res) > 0) {
        foreach ($v_res as $k => $v_row) {
            if (count($v_row) > 0) {
                foreach ($v_row as $k_field => $v_value) {
                    $g_menu[$k_field] = $v_value;
                }
            }
        }
    }
}

if (count($g_menu) > 0 && isset($g_menu['tbmenusid']) && (int) $g_menu['tbmenusid'] > 0) {
    // variables del menu
    $v_tbmenusid = $g_menu['tbmenusid'];

    if ($g_menu['type_menu'] == 'Dinámica') {
        // categorias
        $v_query = '
            SELECT DISTINCT
            *, vme.active as element_active, vce.deleted as element_deleted
            FROM
                vtiger_tbmenuscategory vmc
            INNER JOIN vtiger_tbmenuscategorycf vmccf ON
                vmccf.tbmenuscategoryid = vmc.tbmenuscategoryid
            LEFT JOIN vtiger_tbmenuselements vme ON
                vme.category = vmc.tbmenuscategoryid AND vme.category = vmc.tbmenuscategoryid
            LEFT JOIN vtiger_tbmenuselementscf vmecf ON
                vmecf.tbmenuselementsid = vme.tbmenuselementsid
            LEFT JOIN vtiger_crmentity vce ON
                vce.crmid = vme.tbmenuselementsid
            INNER JOIN vtiger_crmentity vcec ON
                vcec.crmid = vmc.tbmenuscategoryid AND vcec.deleted = 0
            WHERE vmc.active = 1 AND vmc.menu_cat = ? ' . $v_where . ' ORDER BY vmc.order_category ASC, vme.order_element ASC';

        $v_res = $adb->pquery($v_query, array(
            $id
        ));

        $g_menu['categorias'] = array();
        $g_menu['active_langs'] = array();
        $g_menu['category_tree'] = array();

        if (count($v_res) > 0) {
            foreach ($v_res as $k => $row) {
                if (count($row) > 0) {
                    $v_catid = $row['tbmenuscategoryid'];

                    foreach ($row as $fieldname => $value) {
                        // categorías

                        $a_field = explode('_', $fieldname);
                        if ($a_field[0] == 'cat' && $a_field[1] == 'language') {
                            if ($row['cat_title_' . $a_field[2]] != '') {
                                $g_menu['categorias'][$v_catid][$value]['title'] = $row['cat_title_' . $a_field[2]];
                                $g_menu['categorias'][$v_catid][$value]['description'] = $row['cat_description_' . $a_field[2]];
                                $g_menu['categorias'][$v_catid][$value]['subtitle'] = $row['cat_subtitle_' . $a_field[2]];
                                $g_menu['categorias'][$v_catid][$value]['foto'] = $row['foto'];
                                $g_menu['categorias'][$v_catid][$value]['categoria_en_menu'] = $row['categoria_en_menu'];
                                $g_menu['categorias'][$v_catid][$value]['related_category'] = $row['related_category'];
                                $g_menu['categorias'][$v_catid][$value]['categoria_resumida'] = $row['categoria_resumida'];
                                $g_menu['categorias'][$v_catid][$value]['cat_number_of_columns'] = $row['cat_number_of_columns'];
                                $g_menu['categorias'][$v_catid][$value]['order_category'] = $row['order_category'];
                            }
                        }

                        // elementos
                        if ($a_field[0] == 'language') {
                            $v_element_type = 'elementos';
                            if ($row['sugerencia'] == 1) {
                                $v_element_type = 'sugerencias';
                            }

                            if ($row['title_' . $a_field[1]] != '' || $row['description_' . $a_field[1]] != '') {
                                $g_menu['active_langs'][$value] = 1;

                                if (! isset($g_menu['categorias'][$v_catid][$value][$v_element_type])) {
                                    $g_menu['categorias'][$v_catid][$value][$v_element_type] = array();
                                }

                                // alergenos
                                $t_alergenos = explode(' |##| ', $row['alergenos']);
                                $a_alergenos = array();
                                if (count($t_alergenos) > 0) {
                                    foreach ($t_alergenos as $alergeno) {
                                        $v_path = "layouts/v7/resources/Images/Icono-Alergeno-" . tbStringEncode($alergeno) . ".svg";

                                        if (file_exists($root_directory . $v_path)) {
                                            $a_alergenos[] = "<img loading='lazy' src='" . $site_URL . $v_path . "' alt='$alergeno'>";
                                        } else {
                                            $a_alergenos[] = $alergeno;
                                        }
                                    }
                                }

                                // currency
                                $v_currency = '€';
                                if (isset($a_currency[$row['currency_menu']])) {
                                    $v_currency = $a_currency[$row['currency_menu']];
                                }

                                // additional prices
                                $a_additional_prices = array();
                                for ($i = 1; $i <= 3; $i ++) {
                                    if ($row['title_price_additional' . $i] != '' && $row['price_additional' . $i] > 0) {
                                        $a_additional_prices[$i] = array(
                                            'title' => $row['title_price_additional' . $i],
                                            'price' => number_format($row['price_additional' . $i], 2, ',', '.')
                                        );
                                    }
                                }

                                if ($row['element_active'] == 1 && $row['element_deleted'] == 0) {
                                    $g_menu['categorias'][$v_catid][$value][$v_element_type][$row['tbmenuselementsid']] = array(
                                        'title' => $row['title_' . $a_field[1]],
                                        'description' => $row['description_' . $a_field[1]],
                                        'price' => number_format($row['price'], 2, ',', '.'),
                                        'currency_menu' => $v_currency,
                                        'alergenos' => $a_alergenos,
                                        'foto' => $row['foto_element'],
                                        'foto_detalle' => $row['foto_element_detail'],
                                        'categoria_en_menu' => $row['categoria_en_menu'],
                                        'related_category' => $row['related_category'],
                                        'additional_prices' => $a_additional_prices,
                                        'market_price' => $row['market_price'],
                                        'listado_subcategoria' => $row['listado_subcategoria']
                                    );
                                }
                            }
                        }
                    }
                }
            }
        }
    } else { // menú con pdfs
    }

    // organizamos el arbol de categorías
    if (count($g_menu['categorias']) > 0) {
        foreach ($g_menu['categorias'] as $kcat => $langs) {
            if (count($langs) > 0) {
                foreach ($langs as $klangs => $cat) {

                    if ((int) $cat['related_category'] > 0) {
                        if (! isset($g_menu['category_tree'][$klangs][$cat['related_category']])) {
                            $g_menu['category_tree'][$klangs][$cat['related_category']] = array();
                        }
                        $g_menu['category_tree'][$klangs][$cat['related_category']][$kcat] = '';
                    } elseif (! isset($g_menu['category_tree'][$klangs][$kcat])) {
                        $g_menu['category_tree'][$klangs][$kcat] = array();
                    }
                }
            }
        }
    }

    // W34MENU URLS EXTERNAS
    $v_query = '
        SELECT * FROM vtiger_tbmenusurls vmu
        INNER JOIN vtiger_tbmenusurlscf vmucf ON vmucf.tbmenusurlsid = vmu.tbmenusurlsid
        INNER JOIN vtiger_crmentity vce ON vce.crmid = vmu.tbmenusurlsid and vce.deleted = 0
        WHERE vmu.menu LIKE ?';
    $v_res = $adb->pquery($v_query, array(
        $id
    ));

    if (count($v_res) > 0) {
        foreach ($v_res as $row) {
            $g_menu['external_urls'][$row['language_url']][$row['type_url']] = $row['url'];
        }
    }
} else {
    // mostrar un mensaje de advertencia de que el menú no existe
    echo "El menú no existe";
    die();
    exit();
}

// devolvemos el script
$v_embed = false;
if (isset($_GET['embed']) && (int) $_GET['embed'] === 1) {
    $v_embed = true;
    // NO TOCAR, REQUERIDO PARA LA APP!!!
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        exit(0);
    }

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    header("content-type: application/json; charset=utf-8");
}

// var_dump($a_html_lang[$v_lang]);exit();
$msg_price_market = '';
if ($a_html_lang[$v_lang] == 'es') {
    $msg_price_market = 'P.S.M';
} else if ($a_html_lang[$v_lang] == 'fr') {
    $msg_price_market = 'S.P.M';
} else {
    $msg_price_market = 'M.P';
}
// var_dump($msg_price_market);exit();
// var_dump($g_menu['categorias']);exit();

?>

<?php if (!$v_embed) { ?>
<html lang="<?php echo $a_html_lang[$v_lang];?>">
<head>
<title>Menú <?php echo $g_menu['name'];?></title>
<link rel="SHORTCUT ICON" href="layouts/v7/skins/images/favicon.ico">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://kit.fontawesome.com/104ec30d65.js"
	crossorigin="anonymous"></script>
<?php } ?>


	      <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
          <?php

        if ($g_menu['font_family_url'] && $g_menu['font_family_name']) {
            echo $g_menu['font_family_url'];
        } else {
            ?>
    	      <link
	href="https://fonts.googleapis.com/css2?family=Oswald:wght@300&display=swap"
	rel="stylesheet">
          <?php } ?>

          <?php if ($g_menu['custom_css'] != '') { ?>
          	<style><?php echo $g_menu['custom_css'];?></style>
          <?php } ?>

          <link rel="stylesheet"
	href="<?php echo $site_URL; ?>tb_custom/tb/css/menu/custom_menu.css">
<!-- css personalizado -->
<style>
            body.tbmenubody {
                width: 100%;
                height: 100%;
                margin:0;
                <?php if ($g_menu['background_color'] != '') { ?>background-color: <?php echo $g_menu['background_color'];?>;<?php } ?>
                <?php if ((int)$g_menu['background_image'] > 0) {?>background-image: url("<?php echo $site_URL;?>tbservice.php?service=tbimage&docofunction=image&imageid=<?php echo (int)$g_menu['background_image'] ?>");<?php } ?>
                background-size: cover;
                <?php

                if ($g_menu['font_family_url'] && $g_menu['font_family_name']) {
                    echo "font-family: " . str_replace("font-family", "", str_replace(";", "", str_replace(":", "", $g_menu['font_family_name']))) . ";";
                } else {
                    echo "font-family: 'Oswald', sans-serif;";
                }
                ?>
            }
            /*colores del crm*/
            <?php
            if ($g_menu['text_color'] == '') {
                $g_menu['text_color'] = '#4a5568';
            }
            ?>
            .tbmenu{
                <?php if ($g_menu['background_color'] != '') { ?>background-color: <?php echo $g_menu['background_color'];?>;<?php } else { ?>
                background-color: #ffffff;
                <?php
                }
                ?>
            }
            header .listaCartas a {
                color: <?php echo $g_menu['text_color']; ?>;
            }
            header .listaCartas a.active {
                border-bottom: 2px solid <?php echo $g_menu['text_color']; ?>!important;
            }
            .returnMenu a{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .section{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .section h2:before{
                border-top: 2px solid <?php echo $g_menu['text_color']; ?>;
            }
            #main .elements .box .title span i{
                background: <?php echo $g_menu['text_color']; ?>;
            }
            #main .descriptionAndColumns .description{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .descriptionAndColumns .description h2:before{
                border-top: 2px solid <?php echo $g_menu['text_color']; ?>;
            }
            #main .descriptionAndColumns .columns h3:before{
                border-top: 2px solid <?php echo $g_menu['text_color']; ?>;
            }
            #main .alergenos .left h3{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #tbmenubody footer p {
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #tbmenubody footer a {
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #tbmenubody footer a:hover {
                color: <?php echo $g_menu['text_color']; ?>a6;
            }
            .modalContainer .close, .modalContainer .right-arrow, .modalContainer .left-arrow  {
      		    background: <?php echo $g_menu['text_color']; ?>;
      		}
            @media screen and (max-width: 650px) {
                header .listaCartas .openMenu{
                    background: <?php echo $g_menu['text_color']; ?>;
                }
            }
            @media screen and (max-width: 600px) {
                header .listaCartas a {
                  color: <?php echo $g_menu['text_color']; ?>;
                }
            }
            @media screen and (max-width: 400px) {
                header .listaCartas a.active:before {
                    border-top: 2px solid <?php echo $g_menu['text_color']; ?>;
                }
            }
            #tbmenubody .box .mainText .toggleButton::before {
                border-right: 1px solid <?php echo $g_menu['text_color']; ?>;
                border-bottom: 1px solid <?php echo $g_menu['text_color']; ?>;
            }

            .tbcat .textBtnElement .fav:before{
                background-image: url(<?php echo $site_URL; ?>layouts/v7/resources/Images/btn_fav_menu.svg);
            }

            .tbcat .textBtnElement .fav.like.selected:before {
                content: '';
                background-image: url(<?php echo $site_URL; ?>layouts/v7/resources/Images/btn_fav_menu_selected.svg);
            }

            header .glider-dots .glider-dot.active {
                background-color: <?php echo $g_menu['text_color']; ?>;
            }
            header .glider-prev:hover, .glider-next:hover, .glider-prev:focus, .glider-next:focus {
                color: <?php echo $g_menu['text_color']; ?>;
            }

            <?php if($v_embed) { ?>
            .tbcat .sugerencias .textBtnElement .fav {
                right: 22%;
            }
            #tbmenubody .container{
                width: 100%;
                float: initial!important;
            }
            .tbcat .textBtnElement .fav {
                display: none!important;
            }
            <?php } ?>

            #tbmenubody h2.sugerenciasTitle {
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .descriptionAndColumns .description h2{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .descriptionAndColumns .columns h3{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #main .elements h4.description_subcategory{
                color: <?php echo $g_menu['text_color']; ?>;
            }
            #tbmenubody #main .modalContainer .detail h2{
      		    color: <?php echo $g_menu['text_color']; ?>;
      		}
        </style>

<link rel="stylesheet" type="text/css"
	href="<?php echo $site_URL; ?>layouts/v7/resources/glider/glider.css">

<?php if(!$v_embed) { ?>
	</head>


<body class="tbmenubody">
<?php } ?>
<div id="tbmenubody">
		<header>
<?php if(!$v_embed) { ?>

			<nav class="logo">
				<div class="left">
					<?php if (isset($g_menu['external_urls'][$v_lang]['web']) && $g_menu['external_urls'][$v_lang]['web'] != '') { ?>
                  		<a
						href="<?php echo $g_menu['external_urls'][$v_lang]['web'] ?>"
						target="_blank">
                  	<?php } ?>
                  		<img loading='lazy'
						src="<?php echo $site_URL;?>tbservice.php?service=tbimage&docofunction=image&imageid=<?php echo $g_menu['logo']; ?>"
						height="60">
                  	<?php if (isset($g_menu['external_urls'][$v_lang]['web']) && $g_menu['external_urls'][$v_lang]['web'] != '') { ?>
                  		</a>
                  	<?php } ?>
                </div>

				<div class="right">
                	<?php

    foreach ($g_menu['active_langs'] as $l => $v) {
        $idioma = substr($l, 0, 2); // iniciales idioma
        echo "<a href='menu.php?l=$l&i=$id'><img loading='lazy' src='" . $site_URL . "layouts/v7/resources/Images/$idioma.png'></a>";
    }
    ?>
                </div>
				<div class='whats' style="display: none">
                <?php
    $v_ampersand = (urlencode('&'));
    $site_URL_Whats = substr($site_URL, 0, - 1);
    $v_url_whats = "https://api.whatsapp.com/send?text=Visita%20nuestra%20carta%20en%20$site_URL_Whats/menu.php?l=$v_lang $v_ampersand i=$id";
    $v_url_whats = str_replace(' ', '', $v_url_whats);
    ?>
        	   		 <a href='<?php echo $v_url_whats ?>' target='_blank'> <img
						loading='lazy'
						src='<?php echo $site_URL.'layouts/v7/resources/Images/ico-Whatsapp.svg' ?>'>
					</a>
				</div>
				<div class="bwm_favs mobile">
					<a
						href="menu.php?l=<?php echo $v_lang?>&i=<?php echo $id?>&favorites=1">
						<span>0</span>
					</a>
				</div>
			</nav>

<?php } ?>



              <?php
            if (!$pageFavoritos) {
                echo "<div class='listaCartas'><!--menu categorias [begin]-->
                    <span class='openMenu'>+</span>";
                echo "<div class='menuCategorias'>";
                foreach (($g_menu['categorias']) as $catid => $langs) {
                        echo "<a href='javascript:void(0)' class='tbcatmenu' id='tbcat_$catid' data-catid='$catid'>";
                        echo $langs[$v_lang]['title']; // titulo de la categoria
                        echo "</a>";
                
                }
                echo "</div>";

                echo "<button aria-label='Previous' class='glider-prev'>«</button>
                    <button aria-label='Next' class='glider-next'>»</button>
                    <div role='tablist' class='dots'></div></div>";
            }

            ?>
		</header>

		<div id="main" class="cartas">
			<!-- main [begin] -->

			<div class="container">
				<!-- container [begin] -->

				<div id="sectionCarta">
					<!-- section carta [begin] -->

					<!-- Menu Elements -->

<?php

// seccion favoritos [begin]
if ($pageFavoritos) {
    echo "<div class='returnMenu'>";
    echo "<a href='menu.php?l=$v_lang&i=$id'>";
    echo "<span class='txtReturn'></span>";
    echo "</a>";
    echo "</div>";

    echo "<!--seccion favoritos [begin]-->";
    echo "<div class='descriptionAndColumns tbcat'>";
    echo "<div class='favoritos section'>";
    echo "<h2 class='favoritosTitle'></h2>";
    echo "<div class='elements'>";

    if (count($g_menu['categorias']) > 0) {
        foreach ($g_menu['categorias'] as $cat) {
            if (count($cat[$v_lang]['elementos']) > 0) // mostramos los elementos como favoritos de 'elementos'
            {
                foreach ($cat[$v_lang]['elementos'] as $kel => $el) {
                    echo "<div class='box tbelement_" . $kel . "'>";
                    if ($el['foto'] > 0) { // si hay imagen..
                        echo "<img loading='lazy' onclick=addModalFavoritos('favoritos','modal_" . $kel . "') class='imgBox' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=300&height=300&imageid=" . $el['foto'] . "'>";
                        echo "<div class='mainText' style='width: 83%;'>";
                    } else { // si no existe invisible para mantener el espacio
                        echo "<div class='mainText' style='width: 97%;'>";
                    }

                    echo "<div class='title'>
                                                <div class='textTitleElement'>
                                                    <p class='titleElement' style='font-weight: bold' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['title'] . "</p>
                                                </div>
                                                <div class='textBtnElement'>
                                                    <a href='javascript:void(0)' class='fav fav_" . $kel . "' onclick=addToFavorite('" . $kel . "')></a>
                                                    <span class='btn btnModal modal_" . $kel . "' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>
                                                        <i class='fa-regular fa-plus'></i>
                                                    </span>
                                                </div>
                                             </div>";
                    echo "<div class='description'>";
                    if ($el['market_price'] == 1) { // si esta seleccionado precio de mercado..
                        $market_price = $msg_price_market; // mostraremos P.S.M
                        if ($el['description'] != '' || $el['description'] != null) {
                            echo "<div class='descriptionTitle'><p onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $market_price . "</span></div>";
                        } else { // si no hay descripcion solo mostrar precio
                            echo "<div class='descriptionPrice' style='width: 100%'><span>" . $market_price . "</span></div>";
                        }
                    } else { // si no esta seleccionado precio de mercado..
                        if (count($el['additional_prices']) > 0) // si hay precios adicionales..
                        {
                            if ($el['description'] != '' || $el['description'] != null) { // si hay descripcion
                                echo "<div class='descriptionTitle'><p style='width: 90%' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>

                                                    <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                                // echo "</div>";
                                echo "<div class='additional_prices'>";
                                foreach ($el['additional_prices'] as $otherprice) {
                                    echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                                }
                                echo "</div>";
                            } else { // si no hay descripcion

                                echo "<div class='additional_prices'>";
                                foreach ($el['additional_prices'] as $otherprice) {
                                    echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                                }
                                echo "</div>";
                            }
                        } else { // si NO hay precios adicionales..

                            if ($el['description'] != '' || $el['description'] != null) {

                                echo "<div class='descriptionTitle'><p onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $el['price'] . " " . $el['currency_menu'] . "</span></div>";
                            } else { // si no hay descripcion solo mostrar precio

                                echo "<div class='descriptionPrice' style='width: 100%'>
                                                            <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>
                                                        </div>";
                            }
                        }
                    }
                    echo "</div></div>";
                    echo "</div>";

                    // modal
                    $specialClassModal = '';
                    if ($el['foto_detalle'] > 0 || $el['foto'] > 0) {
                        $specialClassModal = "modalWithImg";
                    }
                    echo "<div class='$specialClassModal tbModal modal_" . $kel . " modalContainer'>";
                    echo "<div class='modal-content'>";
                    echo "<span class='close'></span>";
                    if ($el['foto_detalle'] > 0) { // si hay imagen preparada para que salga en el detalle..
                        echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto_detalle'] . "'>";
                    } else if ($el['foto'] > 0) { // si hay imagen normal..
                        echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto'] . "'>";
                    }
                    echo "<div class='detail'>";
                    echo "<h2>" . $el['title'] . "</h2>";
                    echo "<p class='textDescription'>" . $el['description'] . "</p>";

                    if (count($el['additional_prices']) > 0) {
                        if ($el['market_price'] == 1) {
                            $market_price = $msg_price_market;
                            echo "<span>" . $market_price . "</span>";
                        } else {
                            echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                        }

                        foreach ($el['additional_prices'] as $otherprice) {
                            echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                        }
                    } else {
                        if ($el['market_price'] == 1) {
                            $market_price = $msg_price_market;
                            echo "<span>" . $market_price . "</span>";
                        } else {
                            echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                        }
                    }

                    if (count($el['alergenos']) > 0) {
                        echo "<div class='icons'>";
                        foreach ($el['alergenos'] as $alergeno) {
                            echo $alergeno;
                        }
                        echo "</div>";
                    }
                    echo "</div>"; // fin modal

                    echo "</div>";
                    echo "</div>";

                    // echo "<script>addModalFavoritos('favoritos','modal_".$kel."');</script>";
                }
            } else if (count($cat[$v_lang]['sugerencias']) > 0) { // mostramos los elementos como favoritos de 'sugerencias'
                foreach ($cat[$v_lang]['sugerencias'] as $kel => $el) {
                    echo "<div class='box tbelement_" . $kel . "'>";
                    if ($el['foto'] > 0) { // si hay imagen..
                        echo "<img loading='lazy' onclick=addModalFavoritos('favoritos','modal_" . $kel . "') class='imgBox' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=300&height=300&imageid=" . $el['foto'] . "'>";
                        echo "<div class='mainText' style='width: 83%;'>";
                    } else { // si no existe invisible para mantener el espacio
                        echo "<div class='mainText' style='width: 97%;'>";
                    }

                    echo "<div class='title'>
                                                <div class='textTitleElement'>
                                                    <p class='titleElement' style='font-weight: bold' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['title'] . "</p>
                                                </div>
                                                <div class='textBtnElement'>
                                                    <a href='javascript:void(0)' class='fav fav_" . $kel . "' onclick=addToFavorite('" . $kel . "')></a>
                                                    <span class='btn btnModal modal_" . $kel . "' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>
                                                        <i class='fa-regular fa-plus'></i>
                                                    </span>
                                                </div>
                                             </div>";
                    echo "<div class='description'>";
                    if ($el['market_price'] == 1) { // si esta seleccionado precio de mercado..
                        $market_price = $msg_price_market; // mostraremos P.S.M
                        if ($el['description'] != '' || $el['description'] != null) {
                            echo "<div class='descriptionTitle'><p onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $market_price . "</span></div>";
                        } else { // si no hay descripcion solo mostrar precio
                            echo "<div class='descriptionPrice' style='width: 100%'><span>" . $market_price . "</span></div>";
                        }
                    } else { // si no esta seleccionado precio de mercado..
                        if (count($el['additional_prices']) > 0) // si hay precios adicionales..
                        {
                            if ($el['description'] != '' || $el['description'] != null) { // si hay descripcion
                                echo "<div class='descriptionTitle'><p style='width: 90%' onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>

                                                     <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                                // echo "</div>";
                                echo "<div class='additional_prices'>";
                                foreach ($el['additional_prices'] as $otherprice) {
                                    echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                                }
                                echo "</div>";
                            } else { // si no hay descripcion

                                echo "<div class='additional_prices'>";
                                foreach ($el['additional_prices'] as $otherprice) {
                                    echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                                }
                                echo "</div>";
                            }
                        } else { // si NO hay precios adicionales..

                            if ($el['description'] != '' || $el['description'] != null) {

                                echo "<div class='descriptionTitle'><p onclick=addModalFavoritos('favoritos','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $el['price'] . " " . $el['currency_menu'] . "</span></div>";
                            } else { // si no hay descripcion solo mostrar precio

                                echo "<div class='descriptionPrice' style='width: 100%'>
                                                            <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>
                                                        </div>";
                            }
                        }
                    }
                    echo "</div></div>";

                    echo "</div>";

                    // modal
                    $specialClassModal = '';
                    if ($el['foto_detalle'] > 0 || $el['foto'] > 0) {
                        $specialClassModal = "modalWithImg";
                    }
                    echo "<div class='$specialClassModal tbModal modal_" . $kel . " modalContainer'>";
                    echo "<div class='modal-content'>";
                    echo "<span class='close'></span>";
                    if ($el['foto_detalle'] > 0) { // si hay imagen preparada para que salga en el detalle..
                        echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto_detalle'] . "'>";
                    } else if ($el['foto'] > 0) { // si hay imagen normal..
                        echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto'] . "'>";
                    }
                    echo "<div class='detail'>";
                    echo "<h2>" . $el['title'] . "</h2>";
                    echo "<p class='textDescription'>" . $el['description'] . "</p>";

                    if (count($el['additional_prices']) > 0) {
                        if ($el['market_price'] == 1) {
                            $market_price = $msg_price_market;
                            echo "<span>" . $market_price . "</span>";
                        } else {
                            echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                        }

                        foreach ($el['additional_prices'] as $otherprice) {
                            echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                        }
                    } else {
                        if ($el['market_price'] == 1) {
                            $market_price = $msg_price_market;
                            echo "<span>" . $market_price . "</span>";
                        } else {
                            echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                        }
                    }

                    if (count($el['alergenos']) > 0) {
                        echo "<div class='icons'>";
                        foreach ($el['alergenos'] as $alergeno) {
                            echo $alergeno;
                        }
                        echo "</div>";
                    }
                    echo "</div>"; // fin modal

                    echo "</div>";
                    echo "</div>";

                    // echo "<script>addModalFavoritos('favoritos','modal_".$kel."');</script>";
                }
            }
        }
    }

    echo "</div>";
    echo "</div>";
    echo "</div>";
    echo " <!--seccion favoritos [end] -->";
}
// seccion favoritos [end]

// seccion sugerencias [begin]
if (!$pageFavoritos && count($g_menu['categorias']) > 0) {
    echo "<!--seccion sugerencias begin-->";
    echo "<div class='descriptionAndColumns tbcat'>"; // tbcat
    echo "<div class='sugerencias section'>"; // sugerencias section
    echo "<h2 class='sugerenciasTitle'></h2>";
    echo "<div class='elements'>"; // elements
    foreach ($g_menu['categorias'] as $cat) {
        // var_dump(count($cat[$v_lang]['sugerencias']));
        if (count($cat[$v_lang]['sugerencias']) > 0) { // var_dump($cat[$v_lang]['sugerencias']);
            foreach ($cat[$v_lang]['sugerencias'] as $kel => $el) {
                echo "<div class='box tbelement_" . $kel . "'>";
                if ($el['foto'] > 0) { // si hay imagen..
                    echo "<img loading='lazy' onclick=addModalSugerencias('sugerencias','modal_" . $kel . "') class='imgBox' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=300&height=300&imageid=" . $el['foto'] . "'>";
                    echo "<div class='mainText' style='width: 83%;'>";
                } else { // si no existe invisible para mantener el espacio
                    echo "<div class='mainText' style='width: 97%;'>";
                }
                echo "<div class='title'>
                                                <div class='textTitleElement'>
                                                    <p class='titleElement' onclick=addModalSugerencias('sugerencias','modal_" . $kel . "') style='font-weight: bold'>" . $el['title'] . "</p>
                                                </div>
                                                <div class='textBtnElement'>
                                                    <a href='javascript:void(0)' class='fav fav_" . $kel . "' onclick=addToFavorite('" . $kel . "')></a>
                                                    <span class='btn btnModal modal_" . $kel . "' onclick=addModalSugerencias('sugerencias','modal_" . $kel . "')>
                                                        <i class='fa-regular fa-plus'></i>
                                                    </span>
                                                </div>
                                             </div>";
                echo "<div class='description'>";
                if ($el['market_price'] == 1) { // si esta seleccionado precio de mercado..
                    $market_price = $msg_price_market; // mostraremos P.S.M
                    if ($el['description'] != '' || $el['description'] != null) {
                        echo "<div class='descriptionTitle'><p onclick=addModalSugerencias('sugerencias','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $market_price . "</span></div>";
                    } else { // si no hay descripcion solo mostrar precio
                        echo "<div class='descriptionPrice' style='width: 100%'><span>" . $market_price . "</span></div>";
                    }
                } else { // si no esta seleccionado precio de mercado..
                    if (count($el['additional_prices']) > 0) // si hay precios adicionales..
                    {
                        if ($el['description'] != '' || $el['description'] != null) { // si hay descripcion
                            echo "<div class='descriptionTitle'><p style='width: 90%' onclick=addModalSugerencias('sugerencias','modal_" . $kel . "')>" . $el['description'] . "</p></div>";
                            if ($el['price'] > 0) {
                                echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                            }

                            // echo "</div>";
                            echo "<div class='additional_prices'>";
                            foreach ($el['additional_prices'] as $otherprice) {
                                echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                            }
                            echo "</div>";
                        } else { // si no hay descripcion

                            echo "<div class='additional_prices'>";
                            foreach ($el['additional_prices'] as $otherprice) {
                                echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                            }
                            echo "</div>";
                        }
                    } else { // si NO hay precios adicionales..

                        if ($el['description'] != '' || $el['description'] != null) {

                            echo "<div class='descriptionTitle'><p onclick=addModalSugerencias('sugerencias','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $el['price'] . " " . $el['currency_menu'] . "</span></div>";
                        } else { // si no hay descripcion solo mostrar precio

                            echo "<div class='descriptionPrice' style='width: 100%'>
                                                            <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>
                                                        </div>";
                        }
                    }
                }
                echo "</div></div>";

                echo "</div>";

                // modal
                $specialClassModal = '';
                if ($el['foto_detalle'] > 0 || $el['foto'] > 0) {
                    $specialClassModal = "modalWithImg";
                }
                echo "<div class='$specialClassModal tbModal modal_" . $kel . " modalContainer'>";
                echo "<div class='modal-content'>";
                echo "<span class='close'></span>";
                if ($el['foto_detalle'] > 0) { // si hay imagen preparada para que salga en el detalle..
                    echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto_detalle'] . "'>";
                } else if ($el['foto'] > 0) { // si hay imagen normal..
                    echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto'] . "'>";
                }
                echo "<div class='detail'>";
                echo "<h2>" . $el['title'] . "</h2>";
                echo "<p class='textDescription'>" . $el['description'] . "</p>";

                if (count($el['additional_prices']) > 0) {
                    if ($el['market_price'] == 1) {
                        $market_price = $msg_price_market;
                        echo "<span>" . $market_price . "</span>";
                    } else {
                        echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                    }

                    foreach ($el['additional_prices'] as $otherprice) {
                        echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                    }
                } else {
                    if ($el['market_price'] == 1) {
                        $market_price = $msg_price_market;
                        echo "<span>" . $market_price . "</span>";
                    } else {
                        echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
                    }
                }

                if (count($el['alergenos']) > 0) {
                    echo "<div class='icons'>";
                    foreach ($el['alergenos'] as $alergeno) {
                        echo $alergeno;
                    }
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";
                echo "</div>"; // box

                // echo "<script>addModalSugerencias('sugerencias','modal_".$kel."');</script>";
            }
        }
    }
    echo "</div>"; // elements
    echo "</div>"; // sugerencias section
    echo "</div>"; // tbcat
    echo " <!--seccion sugerencias [end] -->";
}
// seccion sugerencias [end]

// todas las categorías [begin]
echo "<!-- todas las categorias [begin] -->";

if (! $pageFavoritos && count($g_menu['categorias']) > 0) {

    imprimirArbolCategorias($g_menu['category_tree'][$v_lang], $g_menu['category_tree'][$v_lang], $g_menu['categorias'], $v_lang);
}

echo "<!-- todas las categorias [end] -->";
// todas las categorías [end]

?>


		</div>
				<!-- section carta [END] -->


				<!-- alergenos [BEGIN] -->
		<?php
if (($g_menu) != null) {
    // exit();
    ?>
		<div class="alergenos">
					<div class="left">
						<div class="warning">
							<img loading='lazy'
								src="<?php echo $site_URL; ?>layouts/v7/resources/Images/Ico-warning-alergenos.png">
							<h3></h3>
						</div>
					</div>

					<div class="right">
						<div class="necesidad">
							<h3></h3>
						</div>
					</div>
				</div>
				<!-- alergenos [END] -->


				<?php if($g_menu['informacion_adicional'] != ""){?>
    				<!-- Informacion adicional [BEGIN] -->
				<div class="info_add">
					<p>
    				<?php
        $v_info = explode(";", $g_menu['informacion_adicional']);
        echo "<ul id='tblistInfo'>";
        for ($i = 0; $i < count($v_info); $i ++) {
            echo "<li class='tblistInfo_$i'>" . $v_info[$i] . "</li>";
        }
        echo "</ul>";
        // <!-- Informacion adicional [END] -->
        ?>
            </p>
				</div>

        <?php
    }
    ?>

		<?php
    // echo $g_menu['informacion_adicional'];
}
?>

	</div>
			<!-- container [END] -->


		</div>
		<!-- main [END] -->


<?php if (!$v_embed) { ?>
		<footer>
		<?php
    // var_dump($g_menu['external_urls']);
    $v_urlMain = $g_menu['external_urls'][$v_lang]['web'];
    $v_urlPrivacy = $g_menu['external_urls'][$v_lang]['privacy policy'];
    $v_urlLegals = $g_menu['external_urls'][$v_lang]['legal notice'];
    $v_urlCookies = $g_menu['external_urls'][$v_lang]['cookies policy'];
    $v_urlShop = $g_menu['external_urls'][$v_lang]['online shop'];

    ?>
			<p>
    			<?php if ($v_urlMain){ ?><a href="<?php echo $v_urlMain?>"><?php echo $g_menu['name'];?></a><?php } ?> © <?php echo date("Y")?> DESIGN BY
    			<a href="https://tbmarketing.com">TB MARKETING</a>
    			<?php if ($v_urlPrivacy){ ?> | <a
					href="<?php echo $v_urlPrivacy ?>" class="politica"></a><?php } ?>
    			<?php if ($v_urlLegals){ ?> | <a
					href="<?php echo $v_urlLegals ?>" class="legales"></a><?php } ?>
    			<?php if ($v_urlCookies){ ?> | <a
					href="<?php echo $v_urlCookies ?>" class="cookies"></a><?php } ?>
    			<?php if ($v_urlShop){ ?> | <a href="<?php echo $v_urlShop ?>"
					class="shop"></a><?php } ?>
			</p>
		</footer>
<?php } ?>




		<!-- IDIOMAS MOBILE [begin]-->
		<?php
echo "<div class='menuLanguageMobile' style='display:none'>";

echo "<div class='whats' style='display:none'>";
echo "<a href='https://api.whatsapp.com/send?text=Visita%20nuestra%20carta%20en%20" . $site_URL . "menu.php?l=$v_lang&i=$id' target='_blank'>
                        <img loading='lazy' src='" . $site_URL . "layouts/v7/resources/Images/ico-Whatsapp.svg'>
                     </a>";
echo "</div>";

echo "<div class='languages'>";
foreach ($g_menu['active_langs'] as $l => $v) {
    $idioma = substr($l, 0, 2); // iniciales idioma
    echo "<a href='menu.php?l=$l&i=$id' class='$l'><img loading='lazy' src='" . $site_URL . "layouts/v7/resources/Images/$idioma.png'></a>";
}
echo "</div>";

echo "<div class='bwm_favs mobile' style='display:none'>";
echo "<a href='menu.php?l=$v_lang&i=$id&favorites=1'>";
echo "<span>0</span>";
echo "</a>";
echo "</div>";

echo "</div>";
?>
		<!-- IDIOMAS MOBILE [end]-->

<?php
// scripts
if (count($a_scripts) > 0) {
    if (! $v_embed) {
        foreach ($a_scripts as $s) {
            if ($s != '') {
                echo '<script type="text/javascript" src="' . $s . '" defer></script>';
            }
        }
    }
}

if ($g_menu['custom_js'] != '') {
    echo '<script type="text/javascript">' . $g_menu['custom_js'] . '</script>';
    // var_dump($g_menu['custom_js']);
}

?>
</div>
<?php if (!$v_embed) { ?>
	</body>
</html>

<?php
}

if ($v_embed) {
    $v_html = ob_get_contents();
    ob_clean();
    echo json_encode(array(
        "html" => $v_html,
        "js" => $a_scripts
    ));
    exit();
}

/*
 * Imprime el arbol completo de categorias y elementos de forma recurrente
 * p_childs --> array de elementos a procesar
 * p_tree --> arbol completos de categorias
 * p_categorias --> la información de cada categoría y sus elementos
 * p_level --> nivel en el que nos encontramos
 * p_processed --> todas las categorías que ya se han procesado
 */
function imprimirArbolCategorias($p_childs, $p_tree, $p_categories, $p_lang, $p_level = 2, $p_processed = array())
{
    global $site_URL;
    $a_processed = $p_processed;

    if (count($p_tree) > 0 && count($p_categories) > 0) {
        foreach ($p_childs as $k => $childs) {
            $cat_vista_reducida = false;
            $a_fields = $p_categories[$k][$p_lang];
            // var_dump(is_array($p_tree[$k]));
            // var_dump(count($p_tree[$k]));
            if (is_array($p_tree[$k]) && count($p_tree[$k]) > 0) {

                if (! isset($a_processed[$k])) {

                    /* LOGICA PARA CATEGORIA EN VISTA RESUMIDA[begin] */
                    if ((int) $a_fields['categoria_resumida'] == 1) {
                        if ($a_fields['cat_number_of_columns'] != '') {

                            // Porcentaje de las columnas de elementos dentro de una categoria
                            $cat_number_of_columns = 0;
                            switch ($a_fields['cat_number_of_columns']) {
                                case '100%':
                                    $cat_number_of_columns = 100;
                                    break;
                                case '50% | 50%':
                                    $cat_number_of_columns = 50;
                                    break;
                                case '33% | 33% | 33%':
                                    $cat_number_of_columns = 33;
                                    break;
                                case '25% | 25% | 25% | 25%':
                                    $cat_number_of_columns = 25;
                                    break;
                                default:
                                    $cat_number_of_columns = 100;
                                    break;
                            }

                            if ($cat_number_of_columns > 0) {
                                // /echo "<p>Categoria resumida. Porcentaje columnas: ".$cat_number_of_columns."</p>";
                                $cat_vista_reducida = true;
                                // vistaReducida($k, $a_fields, $site_URL, $p_level, $a_processed, $cat_vista_reducida );
                                echo "<div class='descriptionAndColumns tbcat nivel$p_level' id='cat_$k' style='-webkit-order: " . $a_fields['order_category'] . "; -ms-flex-order: " . $a_fields['order_category'] . "; order: " . $a_fields['order_category'] . ";'>";
                                echo "<div class='description'>";
                                if ($a_fields['title'] != '') {
                                    echo "<h$p_level>" . $a_fields['title'] . "</h$p_level>"; // titulo
                                }
                                if ($a_fields['description'] != '') {
                                    echo "<p>" . $a_fields['description'] . "</p>"; // descripcion
                                }

                                // var_dump($a_cat['foto']);
                                if ($a_fields['foto'] != '') {
                                    echo "<div class='imgCategory'><img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $a_fields['foto'] . "'></div>";
                                }

                                echo "</div>"; // cierra <div class="description">
                                echo "<div class='columns section cat_" . $k . "'>";
                                if ($a_fields['subtitle'] != '') {
                                    echo "<h" . ($p_level + 1) . ">" . $a_fields['subtitle'] . "</h" . ($p_level + 1) . ">"; // subtitulo
                                }

                                echo "<div class='elements tbreducido columns_$cat_number_of_columns'>";
                                // var_dump($a_cat['elementos']);echo "test12";
                                // procesar el elemento
                                $a_processed[$k] = '';
                                imprimirCategoria($a_fields, $k, $cat_vista_reducida, $p_level);

                                $a_processed = imprimirArbolCategorias($p_tree[$k], $p_tree, $p_categories, $p_lang, ($p_level + 1), $a_processed);

                                echo "</div>"; // cierra el div elements
                                echo "</div>"; // cierra el div section_$kcat
                                echo "</div>"; // cierra div descriptionAndColumns
                            }
                        }
                        /* LOGICA PARA CATEGORIA EN VISTA RESUMIDA[end] */
                    } else {

                        echo "<div class='descriptionAndColumns tbcat nivel$p_level' id='cat_$k'>";
                        echo "<div class='description'>";
                        if ($a_fields['title'] != '') {
                            echo "<h$p_level>" . $a_fields['title'] . "</h$p_level>"; // titulo
                        }
                        if ($a_fields['description'] != '') {
                            echo "<p>" . $a_fields['description'] . "</p>"; // descripcion
                        }

                        // var_dump($a_cat['foto']);
                        if ($a_fields['foto'] != '') {
                            echo "<div class='imgCategory'><img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $a_fields['foto'] . "'></div>";
                        }

                        echo "</div>"; // cierra <div class="description">
                        echo "<div class='columns section cat_" . $k . "'>";
                        if ($a_fields['subtitle'] != '') {
                            echo "<h" . ($p_level + 1) . ">" . $a_fields['subtitle'] . "</h" . ($p_level + 1) . ">"; // subtitulo
                        }

                        echo "<div class='elements'>";
                        // var_dump($a_cat['elementos']);echo "test12";
                        // procesar el elemento
                        $a_processed[$k] = '';
                        imprimirCategoria($a_fields, $k, $cat_vista_reducida, $p_level);

                        $a_processed = imprimirArbolCategorias($p_tree[$k], $p_tree, $p_categories, $p_lang, ($p_level + 1), $a_processed);

                        echo "</div>"; // cierra el div elements
                        echo "</div>"; // cierra el div section_$kcat
                        echo "</div>"; // cierra div descriptionAndColumns
                    }
                }
            } elseif ((int) $k > 0 && isset($a_fields) && ((isset($a_fields['elementos']) && count($a_fields['elementos']) > 0) || (isset($a_fields['sugerencias']) && count($a_fields['sugerencias']) > 0) || count($p_tree[$k]) > 0)) {
                // && ((isset($a_fields['elementos']) && count($a_fields['elementos']) > 0) || count($p_tree[$k]) > 0)
                if (! isset($a_processed[$k])) {
                    // procesar el elemento

                    /* LOGICA PARA CATEGORIA EN VISTA RESUMIDA[begin] */
                    if ((int) $a_fields['categoria_resumida'] == 1) {
                        if ($a_fields['cat_number_of_columns'] != '') {

                            // Porcentaje de las columnas de elementos dentro de una categoria
                            $cat_number_of_columns = 0;
                            switch ($a_fields['cat_number_of_columns']) {
                                case '100%':
                                    $cat_number_of_columns = 100;
                                    break;
                                case '50% | 50%':
                                    $cat_number_of_columns = 50;
                                    break;
                                case '33% | 33% | 33%':
                                    $cat_number_of_columns = 33;
                                    break;
                                default:
                                    $cat_number_of_columns = 100;
                                    break;
                            }

                            if ($cat_number_of_columns > 0) {
                                // echo "<p>Categoria resumida. Porcentaje columnas: ".$cat_number_of_columns."</p>";
                                $cat_vista_reducida = true;
                                vistaReducida($k, $a_fields, $site_URL, $p_level, $a_processed, $cat_number_of_columns);
                            }
                        }
                        /* LOGICA PARA CATEGORIA EN VISTA RESUMIDA[end] */
                    } else {

                        echo "<div class='descriptionAndColumns tbcat nivel$p_level' id='cat_$k'>";
                        echo "<div class='description'>";

                        if ($a_fields['title'] != '') {
                            echo "<h$p_level>" . $a_fields['title'] . "</h$p_level>"; // titulo
                        }
                        if ($a_fields['description'] != '') {
                            echo "<p>" . $a_fields['description'] . "</p>"; // descripcion
                        }

                        // var_dump($a_cat['foto']);
                        if ($a_fields['foto'] != '') {
                            echo "<div class='imgCategory'><img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $a_fields['foto'] . "'></div>";
                        }

                        echo "</div>"; // cierra <div class="description">
                        echo "<div class='columns section cat_" . $k . "'>";
                        if ($a_fields['subtitle'] != '') {
                            echo "<h" . ($p_level + 1) . ">" . $a_fields['subtitle'] . "</h" . ($p_level + 1) . ">"; // subtitulo
                        }

                        echo "<div class='elements'>";
                        // var_dump($a_cat['elementos']);echo "test12";
                        // procesar el elemento
                        $a_processed[$k] = '';
                        imprimirCategoria($a_fields, $k, $cat_vista_reducida, $p_level);

                        echo "</div>"; // cierra el div elements
                        echo "</div>"; // cierra el div section_$kcat
                        echo "</div>"; // cierra div descriptionAndColumns
                    }
                }
            }
        }
    }
    return $a_processed;
}

function vistaReducida($k, $a_fields, $site_URL, $p_level, $a_processed, $p_columns)
{
    echo "<div class='vistareducida descriptionAndColumns tbcat nivel$p_level' id='cat_$k' style='-webkit-order: " . $a_fields['order_category'] . "; -ms-flex-order: " . $a_fields['order_category'] . "; order: " . $a_fields['order_category'] . ";'>";
    echo "<div class='description'>";
    if ($a_fields['title'] != '') {
        echo "<h$p_level>" . $a_fields['title'] . "</h$p_level>"; // titulo
    }
    if ($a_fields['description'] != '') {
        echo "<p>" . $a_fields['description'] . "</p>"; // descripcion
    }

    // var_dump($a_cat['foto']);
    if ($a_fields['foto'] != '') {
        echo "<div class='imgCategory'><img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $a_fields['foto'] . "'></div>";
    }

    echo "</div>"; // cierra <div class="description">
    echo "<div class='columns section cat_" . $k . "'>";
    if ($a_fields['subtitle'] != '') {
        echo "<h" . ($p_level + 1) . ">" . $a_fields['subtitle'] . "</h" . ($p_level + 1) . ">"; // subtitulo
    }

    echo "<div class='elements tbreducido columns_$p_columns'>";
    // var_dump($a_cat['elementos']);echo "test12";
    // procesar el elemento
    $a_processed[$k] = '';
    imprimirCategoria($a_fields, $k, true, $p_level);

    echo "</div>"; // cierra el div elements
    echo "</div>"; // cierra el div section_$kcat
    echo "</div>"; // cierra div descriptionAndColumns
}

function imprimirCategoria($p_array, $kcat, $cat_vista_reducida, $p_index = 0)
{
    // var_dump($p_array);
    foreach ($p_array as $kel => $el) {
        if (is_numeric($kel)) {
            // es un elemento
            if ($el['listado_subcategoria'] == 'Subcategoría') { // caso en el que es una subcategoria..
                                                                 // var_dump($el);echo "test12";
                if ($el['title'] != '') {
                    echo "<h$p_index class='subcategory'>" . $el['title'] . "</h$p_index>"; // titulo de la subcategoria del producto
                }

                if ($el['description'] != '') {
                    echo "<h" . ($p_index - 1) . " class='description_subcategory'>" . $el['description'] . "</h" . ($p_index - 1) . ">"; // titulo de la subcategoria del producto
                }
            } else {
                imprimirElemento($kel, $el, $kcat, $cat_vista_reducida);
            }
        } else {
            // es una subcategoría
            if (is_array($el)) {
                $p_index ++;
                // $p_index++;
                echo "<div class='subcategory nivel$p_index'>";
                // imprimir título subcategoría
                if ($el['title'] != '') {
                    echo "<h" . ($p_index - 1) . " class='title_subcategory'>" . $el['title'] . "</h" . ($p_index - 1) . ">";
                }

                // imprimimos los elementos de la subcategoría
                imprimirCategoria($el, $kcat, $cat_vista_reducida, $p_index ++);

                echo "</div>";
            }
        }
    } // end foreach
}

function imprimirElemento($kel, $el, $kcat, $cat_vista_reducida)
{
    global $site_URL;
    $v_lang = 'english';
    if (isset($_GET['l']) && $_GET['l'] != '') {
        $v_lang = trim($_GET['l']);
    }

    $a_html_lang = array(
        'spanish' => 'es',
        'catala' => 'ca',
        'english' => 'en',
        'german' => 'de',
        'french' => 'fr',
        'italian' => 'it',
        'russian' => 'ru',
        'portuguese' => 'pt'
    );
    $msg_price_market = '';
    if ($a_html_lang[$v_lang] == 'es' || $a_html_lang[$v_lang] == 'ca') {
        $msg_price_market = 'P.S.M';
    } else if ($a_html_lang[$v_lang] == 'fr') {
        $msg_price_market = 'S.P.M';
    } else {
        $msg_price_market = 'M.P';
    }
    /*
     * echo "<script>
     * arrElementsBox[i] = 'tbpopup_".$kel."';
     * //console.log(arrElementsBox[i]);
     * i =i+1;
     * //console.log(arrElementsBox);
     *
     * </script>";
     */

    if (! $cat_vista_reducida) {
        // esta activada vista normal

        echo "<div class='box tbelement_" . $kel . "'>";

        if ($el['foto'] > 0) { // si existe la imagen..
            echo "<img loading='lazy' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "') class='imgBox' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=300&height=300&imageid=" . $el['foto'] . "'>";
            echo "<div class='mainText' style='width: 83%;'>";
        } else { // si no existe invisible para mantener el espacio
            echo "<div class='mainText' style='width: 97%;'>";
        }
        echo "<div class='title'>
            <div class='textTitleElement'>
                <p class='titleElement' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "') style='font-weight: bold'>" . $el['title'] . "</p>
            </div>
            <div class='textBtnElement'>
                <a href='javascript:void(0)' class='fav fav_" . $kel . "' onclick=addToFavorite('" . $kel . "')></a>
                <span class='btn btnModal modal_" . $kel . "' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>
                    <i class='fa-regular fa-plus'></i>
                </span>
            </div>
         </div>";
        echo "<div class='description'>";
        if ($el['market_price'] == 1) { // si esta seleccionado precio de mercado..
            $market_price = $msg_price_market; // mostraremos P.S.M
            if ($el['description'] != '' || $el['description'] != null) {
                echo "<div class='descriptionTitle'><p onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $market_price . "</span></div>";
            } else { // si no hay descripcion solo mostrar precio
                echo "<div class='descriptionPrice' style='width: 100%'><span>" . $market_price . "</span></div>";
            }
        } else { // si no esta seleccionado precio de mercado..
            if (count($el['additional_prices']) > 0) // si hay precios adicionales..
            {
                if ($el['description'] != '' || $el['description'] != null) { // si hay descripcion
                    echo "<div class='descriptionTitle'><p style='width: 90%' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>

                                                   <!--<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>-->";
                    // echo "</div>";
                    echo "<div class='additional_prices'>";
                    foreach ($el['additional_prices'] as $otherprice) {
                        echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                    }
                    echo "</div>";
                } else { // si no hay descripcion

                    echo "<div class='additional_prices'>";
                    foreach ($el['additional_prices'] as $otherprice) {
                        echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                    }
                    echo "</div>";
                }
            } else { // si NO hay precios adicionales..

                if ($el['description'] != '' || $el['description'] != null) {

                    echo "<div class='descriptionTitle'><p onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $el['price'] . " " . $el['currency_menu'] . "</span></div>";
                } else { // si no hay descripcion solo mostrar precio

                    echo "<div class='descriptionPrice' style='width: 100%'>
                                                            <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>
                                                        </div>";
                }
            }
        }
        echo "</div></div>";

        echo "</div>";

        // modal
        $specialClassModal = '';
        if ($el['foto_detalle'] > 0 || $el['foto'] > 0) {
            $specialClassModal = "modalWithImg";
        }
        echo "<div class='$specialClassModal tbModal modal_" . $kel . " modalContainer' id='tbpopup_" . $kel . "'>";
        echo "<div class='modal-content'>";
        echo "<span class='close' onclick=closeModal('tbpopup_" . $kel . "')></span>";
        if ($el['foto_detalle'] > 0) { // si hay imagen preparada para que salga en el detalle..
            echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto_detalle'] . "'>";
        } else if ($el['foto'] > 0) { // si hay imagen normal..
            echo "<img loading='lazy' src='" . $site_URL . "tbservice.php?service=tbimage&docofunction=image&width=900&height=600&imageid=" . $el['foto'] . "'>";
        }
        echo "<div class='buttonsPopup'>
                                                    <span class='left-arrow' onclick=anteriorModal('tbpopup_" . $kel . "')></span></div>";
        echo "<div class='detail'>";
        echo "<h2>" . $el['title'] . "</h2>";
        echo "<p class='textDescription'>" . $el['description'] . "</p>";
        // echo "<span>".$el['price'] . " " . $el['currency_menu']."</span>";
        if (count($el['additional_prices']) > 0) {
            if ($el['market_price'] == 1) {
                $market_price = $msg_price_market;
                echo "<span>" . $market_price . "</span>";
            } else {
                echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
            }

            foreach ($el['additional_prices'] as $otherprice) {
                echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
            }
        } else {
            if ($el['market_price'] == 1) {
                $market_price = $msg_price_market;
                echo "<span>" . $market_price . "</span>";
            } else {
                echo "<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>";
            }
        }

        if (count($el['alergenos']) > 0) {
            echo "<div class='icons'>";
            foreach ($el['alergenos'] as $alergeno) {
                echo $alergeno;
            }
            echo "</div>";
        }

        echo "</div>";

        echo "<div class='buttonsPopup'>
                                                    <span class='right-arrow' onclick=posteriorModal('tbpopup_" . $kel . "')></span></div>";

        echo "</div>";
        echo "</div>";

        // echo "<script>addModal('cat_".$kcat."','modal_".$kel."');</script>";

        // fin vista normaml
    } else {
        // vista reducida
        echo "<div class='vista_reducida element tbelement_" . $kel . "'>";

        echo "<div class='mainText' style='width:100%;'>";
        echo "<div class='title'>
                                                <div class='textTitleElement'>
                                                    <p class='titleElement' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "') style='font-weight: bold'>" . $el['title'] . "</p>
                                                </div>
                                                <div class='textBtnElement'>
                                                    <a href='javascript:void(0)' class='fav fav_" . $kel . "' onclick=addToFavorite('" . $kel . "')></a>
                                                    <span class='btn btnModal modal_" . $kel . "' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>
                                                        <i class='fa-regular fa-plus'></i>
                                                    </span>
                                                </div>
                                             </div>";
        echo "<div class='description'>";
        if (count($el['alergenos']) > 0) {
            echo "<div class='icons'>";
            foreach ($el['alergenos'] as $alergeno) {
                echo $alergeno;
            }
            echo "</div>";
        }
        if ($el['market_price'] == 1) { // si esta seleccionado precio de mercado..
            $market_price = $msg_price_market; // mostraremos P.S.M
            if ($el['description'] != '' || $el['description'] != null) {
                echo "<div class='descriptionTitle'><p onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $market_price . "</span></div>";
            } else { // si no hay descripcion solo mostrar precio
                echo "<div class='descriptionPrice' style='width: 100%'><span>" . $market_price . "</span></div>";
            }
        } else { // si no esta seleccionado precio de mercado..
            if (count($el['additional_prices']) > 0) // si hay precios adicionales..
            {
                if ($el['description'] != '' || $el['description'] != null) { // si hay descripcion
                    echo "<div class='descriptionTitle'><p style='width: 90%' onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>

                                                   <!--<span>" . $el['price'] . " " . $el['currency_menu'] . "</span>-->";
                    // echo "</div>";
                    echo "<div class='additional_prices'>";
                    foreach ($el['additional_prices'] as $otherprice) {
                        echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                    }
                    echo "</div>";
                } else { // si no hay descripcion

                    echo "<div class='additional_prices'>";
                    foreach ($el['additional_prices'] as $otherprice) {
                        echo "<p>" . $otherprice['title'] . " " . $otherprice['price'] . " " . $el['currency_menu'] . "</p>";
                    }
                    echo "</div>";
                }
            } else { // si NO hay precios adicionales..

                if ($el['description'] != '' || $el['description'] != null) {

                    echo "<div class='descriptionTitle'><p onclick=addModal('cat_" . $kcat . "','modal_" . $kel . "')>" . $el['description'] . "</p></div>
                                                 <div class='descriptionPrice'><span>" . $el['price'] . " " . $el['currency_menu'] . "</span></div>";
                } else { // si no hay descripcion solo mostrar precio

                    echo "<div class='descriptionPrice' style='width: 100%'>
                                                            <span>" . $el['price'] . " " . $el['currency_menu'] . "</span>
                                                        </div>";
                }
            }
        }
        echo "</div></div>";

        echo "</div>";
    }
}

function tbrecortarString($cadena)
{
    // Si la longitud es mayor que el límite...
    $sufijo = '...';
    if (strlen($cadena) > 30) {
        // Entonces corta la cadena y pone el sufijo
        return mb_substr($cadena, 0, 30, 'utf8') . $sufijo;
    }

    // Si no, entonces devuelve la cadena normal
    return $cadena;
}

// devuelve el script para encajar el menu en una página
function tbGetMenuScript()
{
    global $site_URL;
    $v_result = '';

    // recuperamos todos los menus a mostrar
    $v_result .= '
        var a_divs = document.querySelectorAll(".tbmenu");
        if (a_divs.length > 0)
        {
            var v_interval = setTimeout(function() {
                a_divs.forEach (function (row) {
                    var v_lang = row.dataset.language;
                    var v_id = parseInt(row.dataset.menuid);
                    if (v_lang != "" && v_id > 0)
                    {
                        var v_url = "' . $site_URL . 'menu.php?embed=1&l="+v_lang+"&i="+v_id;

                        var v_request = new XMLHttpRequest();
                        v_request.onreadystatechange = tbMenuReady(v_request, v_lang, v_id);
                        v_request.open("GET", v_url, true);
                        v_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                        v_request.send();
                    }
                });
            }, 200);
        }


        function tbMenuReady(p_event, p_lang, p_id) {
            if (p_lang != "" && p_id > 0)
            {
                var v_interval = setInterval(function() {
                    if (p_event.readyState == 4) {
                       clearInterval(v_interval);
                       if(p_event.status == 200)
                       {
                         var a_json = JSON.parse(p_event.responseText);
                        var v_html = a_json["html"];
                        var a_menus = document.querySelectorAll(\'.tbmenu[data-language="\'+p_lang+\'"][data-menuid="\'+p_id+\'"]\');
                        a_menus.forEach(function(el) {
                            el.innerHTML = v_html;
                        });
                        a_json["js"].forEach(function(el) {
                          var script = document.createElement("script");
                          script.onload = function() {
                            console.log("Script loaded and ready "+el);
                            if (typeof window.tbOnLoad !== "undefined") {
                              tbOnLoad(); // a ejecutar al cargar
                            }
                          };
                          script.src = el;
                          document.getElementsByTagName("head")[0].appendChild(script);
                        });
                       }
                       else
                        console.log("Error loading page\n");
                    }
                }, 100);


            }
        }
    ';

    return $v_result;
}
?>
