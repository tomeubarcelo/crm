window.addEventListener("load", function(event) {
    tbOnLoad();
});

function tbOnLoad() {
    var v_interval_categories = setInterval(function() {
        if (document.querySelectorAll('.menuCategorias .tbcatmenu').length > 0) {
            clearInterval(v_interval_categories);
            // cargamos las categorias
            tbGetCategories();

            // etiquetas generales
            tbTranslates();

            //array
            arrayPopups();
        } else if (document.querySelector('.returnMenu')) { //seccion favoritos

            clearInterval(v_interval_categories);
            // cargamos las categorias
            tbGetCategories();

            // etiquetas generales
            tbTranslates();

            //array
            arrayPopups();
        }
    }, 100);

    // igualamos el tamaño de las cajas
    tbBoxHeight();

    $(window).scroll(function() {
        var a_cats = document.querySelectorAll('.tbcat');
        if (a_cats.length > 0) {
            a_cats.forEach(function(el) {
                if (el.id != '') {
                    var v_catid = el.id;
                    if (isAnyPartOfElementInViewport(el)) {
                        let x = document.querySelectorAll('.tbcatmenu.active');
                        if (x) {
                            for (i = 0; i < x.length; i++) {
                                x[i].classList.remove('active');
                            }
                        }
                        //console.log('--987--', v_catid, el, '#tb'+v_catid);
                        if (document.querySelector('#tb' + v_catid)) {
                            document.querySelector('#tb' + v_catid).classList.add('active');
                        }

                    } else {
                        //console.log('--987--', v_catid, el, '#tb'+v_catid);
                        if (document.querySelector('#tb' + v_catid)) {
                            document.querySelector('#tb' + v_catid).classList.remove('active');
                        }
                    }
                }
            });
        }
    });
}

function tbBoxHeight() {
    bwmBoxSizes('#main .descriptionAndColumns .section .elements .box');
    window.addEventListener('resize', bwmBoxSizes('#main .descriptionAndColumns .section .elements .box'));
}

function bwmBoxSizes(p_cssid) {
    setTimeout(function() {
        var v_max = 0;
        var v_height_price = 0;
        var v_divs = document.querySelectorAll(p_cssid);
        if (v_divs.length > 0) {
            v_divs.forEach(function(el) {
                if (el.offsetHeight > v_max) {
                    v_max = el.offsetHeight;
                }
            });
            if (v_max > 0) {
                document.querySelectorAll(p_cssid).forEach(function(el) {
                    if (el.offsetHeight < v_max) {
                        el.setAttribute('style', 'height: ' + v_max + 'px');
                    }
                });
            }
        }
    }, 500);
}

function tbTranslates() {
    idiomaSugerencias();
    idiomaWarning();
    idiomaReturnMenu();
    refreshNumFavorite();
    bwmLoadFavorites();
    ocultaSectionSuger();

    idiomaLinksPolitica();
    idiomaLinksLegals();
    idiomaLinksCookies();
    idiomaLinksShop();
}

function tbGetCategories() {
    var a_cats = document.querySelectorAll('.menuCategorias .tbcatmenu');
    if (a_cats.length > 0) {
        a_cats.forEach(function(el) {
            if (parseInt(el.dataset.catid) > 0) {
                el.addEventListener('click', (evt) => {
                    closeMenu();
                    var v_cat = parseInt(el.dataset.catid);
                    var v_menu_el = document.querySelector('.menuCategorias #tbcat_' + v_cat);
                    var t_cats = document.querySelectorAll('.menuCategorias .tbcatmenu');
                    t_cats.forEach(function(tel) {
                        tel.classList.remove('active');
                    });

                    //					v_menu_el.classList.add('active');

                    $('html, body').animate({
                            scrollTop: $("#cat_" + v_cat).offset().top - 160
                        },
                        300);
                });
            }
        });
    }
}

function addModal(v_section, v_nameElementClass) {
    if (document.querySelector('.columns.' + v_section + ' .btnModal.' + v_nameElementClass)) {
        var modal = document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass);
        var btn = document.querySelector('.columns.' + v_section + ' .btnModal.' + v_nameElementClass);
        var span = document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass + ' .close');
        var body = document.getElementsByTagName('body')[0];

        //btn.onclick = function() {
        document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'block';
        document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.add("openModal");
        body.style.position = 'static';
        body.style.height = '100%';
        //body.style.overflow = 'hidden';
        //}

        span.onclick = function() {

            document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
            document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.remove("openModal");
            body.style.position = 'inherit';
            body.style.height = 'auto';
            //body.style.overflow = 'visible';
        }

        window.onclick = function(event) {
            if (event.target == document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass)) {
                document.querySelector('.columns.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
                document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.remove("openModal");
                body.style.position = 'inherit';
                body.style.height = 'auto';
                //body.style.overflow = 'visible';
            }
        }
    }
}

function addModalSugerencias(v_section, v_nameElementClass) {
    if (document.querySelector('.' + v_section + ' .btnModal.' + v_nameElementClass)) {
        var modal = document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass);
        var btn = document.querySelector('.' + v_section + ' .btnModal.' + v_nameElementClass);
        var span = document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass + ' .close');
        var body = document.getElementsByTagName('body')[0];

        //btn.onclick = function() {
        document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'block';
        document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.add("openModal");
        body.style.position = 'static';
        body.style.height = '100%';
        //body.style.overflow = 'hidden';
        //}

        span.onclick = function() {

            document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
            document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).remove.add("openModal");
            body.style.position = 'inherit';
            body.style.height = 'auto';
            //body.style.overflow = 'visible';
        }

        window.onclick = function(event) {
            if (event.target == document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass)) {
                document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
                document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.remove("openModal");
                body.style.position = 'inherit';
                body.style.height = 'auto';
                //body.style.overflow = 'visible';
            }
        }
    }
}

function addModalFavoritos(v_section, v_nameElementClass) {
    if (document.querySelector('.' + v_section + ' .btnModal.' + v_nameElementClass)) {
        var modal = document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass);
        var btn = document.querySelector('.' + v_section + ' .btnModal.' + v_nameElementClass);
        var span = document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass + ' .close');
        var body = document.getElementsByTagName('body')[0];

        //btn.onclick = function() {
        document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'block';
        document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.add("openModal");
        body.style.position = 'static';
        body.style.height = '100%';
        //body.style.overflow = 'hidden';
        //}

        span.onclick = function() {

            document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
            document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.remove("openModal");
            body.style.position = 'inherit';
            body.style.height = 'auto';
            //body.style.overflow = 'visible';
        }

        window.onclick = function(event) {
            if (event.target == document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass)) {
                document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).style.display = 'none';
                document.querySelector('.' + v_section + ' .tbModal.' + v_nameElementClass).classList.remove("openModal");
                body.style.position = 'inherit';
                body.style.height = 'auto';
                //body.style.overflow = 'visible';
            }
        }
    }
}

var arrElementsBox = [];

function arrayPopups() {
    var x = document.querySelectorAll('.tbModal.modalContainer')
    for (i = 0; i < x.length; i++) {
        arrElementsBox[i] = x[i].id
    }
}

function posteriorModal(p_elementPopup) { //next element

    if (arrElementsBox.includes(p_elementPopup)) {
        //console.log('Elemento: '+p_elementPopup);
        var v_position = arrElementsBox.lastIndexOf(p_elementPopup);
        //console.log('Posicion: '+v_position);
        v_position = v_position + 1;
        v_nextPopup = arrElementsBox[v_position];
        //console.log('Posicion: '+v_nextPopup);
        document.querySelector('.tbModal#' + p_elementPopup).style.display = "none";
        document.querySelector('.tbModal#' + p_elementPopup).classList.remove("openModal");

        document.querySelector('.tbModal#' + v_nextPopup).style.display = "block";
        document.querySelector('.tbModal#' + v_nextPopup).classList.add("openModal");
    }
}

function anteriorModal(p_elementPopup) { //prev. element

    if (arrElementsBox.includes(p_elementPopup)) {
        //console.log('Elemento: '+p_elementPopup);
        var v_position = arrElementsBox.lastIndexOf(p_elementPopup);
        //console.log('Posicion: '+v_position);
        v_position = v_position - 1;
        v_nextPopup = arrElementsBox[v_position];
        //console.log('Posicion: '+v_nextPopup);
        document.querySelector('.tbModal#' + p_elementPopup).style.display = "none";
        document.querySelector('.tbModal#' + p_elementPopup).classList.remove("openModal");

        document.querySelector('.tbModal#' + v_nextPopup).style.display = "block";
        document.querySelector('.tbModal#' + v_nextPopup).classList.add("openModal");
    }
}

function closeModal(p_elementClose) { //cierra ventana modal
    if (arrElementsBox.includes(p_elementClose)) {
        document.querySelector('.tbModal#' + p_elementClose).style.display = "none";
        document.querySelector('.tbModal#' + p_elementClose).classList.remove("openModal");
    }
}

$("html").click(function() {
    $(".tbModal.modalContainer").hide();
});

//al hacer click en estos elementos evitamos que la modal se cierre
$(".tbModal .modal-content").click(function(e) {
    e.stopPropagation();
});
$(".box .mainText .btn.btnModal").click(function(e) {
    e.stopPropagation();
});
$(".box .mainText .titleElement").click(function(e) {
    e.stopPropagation();
});


function ocultarBox(p_category) {
    var v_boxes = document.querySelectorAll('#' + p_category + ' .elements .box');
    if (v_boxes.length < 1) {
        var v_section = document.querySelector('#' + p_category);
        var v_menu = document.querySelector('.listaCartas .tb' + p_category);
        v_section.style.display = "none";
        v_menu.style.display = "none";

    }
}

function closeMenu() {
    $('.listaCartas').toggleClass("mobile");
}


//detectar si el elemento está en pantalla
function isAnyPartOfElementInViewport(el) {

    const rect = el.getBoundingClientRect();
    // DOMRect { x: 8, y: 8, width: 100, height: 100, top: 8, right: 108, bottom: 108, left: 8 }
    const windowHeight = (window.innerHeight || document.documentElement.clientHeight);
    const windowWidth = (window.innerWidth || document.documentElement.clientWidth);

    // http://stackoverflow.com/questions/325933/determine-whether-two-date-ranges-overlap
    const vertInView = (rect.top <= windowHeight) && ((rect.top + rect.height) >= 0);
    const horInView = (rect.left <= windowWidth) && ((rect.left + rect.width) >= 0);

    return (vertInView && horInView);
}



//cuando deslizamos el menu de idiomas se va
$('body').on({
    'touchmove': function(e) {
        //console.log($(this).scrollTop());
        if ($(this).scrollTop() > 150) {
            document.querySelector('.menuLanguageMobile').classList.add('hidden');
        } else {
            document.querySelector('.menuLanguageMobile').classList.remove('hidden');
        }
    }
});



//activamos el slider a partir de 1600px

$(window).on('load resize orientationchange', function() {
    if ($('.menuCategorias  a').length > 1) {

        document.querySelector('.menuCategorias').style.display = "block";
        new Glider(document.querySelector('.menuCategorias'), {

            // Mobile-first defaults
            slidesToShow: 2,
            slidesToScroll: 2,
            scrollLock: true,
            dots: '.dots',
            arrows: {
                prev: '.glider-prev',
                next: '.glider-next'
            },
            responsive: [{
                // screens greater than >= 775px
                breakpoint: 775,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 3,
                    itemWidth: 150,
                    duration: 0.25
                }
            }, {
                // screens greater than >= 1024px
                breakpoint: 1024,
                settings: {
                    // Set to `auto` and provide item width to adjust to viewport
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    itemWidth: 150,
                    duration: 0.25
                }
            }, {
                // screens greater than >= 1600px
                breakpoint: 1600,
                settings: {
                    // Set to `auto` and provide item width to adjust to viewport
                    //slidesToShow: 'auto',
                    //slidesToScroll: 'auto',
                    slidesToShow: 5,
                    slidesToScroll: 5,
                    itemWidth: 150,
                    duration: 0.25,
                    arrows: {
                        prev: '.glider-prev',
                        next: '.glider-next'
                    }
                }
            }]


        });
    }

    if ($('.languages a').length > 4) { //si hay mas de 4 idiomas..

        new Glider(document.querySelector('.languages'), {

            // Mobile-first defaults
            slidesToShow: 4,
            slidesToScroll: 4,
            scrollLock: true,
            /*dots: '.dots',
            arrows: {
              prev: '.glider-prev',
              next: '.glider-next'
            },*/
            responsive: [{
                // screens greater than >= 900px
                breakpoint: 900,
                settings: {
                    slidesToShow: 'auto',
                    slidesToScroll: 'auto',
                    itemWidth: 150,
                    duration: 0.25
                }
            }]


        });

    }


});

$(".openMenu").on('click', function() {
    var v_menu = document.querySelector('.listaCartas');
    var v_menuOpen = document.querySelector('.listaCartas .open');
    $('.listaCartas').toggleClass("mobile");
})


function toggleText(p_element) //muestra precios adicionales de los productos
{
    $('.' + p_element + ' .additional_prices').toggle();
    if (document.querySelector('.' + p_element + ' .toggleButton.activePrices')) {
        document.querySelector('.' + p_element + ' .toggleButton').classList.remove("activePrices");
    } else {
        document.querySelector('.' + p_element + ' .toggleButton').classList.add("activePrices");
    }
}

/*$(window).on('load resize orientationchange', function() {
	//en movil automaticamente se ve desplegado el texto de precios adicionales
	var v_addPrices = document.querySelectorAll('.additional_prices');
	var v_toggleButtons = document.querySelectorAll('.toggleButton');
    if (screen.width < 900){ //a partir de 900px aparece texto adicional
		for(i = 0; i<v_addPrices.length;i++){
			v_addPrices[i].style.display ="block";
		}
		for(j = 0; j<v_addPrices.length;j++){
			if(v_toggleButtons[j]){
				v_toggleButtons[j].classList.add("activePrices");
			}
		}
	} else{
		for(k = 0; k<v_addPrices.length;k++){
			v_addPrices[k].style.display ="none";
		}

		for(h = 0; h<v_addPrices.length;h++){
			if(v_toggleButtons[h]){
				v_toggleButtons[h].classList.remove("activePrices");
			}
		}
	}
});*/


function ocultaSectionSuger() { //si no hay ningun producto en sugerencias ocultamos la seccion
    var v_boxes = document.querySelectorAll('.sugerencias.section .elements .box');
    if (v_boxes.length < 1) {
        var v_section = document.querySelector('.sugerencias.section');
        if (v_section) {
            v_section.style.display = "none";
        }
    }
}

function idiomaSugerencias() {
    var v_sug = document.querySelector('#sectionCarta .descriptionAndColumns .sugerencias');
    var v_fav = document.querySelector('#sectionCarta .descriptionAndColumns .favoritos');
    var v_lang = document.documentElement.lang;
    if (v_sug) {
        var v_sugTitle = document.querySelector('#sectionCarta .descriptionAndColumns .sugerencias .sugerenciasTitle');
        if (v_lang.indexOf('es') >= 0) {
            v_sugTitle.innerHTML = "Sugerencias de la casa";
        } else if (v_lang.indexOf('en') >= 0) {
            v_sugTitle.innerHTML = "Our suggestions";
        } else if (v_lang.indexOf('de') >= 0) {
            v_sugTitle.innerHTML = "unsere Vorschläge";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_sugTitle.innerHTML = "Nos propositions";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_sugTitle.innerHTML = "наши предложения";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_sugTitle.innerHTML = "Sugerències de la casa";
        }
    }

    if (v_fav) {
        var v_favTitle = document.querySelector('#sectionCarta .descriptionAndColumns .favoritos .favoritosTitle');
        if (v_lang.indexOf('es') >= 0) {
            v_favTitle.innerHTML = "Favoritos";
        } else if (v_lang.indexOf('en') >= 0) {
            v_favTitle.innerHTML = "Favorites";
        } else if (v_lang.indexOf('de') >= 0) {
            v_favTitle.innerHTML = "Favoriten";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_favTitle.innerHTML = "Favoris";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_favTitle.innerHTML = "избранное";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_favTitle.innerHTML = "Favorits";
        }
    }
}

function idiomaWarning() {
    var v_alergenos = document.querySelector('.alergenos .left .warning h3');
    var v_necesidad = document.querySelector('.alergenos .right .necesidad h3');
    var v_lang = document.documentElement.lang;
    if (v_alergenos && v_necesidad) {
        if (v_lang.indexOf('es') >= 0) {
            v_alergenos.innerHTML = "LOS ALÉRGENOS ESTÁN INDICADOS CLICANDO SOBRE EL + DEL ARTÍCULO.";
            v_necesidad.innerHTML = "Si tiene alguna necesidad especial no dude en comunicarlo al camarero.";
        } else if (v_lang.indexOf('en') >= 0) {
            v_alergenos.innerHTML = "ALLERGENS ARE INDICATED BY CLICKING ON THE + OF THE ARTICLE.";
            v_necesidad.innerHTML = "If you have any special needs, do not hesitate to inform the waiter.";
        } else if (v_lang.indexOf('de') >= 0) {
            v_alergenos.innerHTML = "ALLERGENE WERDEN DURCH KLICKEN AUF DAS + DES ARTIKELS ANGEGEBEN.";
            v_necesidad.innerHTML = "Wenn Sie besondere Bedürfnisse haben, zögern Sie nicht, den Kellner zu informieren.";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_alergenos.innerHTML = "LES ALLERGÈNES SONT INDIQUÉS EN CLIQUANT SUR LE + DE L'ARTICLE.";
            v_necesidad.innerHTML = "Si vous avez des besoins particuliers, n'hésitez pas à en informer le serveur.";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_alergenos.innerHTML = "АЛЛЕРГЕНЫ УКАЗЫВАЮТСЯ ПРИ НАЖАТИИ НА «+» СТАТЬИ.";
            v_necesidad.innerHTML = "Если у вас есть особые потребности, не стесняйтесь сообщить об этом официанту.";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_alergenos.innerHTML = "ELS AL·LÈRGENS ESTAN INDICATS CLICANT SOBRE EL + DE L'ARTICLE.";
            v_necesidad.innerHTML = "Si teniu alguna necessitat especial no dubti en comunicar-ho al cambrer.";
        }
    }
}

function idiomaReturnMenu() {
    var v_txtReturn = document.querySelector('.returnMenu a span.txtReturn');
    var v_lang = document.documentElement.lang;
    if (v_txtReturn) {
        if (v_lang.indexOf('es') >= 0) {
            v_txtReturn.innerHTML = "VOLVER AL MENÚ";
        } else if (v_lang.indexOf('en') >= 0) {
            v_txtReturn.innerHTML = "BACK TO MENU.";
        } else if (v_lang.indexOf('de') >= 0) {
            v_txtReturn.innerHTML = "ZURÜCK ZUM MENÜ.";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_txtReturn.innerHTML = "RETOUR AU MENU";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_txtReturn.innerHTML = "НАЗАД К МЕНЮ.";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_txtReturn.innerHTML = "TORNAR AL MENÚ.";
        }
    }
}

function idiomaLinksPolitica() {
    var v_txtReturn = document.querySelector('footer p .politica');
    var v_lang = document.documentElement.lang;
    if (v_txtReturn) {
        if (v_lang.indexOf('es') >= 0) {
            v_txtReturn.innerHTML = "POLÍTICA DE PRIVACIDAD";
        } else if (v_lang.indexOf('en') >= 0) {
            v_txtReturn.innerHTML = "PRIVACY POLICY";
        } else if (v_lang.indexOf('de') >= 0) {
            v_txtReturn.innerHTML = "DATENSCHUTZ-BESTIMMUNGEN";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_txtReturn.innerHTML = "POLITIQUE DE CONFIDENTIALITÉ";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_txtReturn.innerHTML = "ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_txtReturn.innerHTML = "POLÍTICA DE PRIVACITAT";
        }
    }
}

function idiomaLinksLegals() {
    var v_txtReturn = document.querySelector('footer p .legales');
    var v_lang = document.documentElement.lang;
    if (v_txtReturn) {
        if (v_lang.indexOf('es') >= 0) {
            v_txtReturn.innerHTML = "AVISO LEGAL";
        } else if (v_lang.indexOf('en') >= 0) {
            v_txtReturn.innerHTML = "LEGAL WARNING";
        } else if (v_lang.indexOf('de') >= 0) {
            v_txtReturn.innerHTML = "RECHTLICHE HINWEISE";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_txtReturn.innerHTML = "AVIS JURIDIQUE";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_txtReturn.innerHTML = "ЮРИДИЧЕСКОЕ ПРЕДУПРЕЖДЕНИЕ";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_txtReturn.innerHTML = "AVÍS LEGAL";
        }
    }
}

function idiomaLinksCookies() {
    var v_txtReturn = document.querySelector('footer p .cookies');
    var v_lang = document.documentElement.lang;
    if (v_txtReturn) {
        if (v_lang.indexOf('es') >= 0) {
            v_txtReturn.innerHTML = "POLÍTICA DE PRIVACIDAD";
        } else if (v_lang.indexOf('en') >= 0) {
            v_txtReturn.innerHTML = "PRIVACY POLICY";
        } else if (v_lang.indexOf('de') >= 0) {
            v_txtReturn.innerHTML = "DATENSCHUTZ-BESTIMMUNGEN";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_txtReturn.innerHTML = "POLITIQUE DE CONFIDENTIALITÉ";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_txtReturn.innerHTML = "ПОЛИТИКА КОНФИДЕНЦИАЛЬНОСТИ";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_txtReturn.innerHTML = "POLÍTICA DE PRIVACITAT";
        }
    }
}

function idiomaLinksShop() {
    var v_txtReturn = document.querySelector('footer p .shop');
    var v_lang = document.documentElement.lang;
    if (v_txtReturn) {
        if (v_lang.indexOf('es') >= 0) {
            v_txtReturn.innerHTML = "TIENDA ONLINE";
        } else if (v_lang.indexOf('en') >= 0) {
            v_txtReturn.innerHTML = "ONLINE SHOP";
        } else if (v_lang.indexOf('de') >= 0) {
            v_txtReturn.innerHTML = "ONLINE-SHOP";
        } else if (v_lang.indexOf('fr') >= 0) {
            v_txtReturn.innerHTML = "BOUTIQUE EN LIGNE";
        } else if (v_lang.indexOf('ru') >= 0) {
            v_txtReturn.innerHTML = "ИНТЕРНЕТ-МАГАЗИН";
        } else if (v_lang.indexOf('ca') >= 0) {
            v_txtReturn.innerHTML = "TENDA ONLINE";
        }
    }
}

/*$(".fav").on('click', function(){
  console.log('Acción ejecutada!')
})*/

function bwmSaveElement(p_element) {
    console.log(p_element);
}



/*COOKIES[begin]*/
function tbSetCookie(cname, cvalue, exseconds) {
    var d = new Date();
    d.setTime(d.getTime() + (exseconds * 1000));
    var expires = "expires=" + d.toUTCString();
    var v_value = cvalue;
    if (typeof(cvalue) == 'string') {
        v_value = cvalue.replace(/;/gi, '');
        v_value = v_value.replace(/,/gi, '');
    }
    document.cookie = cname + "=" + v_value + "; path=/" + "; " + expires;
}

function tbGetCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function tbCheckCookie() {
    var username = getCookie("username");
    if (username != "") {
        //console.log("Welcome again " + username);
    } else {
        username = prompt("Please enter your name:", "");
        if (username != "" && username != null) {
            setCookie("username", username, 365);
        }
    }
}

function addToFavorite(p_propid) {

    if (p_propid > 0) {
        var cookieProps = tbGetCookie('tbFavElements');
        var a_props;
        if (cookieProps != '' && cookieProps !== undefined && cookieProps !== null) {
            a_props = cookieProps.split('#');

            if (a_props.includes(String(p_propid)) === false) {
                // añadimos la nueva propiedad
                a_props.push(p_propid);
                tbSetCookie('tbFavElements', a_props.join('#'), 730);
                isFavorite(p_propid, true);
            } else {
                // borramos la propiedad
                a_props = grep(a_props, function(value) {
                    return value != p_propid;
                });
                tbSetCookie('tbFavElements', a_props.join('#'), 730);
                isFavorite(p_propid, false);
            }
        } else {
            tbSetCookie('tbFavElements', p_propid, 730);
            isFavorite(p_propid, true);
        }
    }
    refreshNumFavorite();
}

function refreshNumFavorite() {
    var v_num = 0;

    var cookieProps = tbGetCookie('tbFavElements');
    var a_props;
    if (cookieProps != '' && cookieProps !== undefined && cookieProps !== null) {
        a_props = cookieProps.split('#');
        v_num = a_props.length;
    }

    var x = document.querySelectorAll('.bwm_favs span');
    var i;
    for (i = 0; i < x.length; i++) {
        x[i].innerHTML = v_num;
    }
}



function isFavorite(p_propid, p_isfavorite) {
    //console.log(p_propid);
    if (p_propid > 0) {
        if (p_isfavorite) {
            //console.log(document.querySelector('.fav_'+p_propid))
            document.querySelectorAll('.fav_' + p_propid).forEach(function(e) {
                e.classList.add('like', 'selected');
            });
        } else {
            document.querySelectorAll('.fav_' + p_propid).forEach(function(e) {
                e.classList.remove('like', 'selected');
            });
        }
    }
}

function bwmLoadFavorites() {
    var cookieProps = tbGetCookie('tbFavElements');
    var a_props;
    if (cookieProps != '' && cookieProps !== undefined && cookieProps !== null) {
        a_props = cookieProps.split('#');
        a_props.forEach(function(e) {
            isFavorite(e, true);
        });
    }
}

var grep = function(items, callback) {
    var filtered = [],
        len = items.length,
        i = 0;
    for (i; i < len; i++) {
        var item = items[i];
        var cond = callback(item);
        if (cond) {
            filtered.push(item);
        }
    }

    return filtered;
};
/*COOKIES[end]*/