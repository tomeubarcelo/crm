<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbVacaciones';

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
    $block1->label = 'LBL_BLOC_TBVACACIONES';
    $moduleInstance->addBlock($block1);
        
    // Id de vacaciones
    $field99 = new Vtiger_Field();
    $field99->name = 'tbvacacionesno'; // change fieldname
    $field99->label = 'LBL_VACACIONES_NR';
    $field99->column = 'tbvacacionesno'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block1->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"VAC",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);    
    
    // tbEmployees
    $field100 = new Vtiger_Field();
    $field100->name = 'employee_tbvacaciones';
    $field100->column = 'employee_tbvacaciones';
    $field100->label = 'LBL_TBVACACIONES_EMPLOYEE';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block1->addField($field100);
    $field100->setRelatedModules(Array('tbEmployees'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);
  
    // Tipo → Lista ['Vacaciones', 'Permiso']
    $field101 = new Vtiger_Field();
    $field101->name = 'tipo_tbvacaciones';
    $field101->column = 'tipo_tbvacaciones';
    $field101->label = 'LBL_TBVACACIONES_TIPO';
    $field101->columntype = 'VARCHAR(255)';
    $field101->uitype = 15;
    $field101->typeofdata = 'V~O';
    $field101->displaytype = 1;
    $block1->addField($field101);
    $field101->setPicklistValues( Array ('Vacaciones', 'Permiso') );
    
    // Nº de días
    $field102 = new Vtiger_Field();
    $field102->name = 'numdias_tbvacaciones';
    $field102->column = 'numdias_tbvacaciones';
    $field102->label = 'LBL_TBVACACIONES_DIAS';
    $field102->uitype = 7;
    $field102->typeofdata = 'I~O';
    $field102->columntype = 'INT(3)';
    $block1->addField($field102);
    
    // Título
    $field103 = new Vtiger_Field();
    $field103->name = 'titulo_tbvacaciones';
    $field103->column = 'titulo_tbvacaciones';
    $field103->label = 'LBL_TBVACACIONES_TITULO';
    $field103->columntype = 'VARCHAR(255)';
    $field103->uitype = 1;
    $field103->typeofdata = 'V~O';
    $block1->addField($field103);
    
    // Descripción
    $field104 = new Vtiger_Field();
    $field104->name = 'descripcion_tbvacaciones';
    $field104->column = 'descripcion_tbvacaciones';
    $field104->label = 'LBL_TBVACACIONES_DESCRIPCION';
    $field104->uitype = 19;
    $field104->typeofdata = 'V~O';
    $field104->columntype = 'TEXT';
    $block1->addField($field104);
    
    // Estado → Lista ['Pendiente', 'Aprobado', 'Cancelado']
    $field105 = new Vtiger_Field();
    $field105->name = 'estado_tbvacaciones';
    $field105->column = 'estado_tbvacaciones';
    $field105->label = 'LBL_TBVACACIONES_ESTADO';
    $field105->columntype = 'VARCHAR(255)';
    $field105->uitype = 15;
    $field105->typeofdata = 'V~O';
    $field105->displaytype = 1;
    $block1->addField($field105);
    $field105->setPicklistValues( Array ('Pendiente', 'Aprobado', 'Cancelado') );
             
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
    
    // Sharing Access Setup
    $moduleInstance->setDefaultSharing();
    
    // Webservice Setup
    $moduleInstance->initWebservice();
    
    // Creamos las relaciones entre los módulos 1:N
    
    // tbEmployees
    $moduleIns = Vtiger_Module::getInstance('tbEmployees');
    $relatedModule = Vtiger_Module::getInstance($moduleInstance->name);
    $relationLabel = 'LBL_'.strtoupper($moduleInstance->name).'EMPLOYEES';
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
    
    $v_dir = 'modules/'.$MODULENAME;
    if (!file_exists($root_directory.$v_dir))
    {
        mkdir('modules/'.$MODULENAME);
    }
    echo "<br>OK\n";
}