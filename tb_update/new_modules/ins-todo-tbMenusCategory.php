<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbMenusCategory';

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
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"MC",1); // change ENR to your sequence prefix
    
    // Menu
    $field100 = new Vtiger_Field();
    $field100->name = 'menu_cat';
    $field100->column = 'menu_cat';
    $field100->label = 'LBL_'.strtoupper($moduleInstance->name).'_MENU';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block->addField($field100);
    $field100->setRelatedModules(Array('tbMenus'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);

    // Name Category
    $field101 = new Vtiger_Field();
    $field101->name = 'category';
    $field101->column = 'category';
    $field101->label = 'LBL_'.strtoupper($moduleInstance->name).'_CATEGORY';
    $field101->columntype = 'VARCHAR(255)';
    $field101->uitype = 1;
    $field101->summaryfield = 1;
    $field101->typeofdata = 'V~M';
    $block->addField($field101);
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field101);
    
    // Active
    $field104 = new Vtiger_Field();
    $field104->name = 'active';
    $field104->column = 'active';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_ACTIVE';
    $field104->columntype = 'VARCHAR(255)';
    $field104->uitype = 56;
    $field104->summaryfield = 1;
    $field104->typeofdata = 'C~M';
    $field104->defaultvalue = true;
    $block->addField($field104);
    
    // Order
    $field102 = new Vtiger_Field();
    $field102->name = 'order_category';
    $field102->column = 'order_category';
    $field102->label = 'LBL_'.strtoupper($moduleInstance->name).'_ORDER_CATEGORY';
    $field102->columntype = 'NUMBER';
    $field102->uitype = 1;
    $field102->typeofdata = 'N~O';
    $field102->columntype = 'INT(11)';
    $block->addField($field102);
    
    // Foto
    $field1108 = new Vtiger_Field();
    $field1108->name = 'foto';
    $field1108->column = 'foto';
    $field1108->label = 'LBL_'.strtoupper($moduleInstance->name).'_IMAGE';
    $field1108->columntype = 'VARCHAR(200)';
    $field1108->uitype = 901;
    $field1108->typeofdata = 'V~O';
    $block->addField($field1108);
    
    
    // BLOQUE LANGUAGE 01
    $block2 = new Vtiger_Block();
    $block2->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE01';
    $moduleInstance->addBlock($block2);
    
    // Language
    $field105 = new Vtiger_Field();
    $field105->name = 'cat_language_01';
    $field105->column = 'cat_language_01';
    $field105->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field105->columntype = 'VARCHAR(255)';
    $field105->uitype = 15;
    $field105->typeofdata = 'V~O';
    $field105->displaytype = 1;
    $field105->defaultvalue = 'spanish';
    $block2->addField($field105);
    $field105->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field106 = new Vtiger_Field();
    $field106->name = 'cat_title_01';
    $field106->column = 'cat_title_01';
    $field106->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field106->columntype = 'VARCHAR(100)';
    $field106->uitype = 1;
    $field106->typeofdata = 'V~O';
    $block2->addField($field106);
    
    // Description
    $field107 = new Vtiger_Field();
    $field107->name = 'cat_description_01';
    $field107->column = 'cat_description_01';
    $field107->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field107->columntype = 'TEXT';
    $field107->uitype = 21;
    $field107->typeofdata = 'V~O';
    $block2->addField($field107);
    
    // Subtitle
    $field108 = new Vtiger_Field();
    $field108->name = 'cat_subtitle_01';
    $field108->column = 'cat_subtitle_01';
    $field108->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field108->columntype = 'TEXT';
    $field108->uitype = 1;
    $field108->typeofdata = 'V~O';
    $block2->addField($field108);
    
    
    // BLOQUE LANGUAGE 02
    $block3 = new Vtiger_Block();
    $block3->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE02';
    $moduleInstance->addBlock($block3);
    
    // Language
    $field205 = new Vtiger_Field();
    $field205->name = 'cat_language_02';
    $field205->column = 'cat_language_02';
    $field205->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field205->columntype = 'VARCHAR(255)';
    $field205->uitype = 15;
    $field205->typeofdata = 'V~O';
    $field205->displaytype = 1;
    $field205->defaultvalue = 'english';
    $block3->addField($field205);
    $field205->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field206 = new Vtiger_Field();
    $field206->name = 'cat_title_02';
    $field206->column = 'cat_title_02';
    $field206->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field206->columntype = 'VARCHAR(100)';
    $field206->uitype = 1;
    $field206->typeofdata = 'V~O';
    $block3->addField($field206);
    
    // Description
    $field207 = new Vtiger_Field();
    $field207->name = 'cat_description_02';
    $field207->column = 'cat_description_02';
    $field207->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field207->columntype = 'TEXT';
    $field207->uitype = 21;
    $field207->typeofdata = 'V~O';
    $block3->addField($field207);
    
    // Subtitle
    $field208 = new Vtiger_Field();
    $field208->name = 'cat_subtitle_02';
    $field208->column = 'cat_subtitle_02';
    $field208->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field208->columntype = 'TEXT';
    $field208->uitype = 1;
    $field208->typeofdata = 'V~O';
    $block3->addField($field208);
    
    
    // BLOQUE LANGUAGE 03
    $block4 = new Vtiger_Block();
    $block4->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE03';
    $moduleInstance->addBlock($block4);
    
    // Language
    $field305 = new Vtiger_Field();
    $field305->name = 'cat_language_03';
    $field305->column = 'cat_language_03';
    $field305->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field305->columntype = 'VARCHAR(255)';
    $field305->uitype = 15;
    $field305->typeofdata = 'V~O';
    $field305->displaytype = 1;
    $field305->defaultvalue = 'german';
    $block4->addField($field305);
    $field305->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field306 = new Vtiger_Field();
    $field306->name = 'cat_title_03';
    $field306->column = 'cat_title_03';
    $field306->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field306->columntype = 'VARCHAR(100)';
    $field306->uitype = 1;
    $field306->typeofdata = 'V~O';
    $block4->addField($field306);
    
    // Description
    $field307 = new Vtiger_Field();
    $field307->name = 'cat_description_03';
    $field307->column = 'cat_description_03';
    $field307->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field307->columntype = 'TEXT';
    $field307->uitype = 21;
    $field307->typeofdata = 'V~O';
    $block4->addField($field307);
    
    // Subtitle
    $field308 = new Vtiger_Field();
    $field308->name = 'cat_subtitle_03';
    $field308->column = 'cat_subtitle_03';
    $field308->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field308->columntype = 'TEXT';
    $field308->uitype = 1;
    $field308->typeofdata = 'V~O';
    $block4->addField($field308);
    
    
    //BLOQUE LANGUAGE 04
    $block5 = new Vtiger_Block();
    $block5->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE04';
    $moduleInstance->addBlock($block5);
    
    // Language
    $field405 = new Vtiger_Field();
    $field405->name = 'cat_language_04';
    $field405->column = 'cat_language_04';
    $field405->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field405->columntype = 'VARCHAR(255)';
    $field405->uitype = 15;
    $field405->typeofdata = 'V~O';
    $field405->displaytype = 1;
    $field405->defaultvalue = 'french';
    $block5->addField($field405);
    $field405->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field406 = new Vtiger_Field();
    $field406->name = 'cat_title_04';
    $field406->column = 'cat_title_04';
    $field406->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field406->columntype = 'VARCHAR(100)';
    $field406->uitype = 1;
    $field406->typeofdata = 'V~O';
    $block5->addField($field406);
    
    // Description
    $field407 = new Vtiger_Field();
    $field407->name = 'cat_description_04';
    $field407->column = 'cat_description_04';
    $field407->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field407->columntype = 'TEXT';
    $field407->uitype = 21;
    $field407->typeofdata = 'V~O';
    $block5->addField($field407);
    
    // Subtitle
    $field408 = new Vtiger_Field();
    $field408->name = 'cat_subtitle_04';
    $field408->column = 'cat_subtitle_04';
    $field408->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field408->columntype = 'TEXT';
    $field408->uitype = 1;
    $field408->typeofdata = 'V~O';
    $block5->addField($field408);
    
    
    //BLOQUE LANGUAGE 05
    $block6 = new Vtiger_Block();
    $block6->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE05';
    $moduleInstance->addBlock($block6);
    
    // Language
    $field505 = new Vtiger_Field();
    $field505->name = 'cat_language_05';
    $field505->column = 'cat_language_05';
    $field505->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field505->columntype = 'VARCHAR(255)';
    $field505->uitype = 15;
    $field505->typeofdata = 'V~O';
    $field505->displaytype = 1;
    $field505->defaultvalue = 'italian';
    $block6->addField($field505);
    $field505->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field506 = new Vtiger_Field();
    $field506->name = 'cat_title_05';
    $field506->column = 'cat_title_05';
    $field506->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field506->columntype = 'VARCHAR(100)';
    $field506->uitype = 1;
    $field506->typeofdata = 'V~O';
    $block6->addField($field506);
    
    // Description
    $field507 = new Vtiger_Field();
    $field507->name = 'cat_description_05';
    $field507->column = 'cat_description_05';
    $field507->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field507->columntype = 'TEXT';
    $field507->uitype = 21;
    $field507->typeofdata = 'V~O';
    $block6->addField($field507);
    
    // Subtitle
    $field508 = new Vtiger_Field();
    $field508->name = 'cat_subtitle_05';
    $field508->column = 'cat_subtitle_05';
    $field508->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field508->columntype = 'TEXT';
    $field508->uitype = 1;
    $field508->typeofdata = 'V~O';
    $block6->addField($field508);
    
    //BLOQUE LANGUAGE 06
    $block7 = new Vtiger_Block();
    $block7->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE06';
    $moduleInstance->addBlock($block7);
    
    // Language
    $field605 = new Vtiger_Field();
    $field605->name = 'cat_language_06';
    $field605->column = 'cat_language_06';
    $field605->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field605->columntype = 'VARCHAR(255)';
    $field605->uitype = 15;
    $field605->typeofdata = 'V~O';
    $field605->displaytype = 1;
    $field605->defaultvalue = 'russian';
    $block7->addField($field605);
    $field605->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field606 = new Vtiger_Field();
    $field606->name = 'cat_title_06';
    $field606->column = 'cat_title_06';
    $field606->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field606->columntype = 'VARCHAR(100)';
    $field606->uitype = 1;
    $field606->typeofdata = 'V~O';
    $block7->addField($field606);
    
    // Description
    $field607 = new Vtiger_Field();
    $field607->name = 'cat_description_06';
    $field607->column = 'cat_description_06';
    $field607->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field607->columntype = 'TEXT';
    $field607->uitype = 21;
    $field607->typeofdata = 'V~O';
    $block7->addField($field607);
    
    // Subtitle
    $field608 = new Vtiger_Field();
    $field608->name = 'cat_subtitle_06';
    $field608->column = 'cat_subtitle_06';
    $field608->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field608->columntype = 'TEXT';
    $field608->uitype = 1;
    $field608->typeofdata = 'V~O';
    $block7->addField($field608);
    
    //BLOQUE LANGUAGE 07
    $block8 = new Vtiger_Block();
    $block8->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE07';
    $moduleInstance->addBlock($block8);
    
    // Language
    $field705 = new Vtiger_Field();
    $field705->name = 'cat_language_07';
    $field705->column = 'cat_language_07';
    $field705->label = 'LBL_'.strtoupper($moduleInstance->name).'_LANGUAGE';
    $field705->columntype = 'VARCHAR(255)';
    $field705->uitype = 15;
    $field705->typeofdata = 'V~O';
    $field705->displaytype = 1;
    $field705->defaultvalue = 'portuguese';
    $block8->addField($field705);
    $field705->setPicklistValues( Array ('english', 'german', 'spanish', 'french', 'italian', 'russian', 'portuguese') );
    
    // Title
    $field706 = new Vtiger_Field();
    $field706->name = 'cat_title_07';
    $field706->column = 'cat_title_07';
    $field706->label = 'LBL_'.strtoupper($moduleInstance->name).'_TITLE';
    $field706->columntype = 'VARCHAR(100)';
    $field706->uitype = 1;
    $field706->typeofdata = 'V~O';
    $block8->addField($field706);
    
    // Description
    $field707 = new Vtiger_Field();
    $field707->name = 'cat_description_07';
    $field707->column = 'cat_description_07';
    $field707->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPTION';
    $field707->columntype = 'TEXT';
    $field707->uitype = 21;
    $field707->typeofdata = 'V~O';
    $block8->addField($field707);
    
    // Subtitle
    $field708 = new Vtiger_Field();
    $field708->name = 'cat_subtitle_07';
    $field708->column = 'cat_subtitle_07';
    $field708->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUBTITLE';
    $field708->columntype = 'TEXT';
    $field708->uitype = 1;
    $field708->typeofdata = 'V~O';
    $block8->addField($field708);
    
    
    // BLOQUE CONTACT INFO
    $block9 = new Vtiger_Block();
    $block9->label = 'LBL_'.strtoupper($moduleInstance->name).'_SYSTEM';
    $moduleInstance->addBlock($block9);
    
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block9->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block9->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block9->addField($mfield3);
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field101)->addField($field104, 1);
    
    
    
    // Creamos las relaciones entre los módulos 1:N

    //  tbMenu
    $moduleIns = Vtiger_Module::getInstance('tbMenus');
    $relatedModule = Vtiger_Module::getInstance($moduleInstance->name);
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_TBMENUS_CATEGORY';
    $moduleIns->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field100->id);
   
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