<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbqr';

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
    $block0->label = 'LBL_'. strtoupper($moduleInstance->name) . '_INFORMATION';
    $moduleInstance->addBlock($block0);
    
    
    //     Nombre
    $field01 = new Vtiger_Field();
    $field01->name = 'title_qr';
    $field01->column = $field01->name;
    $field01->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field01->name);
    $field01->columntype = 'VARCHAR(50)';
    $field01->uitype = 2;
    $field01->summaryfield = 1;
    $field01->typeofdata = 'V~M';
    $block0->addField($field01);
    
    //     Tipo [URL | VCARD | PDF | HTML ]
    $field02 = new Vtiger_Field();
    $field02->name = 'tipo_qr';
    $field02->column = $field02->name;
    $field02->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field02->name);
    $field02->columntype = 'VARCHAR(255)';
    $field02->uitype = 15;
    $field02->summaryfield = 1;
    $field02->typeofdata = 'V~M';
    $block0->addField($field02);
    $field02->setPicklistValues( Array ('URL', 'VCARD', 'PDF', 'HTML' ) );
    
    // token
    $field03 = new Vtiger_Field();
    $field03->name = 'token';
    $field03->column = $field03->name;
    $field03->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field03->name);
    $field03->columntype = 'VARCHAR(64)';
    $field03->uitype = 1;
    $field03->displaytype = 2;
    $field03->typeofdata = 'V~O';
    $block0->addField($field03);
    
    // URL generada
    $field04 = new Vtiger_Field();
    $field04->name = 'url_generada';
    $field04->column = $field04->name;
    $field04->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field04->name);
    $field04->columntype = 'VARCHAR(255)';
    $field04->uitype = 17;
    $field04->displaytype = 2;
    $field04->typeofdata = 'V~O';
    $block0->addField($field04);
    
    
    
    // Bloque URL
    $block1 = new Vtiger_Block();
    $block1->label = 'LBL_'. strtoupper($moduleInstance->name) . '_URL';
    $moduleInstance->addBlock($block1);
    
    
    //     URL → text
    $field11 = new Vtiger_Field();
    $field11->name = 'qr_url';
    $field11->column = $field11->name;
    $field11->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field11->name);
    $field11->columntype = 'TEXT';
    $field11->uitype = 17;
    $field11->displaytype = 1;
    $field11->typeofdata = 'V~O';
    $block1->addField($field11);
    
    
    //     Bloque VCARD
    $block2 = new Vtiger_Block();
    $block2->label = 'LBL_'. strtoupper($moduleInstance->name) . '_VCARD';
    $moduleInstance->addBlock($block2);
    
    
    //     Nombre → varchar(50)
    $field21 = new Vtiger_Field();
    $field21->name = 'nombre';
    $field21->column = $field21->name;
    $field21->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field21->name);
    $field21->columntype = 'VARCHAR(50)';
    $field21->uitype = 1;
    $field21->summaryfield = 1;
    $field21->typeofdata = 'V~O';
    $block2->addField($field21);
    
    //     Apellidos → varchar(50)
    $field22 = new Vtiger_Field();
    $field22->name = 'apellidos';
    $field22->column = $field22->name;
    $field22->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field22->name);
    $field22->columntype = 'VARCHAR(50)';
    $field22->uitype = 1;
    $field22->summaryfield = 1;
    $field22->typeofdata = 'V~O';
    $block2->addField($field22);
    
    //     Teléfono móvil → varchar(20)
    $field23 = new Vtiger_Field();
    $field23->name = 'mobile';
    $field23->column = $field23->name;
    $field23->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field23->name);
    $field23->columntype = 'VARCHAR(20)';
    $field23->uitype = 1;
    $field23->summaryfield = 1;
    $field23->typeofdata = 'V~O';
    $block2->addField($field23);
    
    //     Teléfono fijo → varchar(20)
    $field24 = new Vtiger_Field();
    $field24->name = 'phone';
    $field24->column = $field24->name;
    $field24->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field24->name);
    $field24->columntype = 'VARCHAR(20)';
    $field24->uitype = 1;
    $field24->summaryfield = 1;
    $field24->typeofdata = 'V~O';
    $block2->addField($field24);
    
    //     Fax → varchar(20)
    $field25 = new Vtiger_Field();
    $field25->name = 'fax';
    $field25->column = $field25->name;
    $field25->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field25->name);
    $field25->columntype = 'VARCHAR(20)';
    $field25->uitype = 1;
    $field25->summaryfield = 1;
    $field25->typeofdata = 'V~O';
    $block2->addField($field25);
    
    //     email → varchar(255)
    $field26 = new Vtiger_Field();
    $field26->name = 'email';
    $field26->column = $field26->name;
    $field26->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field26->name);
    $field26->columntype = 'VARCHAR(255)';
    $field26->uitype = 13;
    $field26->summaryfield = 1;
    $field26->typeofdata = 'E~O';
    $block2->addField($field26);
    
    //     empresa → varchar(50)
    $field27 = new Vtiger_Field();
    $field27->name = 'company';
    $field27->column = $field27->name;
    $field27->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field27->name);
    $field27->columntype = 'VARCHAR(50)';
    $field27->uitype = 1;
    $field27->summaryfield = 1;
    $field27->typeofdata = 'V~O';
    $block2->addField($field27);
    
    //     puesto → varchar(50)
    $field28 = new Vtiger_Field();
    $field28->name = 'cargo';
    $field28->column = $field28->name;
    $field28->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field28->name);
    $field28->columntype = 'VARCHAR(50)';
    $field28->uitype = 1;
    $field28->summaryfield = 1;
    $field28->typeofdata = 'V~O';
    $block2->addField($field28);
    
    //     dirección → text
    $field29 = new Vtiger_Field();
    $field29->name = 'address';
    $field29->column = $field29->name;
    $field29->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field29->name);
    $field29->columntype = 'VARCHAR(255)';
    $field29->uitype = 1;
    $field29->summaryfield = 1;
    $field29->typeofdata = 'V~O';
    $block2->addField($field29);
    
    //     ciudad  → varchar(50)
    $field201 = new Vtiger_Field();
    $field201->name = 'city';
    $field201->column = $field201->name;
    $field201->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field201->name);
    $field201->columntype = 'VARCHAR(50)';
    $field201->uitype = 1;
    $field201->summaryfield = 1;
    $field201->typeofdata = 'V~O';
    $block2->addField($field201);
    
    //     codigo postal → varchar(20)
    $field202 = new Vtiger_Field();
    $field202->name = 'cp';
    $field202->column = $field202->name;
    $field202->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field202->name);
    $field202->columntype = 'VARCHAR(20)';
    $field202->uitype = 1;
    $field202->summaryfield = 1;
    $field202->typeofdata = 'V~O';
    $block2->addField($field202);
    
    //     estado → varchar(50)
    $field203 = new Vtiger_Field();
    $field203->name = 'state';
    $field203->column = $field203->name;
    $field203->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field203->name);
    $field203->columntype = 'VARCHAR(50)';
    $field203->uitype = 1;
    $field203->summaryfield = 1;
    $field203->typeofdata = 'V~O';
    $block2->addField($field203);
    
    //     pais → varchar(50)
    $field204 = new Vtiger_Field();
    $field204->name = 'country';
    $field204->column = $field204->name;
    $field204->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field204->name);
    $field204->columntype = 'VARCHAR(50)';
    $field204->uitype = 1;
    $field204->summaryfield = 1;
    $field204->typeofdata = 'V~O';
    $block2->addField($field204);
    
    //     sitio web → varchar(255)
    $field205 = new Vtiger_Field();
    $field205->name = 'website';
    $field205->column = $field205->name;
    $field205->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field205->name);
    $field205->columntype = 'VARCHAR(255)';
    $field205->uitype = 17;
    $field205->summaryfield = 1;
    $field205->typeofdata = 'V~O';
    $block2->addField($field205);
    
    //     Bloque PDF
    $block5 = new Vtiger_Block();
    $block5->label = 'LBL_'. strtoupper($moduleInstance->name) . '_PDF';
    $moduleInstance->addBlock($block5);
    
    
    //     fichero → uitype 901
    $field51 = new Vtiger_Field();
    $field51->name = 'file';
    $field51->column = $field51->name;
    $field51->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field51->name);
    $field51->columntype = 'VARCHAR(255)';
    $field51->uitype = 901;
    $field51->typeofdata = 'V~O';
    $block5->addField($field51);
    
    //     Bloque HTML
    $block6 = new Vtiger_Block();
    $block6->label = 'LBL_'. strtoupper($moduleInstance->name) . '_HTML';
    $moduleInstance->addBlock($block6);
    
    
    //     titulo → varchar(100)
    $field61 = new Vtiger_Field();
    $field61->name = 'title_html';
    $field61->column = $field61->name;
    $field61->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field61->name);
    $field61->columntype = 'VARCHAR(255)';
    $field61->uitype = 1;
    $field61->summaryfield = 1;
    $field61->typeofdata = 'V~O';
    $block6->addField($field61);
    
    //     html → text (html)
    $field62 = new Vtiger_Field();
    $field62->name = 'html';
    $field62->column = $field62->name;
    $field62->label = 'LBL_'.strtoupper($moduleInstance->name).'_'.strtoupper($field62->name);
    $field62->columntype = 'TEXT';
    $field62->uitype = 21;
    $field62->displaytype = 1;
    $field62->typeofdata = 'V~O';
    $block6->addField($field62);
    
    //     Bloque HTML
    $block7 = new Vtiger_Block();
    $block7->label = 'LBL_'. strtoupper($moduleInstance->name) . '_SYSTEM_INFORMATION';
    $moduleInstance->addBlock($block7);
    
    
    
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block7->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block7->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block7->addField($mfield3);
    
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field01)->addField($field02, 1)->addField($field11, 2)->addField($mfield1, 3);
    
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