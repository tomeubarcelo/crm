<?php

ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbMenus';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if (($moduleInstance) && !$v_is_test) {
    echo "Module already present - choose a different name.";
} else {
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent= 'Marketing';
    $moduleInstance->save();
    
    
    
    // Schema Setup
    $moduleInstance->initTables();
    
    
    // script block
    $block0 = new Vtiger_Block();
    $block0->label = 'LBL_'. strtoupper($moduleInstance->name) . '_EMBED_TITLE';
    $moduleInstance->addBlock($block0);
    
    // URL de la web
    $field102 = new Vtiger_Field();
    $field102->name = 'url';
    $field102->column = 'url';
    $field102->label = 'LBL_'.strtoupper($moduleInstance->name).'_URL';
    $field102->columntype = 'VARCHAR(255)';
    $field102->uitype = 17;
    $field102->displaytype = 2;
    $field102->typeofdata = 'V~O';
    $block0->addField($field102);
    //
    $field211 = new Vtiger_Field();
    $field211->name = 'embed';
    $field211->column = 'embed';
    $field211->label = 'LBL_'.strtoupper($moduleInstance->name).'_EMBED';
    $field211->columntype = 'TEXT';
    $field211->uitype = 21;
    $field211->displaytype = 2;
    $field211->typeofdata = 'V~O';
    $block0->addField($field211);
    
    
    
    // Field Setup
    $block = new Vtiger_Block();
    $block->label = 'LBL_'. strtoupper($moduleInstance->name) . '_INFORMATION';
    $moduleInstance->addBlock($block);
    
        
    // Nombre del restaurante
    $field100 = new Vtiger_Field();
    $field100->name = 'name';
    $field100->column = 'name';
    $field100->label = 'LBL_'.strtoupper($moduleInstance->name).'_NAME';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 2;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block->addField($field100);
    
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);
    
    // Type
    $field101 = new Vtiger_Field();
    $field101->name = 'type_menu';
    $field101->column = 'type_menu';
    $field101->label = 'LBL_'.strtoupper($moduleInstance->name).'_TYPE';
    $field101->columntype = 'VARCHAR(255)';
    $field101->uitype = 15;
    $field101->summaryfield = 1;
    $field101->typeofdata = 'V~M';
    $field101->defaultvalue = 'Dinámica';
    $block->addField($field101);
    $field101->setPicklistValues( Array ('PDF', 'Dinámica') );


    // BLOQUE DESIGN 
    $block2 = new Vtiger_Block();
    $block2->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESIGN';
    $moduleInstance->addBlock($block2);
    
    // Logo
    $field103 = new Vtiger_Field();
    $field103->name = 'logo';
    $field103->column = 'logo';
    $field103->label = 'LBL_'.strtoupper($moduleInstance->name).'_LOGO';
    $field103->columntype = 'VARCHAR(255)';
    $field103->uitype = 901;
    $field103->typeofdata = 'V~O';
    $block2->addField($field103);
    
    // Font family URL
    $field104 = new Vtiger_Field();
    $field104->name = 'font_family_url';
    $field104->column = 'font_family_url';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_FONT_FAMILY_URL';
    $field104->columntype = 'TEXT';
    $field104->uitype = 20;
    $field104->typeofdata = 'V~O';
    $field104->defaultvalue = '<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">';
    $block2->addField($field104);
    
    // Font family name
    $field105 = new Vtiger_Field();
    $field105->name = 'font_family_name';
    $field105->column = 'font_family_name';
    $field105->label = 'LBL_'.strtoupper($moduleInstance->name).'_FONT_FAMILY_NAME';
    $field105->columntype = 'VARCHAR(100)';
    $field105->uitype = 1;
    $field105->typeofdata = 'V~O';
    $field105->defaultvalue = "'Oswald', sans-serif";
    $block2->addField($field105);   
    
    // Text color
    $field106 = new Vtiger_Field();
    $field106->name = 'text_color';
    $field106->column = 'text_color';
    $field106->label = 'LBL_'.strtoupper($moduleInstance->name).'_TEXT_COLOR';
    $field106->columntype = 'VARCHAR(10)';
    $field106->uitype = 1;
    $field106->typeofdata = 'V~O';
    $field106->defaultvalue = '#000000';
    $block2->addField($field106);
    
    // Background color
    $field107 = new Vtiger_Field();
    $field107->name = 'background_color';
    $field107->column = 'background_color';
    $field107->label = 'LBL_'.strtoupper($moduleInstance->name).'_BACKGROUND_COLOR';
    $field107->columntype = 'VARCHAR(10)';
    $field107->uitype = 1;
    $field107->defaultvalue = '#ffffff';
    $field107->typeofdata = 'V~O';
    $block2->addField($field107);
    
    // Background image
    $field108 = new Vtiger_Field();
    $field108->name = 'background_image';
    $field108->column = 'background_image';
    $field108->label = 'LBL_'.strtoupper($moduleInstance->name).'_BACKGROUND_IMAGE';
    $field108->columntype = 'VARCHAR(255)';
    $field108->uitype = 901;
    $field108->typeofdata = 'V~O';
    $block2->addField($field108);
    
    
    // BLOQUE CONTACT INFO
    $block3 = new Vtiger_Block();
    $block3->label = 'LBL_'.strtoupper($moduleInstance->name).'_CONTACT_INFO';
    $moduleInstance->addBlock($block3);
    
    // Telephone 
    $field109 = new Vtiger_Field();
    $field109->name = 'telephone';
    $field109->column = 'telephone';
    $field109->label = 'LBL_'.strtoupper($moduleInstance->name).'_TELEPHONE';
    $field109->columntype = 'VARCHAR(255)';
    $field109->uitype = 11;
    $field109->summaryfield = 1;
    $field109->typeofdata = 'V~O';
    $block3->addField($field109);
    
    //email
    $field110 = new Vtiger_Field();
    $field110->name = 'email';
    $field110->column = 'email';
    $field110->label = 'LBL_'.strtoupper($moduleInstance->name).'_EMAIL';
    $field110->columntype = 'VARCHAR(255)';
    $field110->summaryfield = 1;
    $field110->typeofdata = 'V~O';
    $block3->addField($field110);
    
    //street
    $field111 = new Vtiger_Field();
    $field111->name = 'street';
    $field111->column = 'street';
    $field111->label = 'LBL_'.strtoupper($moduleInstance->name).'_STREET';
    $field111->columntype = 'TEXT';
    $field111->uitype = 21;
    $field111->typeofdata = 'V~O';
    $block3->addField($field111);
    
    //city
    $field112 = new Vtiger_Field();
    $field112->name = 'city';
    $field112->column = 'city';
    $field112->label = 'LBL_'.strtoupper($moduleInstance->name).'_CITY';
    $field112->columntype = 'VARCHAR(50)';
    $field112->uitype = 1;
    $field112->summaryfield = 1;
    $field112->typeofdata = 'V~O';
    $block3->addField($field112);
    
    //postal code
    $field113 = new Vtiger_Field();
    $field113->name = 'postal_code';
    $field113->column = 'postal_code';
    $field113->label = 'LBL_'.strtoupper($moduleInstance->name).'_POSTAL_CODE';
    $field113->columntype = 'VARCHAR(10)';
    $field113->uitype = 1;
    $field113->typeofdata = 'V~O';
    $block3->addField($field113);
    
    //estate
    $field114 = new Vtiger_Field();
    $field114->name = 'estate';
    $field114->column = 'estate';
    $field114->label = 'LBL_'.strtoupper($moduleInstance->name).'_ESTATE';
    $field114->columntype = 'VARCHAR(50)';
    $field114->uitype = 1;
    $field114->typeofdata = 'V~O';
    $block3->addField($field114);
    
    //country
    $field115 = new Vtiger_Field();
    $field115->name = 'country';
    $field115->column = 'country';
    $field115->label = 'LBL_'.strtoupper($moduleInstance->name).'_COUNTRY';
    $field115->columntype = 'VARCHAR(50)';
    $field115->uitype = 1;
    $field115->typeofdata = 'V~O';
    $block3->addField($field115);
    
    
    // BLOQUE CSS Y JS ADICIONAL
    $block4 = new Vtiger_Block();
    $block4->label = 'LBL_'.strtoupper($moduleInstance->name).'_CUSTOM';
    $moduleInstance->addBlock($block4);
    
    $field116 = new Vtiger_Field();
    $field116->name = 'custom_css';
    $field116->column = 'custom_css';
    $field116->label = 'LBL_'.strtoupper($moduleInstance->name).'_CUSTOM_CSS';
    $field116->columntype = 'TEXT';
    $field116->uitype = 20;
    $field116->typeofdata = 'V~O';
    $block4->addField($field116);
    
    $field117 = new Vtiger_Field();
    $field117->name = 'custom_js';
    $field117->column = 'custom_js';
    $field117->label = 'LBL_'.strtoupper($moduleInstance->name).'_CUSTOM_JS';
    $field117->columntype = 'TEXT';
    $field117->uitype = 20;
    $field117->typeofdata = 'V~O';
    $block4->addField($field117);
    
    // BLOQUE PLANTILLA
    $block5 = new Vtiger_Block();
    $block5->label = 'LBL_'.strtoupper($moduleInstance->name).'_PLANTILLA';
    $moduleInstance->addBlock($block5);
    
    $field118 = new Vtiger_Field();
    $field118->name = 'plantilla_pdf';
    $field118->column = 'plantilla_pdf';
    $field118->label = 'LBL_'.strtoupper($moduleInstance->name).'_PLANTILLA_PDF';
    $field118->columntype = 'VARCHAR(255)';
    $field118->uitype = 901;
    $field118->typeofdata = 'V~O';
    $block5->addField($field118);
    
    $field119 = new Vtiger_Field();
    $field119->name = 'pdf_json';
    $field119->column = 'pdf_json';
    $field119->label = 'LBL_'.strtoupper($moduleInstance->name).'_PDF_JSON';
    $field119->columntype = 'TEXT';
    $field119->uitype = 20;
    $field119->typeofdata = 'V~O';
    $block5->addField($field119);
    
    
    // BLOQUE CONTACT INFO
    $block6 = new Vtiger_Block();
    $block6->label = 'LBL_'.strtoupper($moduleInstance->name).'_SYSTEM';
    $moduleInstance->addBlock($block6);
    
    $field99 = new Vtiger_Field();
    $field99->name = strtolower($moduleInstance->name).'no'; // change fieldname
    $field99->label = 'LBL_'.strtoupper($moduleInstance->name).'_NR';
    $field99->column = strtolower($moduleInstance->name).'no'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block6->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"M",1); // change ENR to your sequence prefix
    
    
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block6->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block6->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block6->addField($mfield3);
    
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field100)->addField($field101, 1)->addField($field109, 2)->addField($field110, 2)->addField($field112, 2);
    
    // Sharing Access Setup
    $moduleInstance->setDefaultSharing();
    
    // Webservice Setup
    $moduleInstance->initWebservice();
    
    
    /*
     * 
     * 
     * 
     * TO DO
     * 
     * 
     * 
     */

     
     
   
     
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
    
    $v_dir = 'modules/'.$MODULENAME;
    if (!file_exists($root_directory.$v_dir))
    {
        mkdir('modules/'.$MODULENAME);
    }
    echo "OK\n";
}