<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbEmployees';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if (($moduleInstance) && !$v_is_test) {
    echo "Module already present - choose a different name.";
} else {
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent= 'Sales';
    $moduleInstance->save();
    
    // Schema Setup
    $moduleInstance->initTables();
    
        
    $block1 = new Vtiger_Block();
    $block1->label = 'LBL_BLOC_TBEMPLOYEES';
    $moduleInstance->addBlock($block1);    
    
    // Número de empleado
    $field99 = new Vtiger_Field();
    $field99->name = 'tbemployeeno'; // change fieldname
    $field99->label = 'LBL_EMPLOYEER_NR';
    $field99->column = 'tbemployeeno'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block1->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"EMP",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);    
    
    // Nombre
    $field100 = new Vtiger_Field();
    $field100->name = 'nombre_tbemployees';
    $field100->column = 'nombre_tbemployees';
    $field100->label = 'LBL_TBEMPLOYEES_NAME';
    $field100->columntype = 'VARCHAR(255)';
    $field100->uitype = 1;
    $field100->typeofdata = 'V~O';
    $block1->addField($field100);
    
    // Apellidos
    $field101 = new Vtiger_Field();
    $field101->name = 'apellidos_tbemployees';
    $field101->column = 'apellidos_tbemployees';
    $field101->label = 'LBL_TBEMPLOYEES_SURNAME';
    $field101->columntype = 'VARCHAR(255)';
    $field101->uitype = 1;
    $field101->typeofdata = 'V~O';
    $block1->addField($field101);
    
    // Teléfono
    $field102 = new Vtiger_Field();
    $field102->name = 'tlf_tbemployees';
    $field102->column = 'tlf_tbemployees';
    $field102->label = 'LBL_TBEMPLOYEES_TLF';
    $field102->columntype = 'VARCHAR(30)';
    $field102->uitype = 11;
    $field102->typeofdata = 'V~O~LE~30';
    $block1->addField($field102);
    
    // Email
    $field103 = new Vtiger_Field();
    $field103->name = 'email_tbemployees';
    $field103->column = 'email_tbemployees';
    $field103->label = 'LBL_TBEMPLOYEES_EMAIL';
    $field103->columntype = 'VARCHAR(100)';
    $field103->uitype = 13;
    $field103->typeofdata = 'E~O';
    $block1->addField($field103);
    
    // Dni
    $field104 = new Vtiger_Field();
    $field104->name = 'dni_tbemployees';
    $field104->column = 'dni_tbemployees';
    $field104->label = 'LBL_TBEMPLOYEES_DNI';
    $field104->columntype = 'VARCHAR(100)';
    $field104->uitype = 1;
    $field104->typeofdata = 'V~O';
    $block1->addField($field104);
    
    // Cod. de la gestoría
    $field106 = new Vtiger_Field();
    $field106->name = 'cod_gestoria_tbemployees';
    $field106->column = 'cod_gestoria_tbemployees';
    $field106->label = 'LBL_TBEMPLOYEES_COD_GESTORIA';
    $field106->columntype = 'VARCHAR(100)';
    $field106->uitype = 1;
    $field106->typeofdata = 'V~O';
    $block1->addField($field106);
                 
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block1->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block1->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block1->addField($mfield3);
    
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field100)->addField($field101)->addField($field102)->addField($field103);
    
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
    
    // revisamos que se haya creado el registro en la tabla vtiger_ws_entity
    
    $v_query = "SELECT * FROM vtiger_ws_entity WHERE name = ?";
    $v_res = $adb->pquery($v_query, array($MODULENAME));
    if ($adb->num_rows($v_res) === 0)
    {
        $v_query = "INSERT INTO `vtiger_ws_entity` (`id`, `name`, `handler_path`, `handler_class`, `ismodule`) VALUES (NULL, ?, 'include/Webservices/VtigerModuleOperation.php', 'VtigerModuleOperation', '1');";
        $adb->pquery($v_query, array($MODULENAME));
    }
    
    $v_dir = 'modules/'.$MODULENAME;
    if (!file_exists($root_directory.$v_dir))
    {
        mkdir('modules/'.$MODULENAME);
    }
    echo "OK\n";
}