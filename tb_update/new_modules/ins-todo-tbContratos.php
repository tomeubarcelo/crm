<?php
ini_set('display_errors', 1);
include_once 'vtlib/Vtiger/Module.php';

$Vtiger_Utils_Log = true;

$MODULENAME = 'tbContratos';

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
    $block1->label = 'LBL_BLOC_TBCONTRATOS';
    $moduleInstance->addBlock($block1);
    
    // Id de contratos
    $field99 = new Vtiger_Field();
    $field99->name = 'tbcontratosno'; // change fieldname
    $field99->label = 'LBL_CONTRATOS_NR';
    $field99->column = 'tbcontratosno'; // change fieldname
    $field99->columntype = 'VARCHAR(100)';
    $field99->uitype = 4;
    $field99->typeofdata = 'V~O';
    $block1->addField($field99);
    # setup auto value
    $oEntity = new CRMEntity();
    $oEntity->setModuleSeqNumber("configure",$moduleInstance->name,"CONTR",1); // change ENR to your sequence prefix
    
    $moduleInstance->setEntityIdentifier($field99);    
    
    // tbEmployees
    $field100 = new Vtiger_Field();
    $field100->name = 'employee_tbcontrato';
    $field100->column = 'employee_tbcontrato';
    $field100->label = 'LBL_'.strtoupper($moduleInstance->name).'_EMPLOYEE';
    $field100->columntype = 'VARCHAR(50)';
    $field100->uitype = 10;
    $field100->summaryfield = 1;
    $field100->typeofdata = 'V~M';
    $block1->addField($field100);
    $field100->setRelatedModules(Array('tbEmployees'));
    // texto que aparece en los campos relacionados
    $moduleInstance->setEntityIdentifier($field100);
                    
    // Fecha de inicio contrato
    $field101 = new Vtiger_Field();
    $field101->name = 'fechainicio_tbcontratos';
    $field101->column = 'fechainicio_tbcontratos';
    $field101->label = 'LBL_'.strtoupper($moduleInstance->name).'_FECHAINICIO';
    $field101->columntype = 'DATE';
    $field101->uitype = 5;
    $field101->typeofdata = 'D~O';
    $block1->addField($field101);
    
    // Fecha de fin contrato
    $field102 = new Vtiger_Field();
    $field102->name = 'fechafin_tbcontratos';
    $field102->column = 'fechafin_tbcontratos';
    $field102->label = 'LBL_'.strtoupper($moduleInstance->name).'_FECHAFIN';
    $field102->columntype = 'DATE';
    $field102->uitype = 5;
    $field102->typeofdata = 'D~O';
    $block1->addField($field102);
    
    // Sueldo anual
    $field103 = new Vtiger_Field();
    $field103->name = 'sueldoanual_tbcontratos';
    $field103->column = 'sueldoanual_tbcontratos';
    $field103->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUELDOANUAL';
    $field103->uitype = 7;
    $field103->typeofdata = 'I~O';
    $field103->columntype = 'INT(3)';
    $block1->addField($field103);
    
    // Sueldo mensual
    $field104 = new Vtiger_Field();
    $field104->name = 'sueldomensual_tbcontratos';
    $field104->column = 'sueldomensual_tbcontratos';
    $field104->label = 'LBL_'.strtoupper($moduleInstance->name).'_SUELDOMENSUAL';
    $field104->uitype = 7;
    $field104->typeofdata = 'I~O';
    $field104->columntype = 'INT(3)';
    $block1->addField($field104);
    
    // Número de días de vacaciones por contrato
    $field105 = new Vtiger_Field();
    $field105->name = 'diasvacaciones_tbcontratos';
    $field105->column = 'diasvacaciones_tbcontratos';
    $field105->label = 'LBL_'.strtoupper($moduleInstance->name).'_DIASVACACIONES';
    $field105->columntype = 'INT(3)';
    $field105->uitype = 7;
    $field105->typeofdata = 'I~O';
    $block1->addField($field105);
    
    // Número de días de vacaciones disfrutados
    $field106 = new Vtiger_Field();
    $field106->name = 'vacacionesdisfr_tbcontratos';
    $field106->column = 'vacacionesdisfr_tbcontratos';
    $field106->label = 'LBL_'.strtoupper($moduleInstance->name).'_DIASVACACIONES_DISFRUTADOS';
    $field106->columntype = 'INT(3)';
    $field106->uitype = 7;
    $field106->typeofdata = 'I~O';
    $block1->addField($field106);
    
    // Número de días de permisos disfrutados
    $field107 = new Vtiger_Field();
    $field107->name = 'permisosdisfr_tbcontratos';
    $field107->column = 'permisosdisfr_tbcontratos';
    $field107->label = 'LBL_'.strtoupper($moduleInstance->name).'_PERMISOS_DISFRUTADOS';
    $field107->columntype = 'INT(3)';
    $field107->uitype = 7;
    $field107->typeofdata = 'I~O';
    $block1->addField($field107);
    
    // Comentario
    $field108 = new Vtiger_Field();
    $field108->name = 'comentario_tbcontratos';
    $field108->column = 'comentario_tbcontratos';
    $field108->label = 'LBL_'.strtoupper($moduleInstance->name).'_COMENTARIO';
    $field108->uitype = 19;
    $field108->typeofdata = 'V~O';
    $field108->columntype = 'TEXT';
    $block1->addField($field108);
    
    // Comentario privado
    $field109 = new Vtiger_Field();
    $field109->name = 'comentariopriv_tbcontratos';
    $field109->column = 'comentariopriv_tbcontratos';
    $field109->label = 'LBL_'.strtoupper($moduleInstance->name).'_COMENTARIOPRIV';
    $field109->uitype = 19;
    $field109->typeofdata = 'V~O';
    $field109->columntype = 'TEXT';
    $block1->addField($field109);
             
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