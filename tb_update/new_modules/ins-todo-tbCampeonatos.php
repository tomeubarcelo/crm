<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbCampeonatos';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance) {
    echo "Module already present - choose a different name.";
} else {
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent= 'Marketing';
    $moduleInstance->save();
    
    // Schema Setup
    $moduleInstance->initTables();
    
    // Field Setup
    $block = new Vtiger_Block();
    $block->label = 'LBL_'. strtoupper($moduleInstance->name) . '_INFORMATION';
    $moduleInstance->addBlock($block);
    
    $field99 = new Vtiger_Field();
    $field99->name = strtolower($moduleInstance->name).'no'; // change fieldname
    $field99->label = 'LBL_'.strtoupper($moduleInstance->name).'_NR';
    $field99->column = strtolower($moduleInstance->name).'no'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"CMP",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);
    
    
    // Nombre del CAMPEONATO
    $field100 = new Vtiger_Field();
    $field100->name = 'name';
    $field100->column = 'name';
    $field100->label = 'LBL_'.strtoupper($moduleInstance->name).'_NAME';
    $field100->columntype = 'VARCHAR(255)';
    $field100->uitype = 2;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block->addField($field100);
    
    // LUGAR
    $field150 = new Vtiger_Field();
    $field150->name = 'lugar';
    $field150->column = 'lugar';
    $field150->label = 'LBL_'.strtoupper($moduleInstance->name).'_LUGAR';
    $field150->columntype = 'VARCHAR(255)';
    $field150->summaryfield = 1;
    $field150->uitype = 1;
    $field150->typeofdata = 'V~O';
    $block->addField($field150);
    
    // FECHA CAMPEONATO
    $field101 = new Vtiger_Field();
    $field101->name = 'fecha_campeonato';
    $field101->column = 'fecha_campeonato';
    $field101->label = 'LBL_'.strtoupper($moduleInstance->name).'_FECHA_CAMPEONATO';
    $field101->columntype = 'DATE';
    $field101->summaryfield = 1;
    $field101->uitype = 5;
    $field101->displaytype = 2;
    $field101->typeofdata = 'D~O';
    $block->addField($field101);
    
    // FECHA FIN INSCRIPCIONES
    $field102 = new Vtiger_Field();
    $field102->name = 'fecha_fin_inscripciones';
    $field102->column = 'fecha_fin_inscripciones';
    $field102->label = 'LBL_'.strtoupper($moduleInstance->name).'_FECHA_FIN_INSCRIPCION';
    $field102->columntype = 'DATE';
    $field102->summaryfield = 1;
    $field102->uitype = 5;
    $field102->displaytype = 2;
    $field102->typeofdata = 'D~O';
    $block->addField($field102);
    
    // DISCIPLINAS MULTISELECCION
    $field103 = new Vtiger_Field();
    $field103->name = 'disciplinas';
    $field103->column = 'disciplinas';
    $field103->label = 'LBL_'.strtoupper($moduleInstance->name).'_DISCIPLINAS';
    $field103->columntype = 'VARCHAR(255)';
    $field103->uitype = 33;
    $field103->typeofdata = 'V~O';
    $block->addField($field103);
    $field103->setPicklistValues( Array ('Taekwondo', 'Hapkido') );
        
    // GRADOS MULTISELECCION
    $field151 = new Vtiger_Field();
    $field151->name = 'grados';
    $field151->column = 'grados';
    $field151->label = 'LBL_'.strtoupper($moduleInstance->name).'_GRADOS';
    $field151->columntype = 'VARCHAR(255)';
    $field151->uitype = 33;
    $field151->typeofdata = 'V~O';
    $block->addField($field151);
    $field151->setPicklistValues( Array ('Blanco', 'Blanco Amarillo', 'Amarillo', 'Amarillo Verde', 'Verde', 'Verde Azul', 'Azul', 'Azul Rojo', 'Rojo', 'Rojo Negro', 'Negro') );
    
    // EDAD MÍNIMA
    $field104 = new Vtiger_Field();
    $field104->name = 'edad_minima';
    $field104->column = 'edad_minima';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_EDAD_MIN';
    $field104->columntype = 'DECIMAL(10,0)';
    $field104->uitype = 7;
    $field104->typeofdata = 'NN~O~10,0';
    $block->addField($field104);
    
    // EDAD MÁXIMA
    $field105 = new Vtiger_Field();
    $field105->name = 'edad_maxima';
    $field105->column = 'edad_maxima';
    $field105->label = 'LBL_'.strtoupper($moduleInstance->name).'_EDAD_MAX';
    $field105->columntype = 'DECIMAL(10,0)';
    $field105->uitype = 7;
    $field105->typeofdata = 'NN~O~10,0';
    $block->addField($field105);
    
    // bloque 2
    
    $block2 = new Vtiger_Block();
    $block2->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPCION';
    $moduleInstance->addBlock($block2);
    
    // DESCRIPCIÓN
    $field200 = new Vtiger_Field();
    $field200->name = 'cmp_descripcion';
    $field200->column = 'cmp_descripcion';
    $field200->label = 'LBL_'.strtoupper($moduleInstance->name).'_DESCRIPCION';
    $field200->columntype = 'TEXT';
    $field200->uitype = 19;
    $field120->displaytype = 2;
    $field200->typeofdata = 'V~O';
    $block2->addField($field200);
    
    
    // Recommended common fields every Entity module should have (linked to core table)
    $mfield1 = new Vtiger_Field();
    $mfield1->name = 'assigned_user_id';
    $mfield1->label = 'Assigned To';
    $mfield1->table = 'vtiger_crmentity';
    $mfield1->column = 'smownerid';
    $mfield1->uitype = 53;
    $mfield1->typeofdata = 'V~M';
    $block->addField($mfield1);
    
    $mfield2 = new Vtiger_Field();
    $mfield2->name = 'createdtime';
    $mfield2->label= 'Created Time';
    $mfield2->table = 'vtiger_crmentity';
    $mfield2->column = 'createdtime';
    $mfield2->uitype = 70;
    $mfield2->typeofdata = 'DT~O';
    $mfield2->displaytype= 2;
    $block->addField($mfield2);
    
    $mfield3 = new Vtiger_Field();
    $mfield3->name = 'modifiedtime';
    $mfield3->label= 'Modified Time';
    $mfield3->table = 'vtiger_crmentity';
    $mfield3->column = 'modifiedtime';
    $mfield3->uitype = 70;
    $mfield3->typeofdata = 'DT~O';
    $mfield3->displaytype= 2;
    $block->addField($mfield3);
    
    
    // Filter Setup
    $filter1 = new Vtiger_Filter();
    $filter1->name = 'All';
    $filter1->isdefault = true;
    $moduleInstance->addFilter($filter1);
    $filter1->addField($field99)->addField($field100, 1)->addField($field150, 2)->addField($field101, 3)->addField($field102, 4)->addField($field103, 5)->addField($field151, 6);
    
    // Creamos las relaciones entre los módulos 1:N
    
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