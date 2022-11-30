<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbNominas';

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
    $block1->label = 'LBL_BLOC_TBNOMINAS';
    $moduleInstance->addBlock($block1);
       
    // Id de nominas
    $field99 = new Vtiger_Field();
    $field99->name = 'tbnominasno'; // change fieldname
    $field99->label = 'LBL_NOMINAS_NR';
    $field99->column = 'tbnominasno'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block1->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"NOM",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);
    
    // tbEmployees
    $field100 = new Vtiger_Field();
    $field100->name = 'employee_tbnomina';
    $field100->column = 'employee_tbnomina';
    $field100->label = 'LBL_TBNOMINAS_EMPLOYEE';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block1->addField($field100);
    $field100->setRelatedModules(Array('tbEmployees'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);
              
    // Descripcion
    $field101 = new Vtiger_Field();
    $field101->name = 'descripcion_tbnomina';
    $field101->column = 'descripcion_tbnomina';
    $field101->label = 'LBL_TBNOMINAS_DESCRIPCION';
    $field101->columntype = 'VARCHAR(255)';
    $field101->uitype = 1;
    $field101->typeofdata = 'V~O';
    $block1->addField($field101);
    
    // Fecha
    $field102 = new Vtiger_Field();
    $field102->name = 'fechainicio_tbnomina';
    $field102->column = 'fechainicio_tbnomina';
    $field102->label = 'LBL_TBNOMINAS_FECHAINICIO';
    $field102->columntype = 'DATE';
    $field102->uitype = 5;
    $field102->typeofdata = 'D~O';
    $block1->addField($field102);
    
    // Documento (PDF de la nómina importada)
    $field103 = new Vtiger_Field();
    $field103->name = 'pdf_tbnomina';
    $field103->column = 'pdf_tbnomina';
    $field103->label = 'LBL_TBNOMINAS_PDF';
    $field103->uitype = 901;
    $field103->typeofdata = 'V~O';   
    $field103->columntype = 'varchar(200)';
    $block1->addField($field103);
             
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