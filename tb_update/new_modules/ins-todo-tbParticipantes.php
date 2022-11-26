<?php
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbParticipantes';

$moduleInstance = Vtiger_Module::getInstance($MODULENAME);
if ($moduleInstance) {
    echo "Module already present - choose a different name.";
} else {
    $moduleInstance = new Vtiger_Module();
    $moduleInstance->name = $MODULENAME;
    $moduleInstance->parent= 'Sales';
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
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"GRD",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);
    
    // CAMPEONATO
    $field100 = new Vtiger_Field();
    $field100->name = 'campeonato';
    $field100->label= 'LBL_'.strtoupper($moduleInstance->name).'_CAMPEONATO';
    $field100->column = 'campeonato';
    $field100->columntype = 'INT(19)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'I~M';
    $block->addField($field100);
    $field100->setRelatedModules(Array('tbCampeonatos'));
    
    // AFILIADO
    $field101 = new Vtiger_Field();
    $field101->name = 'afiliado';
    $field101->label= 'LBL_'.strtoupper($moduleInstance->name).'_AFILIADO';
    $field101->column = 'afiliado';
    $field101->columntype = 'INT(19)';
    $field101->uitype = 10;
    $field101->summaryfield = 1;
    $field101->typeofdata = 'I~M';
    $block->addField($field101);
    $field101->setRelatedModules(Array('Contacts'));
    
    // CLUB
    $field102 = new Vtiger_Field();
    $field102->name = 'club';
    $field102->label= 'LBL_'.strtoupper($moduleInstance->name).'_CLUB';
    $field102->column = 'club';
    $field102->columntype = 'INT(19)';
    $field102->uitype = 10;
    $field102->summaryfield = 1;
    $field102->typeofdata = 'I~M';
    $block->addField($field102);
    $field102->setRelatedModules(Array('Accounts'));
    
    // DISCIPLINA
    $field103 = new Vtiger_Field();
    $field103->name = 'disciplina';
    $field103->column = 'disciplina';
    $field103->label = 'LBL_'.strtoupper($moduleInstance->name).'_DISCIPLINA';
    $field103->columntype = 'VARCHAR(255)';
    $field103->uitype = 15;
    $field103->typeofdata = 'V~M';
    $block->addField($field103);
    $field103->setPicklistValues( Array ('Taekwondo', 'Hapkido') );
    
    // GRADO
    $field104 = new Vtiger_Field();
    $field104->name = 'grado';
    $field104->column = 'grado';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_GRADO';
    $field104->columntype = 'VARCHAR(255)';
    $field104->uitype = 15;
    $field104->typeofdata = 'V~O';
    $block->addField($field104);
    $field104->setPicklistValues( Array ('Blanco', 'Blanco Amarillo', 'Amarillo', 'Amarillo Verde', 'Verde', 'Verde Azul', 'Azul', 'Azul Rojo', 'Rojo', 'Rojo Negro', 'Negro') );
    
    // SELECCIONADO
    $field105 = new Vtiger_Field();
    $field105->name = 'seleccionado';
    $field105->label= 'LBL_'.strtoupper($moduleInstance->name).'_SELECCIONADO';
    $field105->column = 'seleccionado';
    $field105->columntype = 'VARCHAR(3)';
    $field105->uitype = 56;
    $field105->summaryfield = 1;
    $field105->typeofdata = 'C~O';
    $block->addField($field105);
    
    // ASISTENCIA
    $field106 = new Vtiger_Field();
    $field106->name = 'asistencia';
    $field106->label= 'LBL_'.strtoupper($moduleInstance->name).'_ASISTENCIA';
    $field106->column = 'asistencia';
    $field106->columntype = 'VARCHAR(3)';
    $field106->uitype = 56;
    $field106->summaryfield = 1;
    $field106->typeofdata = 'C~O';
    $block->addField($field106);
    
    // PUNTOS CONSEGUIDOS
    $field107 = new Vtiger_Field();
    $field107->name = 'puntos';
    $field107->column = 'puntos';
    $field107->label = 'LBL_'.strtoupper($moduleInstance->name).'_PUNTOS';
    $field107->columntype = 'DECIMAL(10,0)';
    $field107->uitype = 7;
    $field107->typeofdata = 'NN~O~10,0';
    $block->addField($field107);
    
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
    $filter1->addField($field100)->addField($field101, 1)->addField($field102, 2)->addField($field103, 3)->addField($field104, 4)->addField($field105, 5)->addField($field106, 6)->addField($field107, 7);
    
    // Creamos las relaciones entre los módulos 1:N
    
    //  participantes FK
    $moduleInstance = Vtiger_Module::getInstance('tbCampeonatos');
    $relatedModule = Vtiger_Module::getInstance('tbParticipantes');
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_PARTICIPANTES';
    $moduleInstance->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field100->id);
    
    //  participantes FK
    $moduleInstance = Vtiger_Module::getInstance('Contacts');
    $relatedModule = Vtiger_Module::getInstance('tbParticipantes');
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_PARTICIPANTES';
    $moduleInstance->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field101->id);
    
    //  participantes FK
    $moduleInstance = Vtiger_Module::getInstance('Accounts');
    $relatedModule = Vtiger_Module::getInstance('tbParticipantes');
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'_PARTICIPANTES';
    $moduleInstance->setRelatedList($relatedModule, $relationLabel, Array('ADD'), 'get_dependents_list', $field102->id);
    
    
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