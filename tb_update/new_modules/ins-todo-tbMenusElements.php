<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbMenusElements';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if (($moduleInstance) && !$v_is_test) {
    echo "Module already present - choose a different name.";
} else {
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent= 'Tools';
    $moduleInstance->save();
    
    // Schema Setup
    $moduleInstance->initTables();
    
    // Field Setup
    $block = new Vtiger_Block();
    $block->label = 'LBL_'. strtoupper($moduleInstance->name) . '_INFORMATION';
    $moduleInstance->addBlock($block);
    
    
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"ME",1); // change ENR to your sequence prefix
    
    
    // Menu
    $field100 = new Vtiger_Field();
    $field100->name = 'menu';
    $field100->column = 'menu';
    $field100->label = 'LBL_'.strtoupper($moduleInstance->name).'_MENU';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block->addField($field100);
    $field100->setRelatedModules(Array('tbMenus'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);
    
    // Category
    $field101 = new Vtiger_Field();
    $field101->name = 'category';
    $field101->column = 'category';
    $field101->label = 'LBL_'.strtoupper($moduleInstance->name).'_CATEGORY';
    $field101->columntype = 'VARCHAR(50)';
    $field101->uitype = 10;
    $field101->summaryfield = 1;
    $field101->typeofdata = 'V~M';
    $block->addField($field101);
    $field101->setRelatedModules(Array('tbMenusCategory'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field101);
    
    // Price
    $field102 = new Vtiger_Field();
    $field102->name = 'price';
    $field102->column = 'price';
    $field102->label = 'LBL_'.strtoupper($moduleInstance->name).'_PRICE';
    $field102->columntype = 'DECIMAL(5,2)';
    $field102->uitype = 7;
    $field102->summaryfield = 1;
    $field102->typeofdata = 'NN~O';
    $block->addField($field102);
    
    // Currency
    $field103 = new Vtiger_Field();
    $field103->name = 'currency_menu';
    $field103->column = 'currency_menu';
    $field103->label = 'LBL_'.strtoupper($moduleInstance->name).'_CURRENCY';
    $field103->columntype = 'VARCHAR(255)';
    $field103->uitype = 15;
    $field103->summaryfield = 1;
    $field103->typeofdata = 'V~O';
    $block->addField($field103);
    $field103->setPicklistValues( Array ('Euro', 'Dolar', 'British pound') );
    
    // Active
    $field104 = new Vtiger_Field();
    $field104->name = 'active';
    $field104->column = 'active';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_ACTIVE';
    $field104->columntype = 'VARCHAR(255)';
    $field104->uitype = 56;
    $field104->summaryfield = 1;
    $field104->typeofdata = 'C~O';
    $field104->defaultvalue = true;
    $block->addField($field104);
    
    
    // Sugerencias
    $field110 = new Vtiger_Field();
    $field110->name = 'sugerencia';
    $field110->column = 'sugerencia';
    $field110->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUGERENCIAS';
    $field110->columntype = 'VARCHAR(255)';
    $field110->uitype = 56;
    $field110->summaryfield = 1;
    $field110->typeofdata = 'C~O';
    $field110->defaultvalue = false;
    $block->addField($field110);
    
    
    // Alérgenos 
    $field109 = new Vtiger_Field();
    $field109->name = 'alergenos';
    $field109->column = 'alergenos';
    $field109->label = 'LBL_'.strtoupper($moduleInstance->name).'_ALERGENOS';
    $field109->columntype = 'VARCHAR(255)';
    $field109->uitype = 33;
    $field109->summaryfield = 1;
    $field109->typeofdata = 'V~O';
    $block->addField($field109);
    $field109->setPicklistValues(array('Altramuces', 'Ápio', 'Cacahuete', 'Crustáceo', 'Dióxido de azufre y sulfitos', 'Frutos de cáscara', 
        'Gluten', 'Granos de sésamo', 'Lácteos', 'Huevos', 'Moluscos', 'Mostaza', 'Pescado', 'Soja'));
    
    // Foto
    $field108 = new Vtiger_Field();
    $field108->name = 'foto_element';
    $field108->column = 'foto_element';
    $field108->label = 'LBL_'.strtoupper($moduleInstance->name).'_FOTO';
    $field108->columntype = 'VARCHAR(200)';
    $field108->uitype = 901;
    $field108->typeofdata = 'V~O';
    $block->addField($field108);
    
    // Order
    $field113 = new Vtiger_Field();
    $field113->name = 'order_element';
    $field113->column = 'order_element';
    $field113->label = 'LBL_'.strtoupper($moduleInstance->name).'_ORDER_ELEMENT';
    $field113->columntype = 'NUMBER';
    $field113->uitype = 1;
    $field113->typeofdata = 'N~O';
    $field113->columntype = 'INT(11)';
    $block->addField($field113);
    
    // BLOQUE PRECIOS ADICIONALES
    $block2 = new Vtiger_Block();
    $block2->label = 'LBL_'.strtoupper($moduleInstance->name).'_PRECIOS_ADICIONALES';
    $moduleInstance->addBlock($block2);
    
    // creamos 3 secciones de precio adicional
    for ($i = 1; $i <= 3; $i++)
    {
        // Title
        $field111 = new Vtiger_Field();
        $field111->name = 'title_price_additional'.$i;
        $field111->column = 'title_price_additional'.$i;
        $field111->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE'.$i;
        $field111->columntype = 'VARCHAR(100)';
        $field111->uitype = 15;
        $field111->typeofdata = 'V~O';
        $block2->addField($field111);
        $field111->setPicklistValues( Array ('Imperial 6L', 'Jeroboam 4,5L', 'Doble Magnum 3L', 'Magnum 1,5l', 'Media Botella', 'Copa', 'Tapa', 'Media ración', 'Botella 20cl', 'Botella 33cl', 'Botella 3/8', 'Botella 50cl', 'Botella 75cl', 'Caña', '1L', '1/2L') );
        
        // Price
        $field112 = new Vtiger_Field();
        $field112->name = 'price_additional'.$i;
        $field112->column = 'price_additional'.$i;
        $field112->label = 'LBL_'.strtoupper($moduleInstance->name).'_PRICE'.$i;
        $field112->columntype = 'DECIMAL(5,2)';
        $field112->uitype = 7;
        $field112->typeofdata = 'NN~O';
        $block2->addField($field112);
    }
    
    
    
    // BLOQUE LANGUAGE 01
    $block3 = new Vtiger_Block();
    $block3->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE01';
    $moduleInstance->addBlock($block3);
    
    // Language
    $field105 = new Vtiger_Field();
    $field105->name = 'language_01';
    $field105->column = 'language_01';
    $field105->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field105->columntype = 'VARCHAR(255)';
    $field105->uitype = 15;
    $field105->typeofdata = 'V~O';
    $field105->displaytype = 1;
    $field105->defaultvalue = 'spanish';
    $block3->addField($field105);
    $field105->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field106 = new Vtiger_Field();
    $field106->name = 'title_01';
    $field106->column = 'title_01';
    $field106->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field106->columntype = 'VARCHAR(100)';
    $field106->uitype = 1;
    $field106->summaryfield = 1;
    $field106->typeofdata = 'V~O';
    $block3->addField($field106);
    
    // Description
    $field107 = new Vtiger_Field();
    $field107->name = 'description_01';
    $field107->column = 'description_01';
    $field107->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field107->columntype = 'TEXT';
    $field107->uitype = 21;
    $field107->typeofdata = 'V~O';
    $block3->addField($field107);
    
    
    // BLOQUE LANGUAGE 02
    $block4 = new Vtiger_Block();
    $block4->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE02';
    $moduleInstance->addBlock($block4);
    
    // Language
    $field205 = new Vtiger_Field();
    $field205->name = 'language_02';
    $field205->column = 'language_02';
    $field205->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field205->columntype = 'VARCHAR(255)';
    $field205->uitype = 15;
    $field205->typeofdata = 'V~O';
    $field205->displaytype = 1;
    $field205->defaultvalue = 'english';
    $block4->addField($field205);
    $field205->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field206 = new Vtiger_Field();
    $field206->name = 'title_02';
    $field206->column = 'title_02';
    $field206->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field206->columntype = 'VARCHAR(100)';
    $field206->uitype = 1;
    $field206->typeofdata = 'V~O';
    $block4->addField($field206);
    
    // Description
    $field207 = new Vtiger_Field();
    $field207->name = 'description_02';
    $field207->column = 'description_02';
    $field207->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field207->columntype = 'TEXT';
    $field207->uitype = 21;
    $field207->typeofdata = 'V~O';
    $block4->addField($field207);
    
    
    // BLOQUE LANGUAGE 03
    $block5 = new Vtiger_Block();
    $block5->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE03';
    $moduleInstance->addBlock($block5);
    
    // Language
    $field305 = new Vtiger_Field();
    $field305->name = 'language_03';
    $field305->column = 'language_03';
    $field305->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field305->columntype = 'VARCHAR(255)';
    $field305->uitype = 15;
    $field305->typeofdata = 'V~O';
    $field305->displaytype = 1;
    $field305->defaultvalue = 'german';
    $block5->addField($field305);
    $field305->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field306 = new Vtiger_Field();
    $field306->name = 'title_03';
    $field306->column = 'title_03';
    $field306->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field306->columntype = 'VARCHAR(100)';
    $field306->uitype = 1;
    $field306->typeofdata = 'V~O';
    $block5->addField($field306);
    
    // Description
    $field307 = new Vtiger_Field();
    $field307->name = 'description_03';
    $field307->column = 'description_03';
    $field307->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field307->columntype = 'TEXT';
    $field307->uitype = 21;
    $field307->typeofdata = 'V~O';
    $block5->addField($field307);
    
    
    //BLOQUE LANGUAGE 04
    $block6 = new Vtiger_Block();
    $block6->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE04';
    $moduleInstance->addBlock($block6);
    
    // Language
    $field405 = new Vtiger_Field();
    $field405->name = 'language_04';
    $field405->column = 'language_04';
    $field405->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field405->columntype = 'VARCHAR(255)';
    $field405->uitype = 15;
    $field405->typeofdata = 'V~O';
    $field405->displaytype = 1;
    $field405->defaultvalue = 'french';
    $block6->addField($field405);
    $field405->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field406 = new Vtiger_Field();
    $field406->name = 'title_04';
    $field406->column = 'title_04';
    $field406->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field406->columntype = 'VARCHAR(100)';
    $field406->uitype = 1;
    $field406->typeofdata = 'V~O';
    $block6->addField($field406);
    
    // Description
    $field407 = new Vtiger_Field();
    $field407->name = 'description_04';
    $field407->column = 'description_04';
    $field407->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field407->columntype = 'TEXT';
    $field407->uitype = 21;
    $field407->typeofdata = 'V~O';
    $block6->addField($field407);
    
    
    //BLOQUE LANGUAGE 05
    $block7 = new Vtiger_Block();
    $block7->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE05';
    $moduleInstance->addBlock($block7);
    
    // Language
    $field505 = new Vtiger_Field();
    $field505->name = 'language_05';
    $field505->column = 'language_05';
    $field505->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field505->columntype = 'VARCHAR(255)';
    $field505->uitype = 15;
    $field505->typeofdata = 'V~O';
    $field505->displaytype = 1;
    $field505->defaultvalue = 'italian';
    $block7->addField($field505);
    $field505->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field506 = new Vtiger_Field();
    $field506->name = 'title_05';
    $field506->column = 'title_05';
    $field506->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field506->columntype = 'VARCHAR(100)';
    $field506->uitype = 1;
    $field506->typeofdata = 'V~O';
    $block7->addField($field506);
    
    // Description
    $field507 = new Vtiger_Field();
    $field507->name = 'description_05';
    $field507->column = 'description_05';
    $field507->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field507->columntype = 'TEXT';
    $field507->uitype = 21;
    $field507->typeofdata = 'V~O';
    $block7->addField($field507);
    
    
    //BLOQUE LANGUAGE 06
    $block8 = new Vtiger_Block();
    $block8->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE06';
    $moduleInstance->addBlock($block8);
    
    // Language
    $field605 = new Vtiger_Field();
    $field605->name = 'language_06';
    $field605->column = 'language_06';
    $field605->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field605->columntype = 'VARCHAR(255)';
    $field605->uitype = 15;
    $field605->typeofdata = 'V~O';
    $field605->displaytype = 1;
    $field605->defaultvalue = 'russian';
    $block8->addField($field605);
    $field605->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field606 = new Vtiger_Field();
    $field606->name = 'title_06';
    $field606->column = 'title_06';
    $field606->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field606->columntype = 'VARCHAR(100)';
    $field606->uitype = 1;
    $field606->typeofdata = 'V~O';
    $block8->addField($field606);
    
    // Description
    $field607 = new Vtiger_Field();
    $field607->name = 'description_06';
    $field607->column = 'description_06';
    $field607->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field607->columntype = 'TEXT';
    $field607->uitype = 21;
    $field607->typeofdata = 'V~O';
    $block8->addField($field607);
    
    
    //BLOQUE LANGUAGE 07
    $block9 = new Vtiger_Block();
    $block9->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE07';
    $moduleInstance->addBlock($block9);
    
    // Language
    $field705 = new Vtiger_Field();
    $field705->name = 'language_07';
    $field705->column = 'language_07';
    $field705->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field705->columntype = 'VARCHAR(255)';
    $field705->uitype = 15;
    $field705->typeofdata = 'V~O';
    $field705->displaytype = 1;
    $field705->defaultvalue = 'portuguese';
    $block9->addField($field705);
    $field705->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field706 = new Vtiger_Field();
    $field706->name = 'title_07';
    $field706->column = 'title_07';
    $field706->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field706->columntype = 'VARCHAR(100)';
    $field706->uitype = 1;
    $field706->typeofdata = 'V~O';
    $block9->addField($field706);
    
    // Description
    $field707 = new Vtiger_Field();
    $field707->name = 'description_07';
    $field707->column = 'description_07';
    $field707->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field707->columntype = 'TEXT';
    $field707->uitype = 21;
    $field707->typeofdata = 'V~O';
    $block9->addField($field707);
    
    
    // BLOQUE CONTACT INFO
    $block10 = new Vtiger_Block();
    $block10->label = 'LBL_'.strtoupper($moduleInstance->name).'_SYSTEM';
    $moduleInstance->addBlock($block10);
    
    
    
    
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block10->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block10->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block10->addField($mfield3);
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field100)->addField($field106, 1)->addField($field102, 2)->addField($field103, 3)->addField($field104, 4);
    
    //  tbMenus Elements
    $moduleIns = Vtiger_Module::getInstance('tbMenus');
    $relatedModule = Vtiger_Module::getInstance($moduleInstance->name);
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_TBMENUS_ELEMENTS';
    $moduleIns->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field100->id);
    
    // tbCategory
    $moduleIns = Vtiger_Module::getInstance('tbMenusCategory');
    $relatedModule = Vtiger_Module::getInstance($moduleInstance->name);
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_TBMENUS_ELEMENTS2';
    $moduleIns->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field101->id);

    // inicializamos el menu
    
    $v_query = "SELECT (MAX(sequence) + 1) as num FROM vtiger_app2tab WHERE appname LIKE ?";
    $v_res = $adb->pquery($v_query, array(strtoupper($moduleInstance->parent)));
    $v_sequence = 1;
    if ($adb->num_rows($v_res) > 0)
    {
        $v_sequence = $adb->query_result($v_res, 0, 'num');
    }
    
    $v_query = "INSERT INTO `vtiger_app2tab` (`tabid`, `appname`, `sequence`, `visible`) VALUES ((SELECT tabid FROM vtiger_tab WHERE name LIKE ?), ?, ?, '1')";
    $v_res = $adb->pquery($v_query, array(
        $MODULENAME,
        strtoupper($moduleInstance->parent),
        $v_sequence
    ));
    
    // Sharing Access Setup
    $moduleInstance->setDefaultSharing();
    
    // Webservice Setup
    $moduleInstance->initWebservice();
    
    $v_dir = 'modules/'.$MODULENAME;
    if (!file_exists($root_directory.$v_dir))
    {
        mkdir('modules/'.$MODULENAME);
    }
    echo "OK\n";
}