<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL;

/**
 * Business LAyer MAnager config class
 * Uses zend Framework manager config infrastructure
 */
class BLLManagerConfig{
    
    /**
     * constructor
     */
    public function __construct() {
        
    }
    
    /**
     * config array for zend service manager config
     * @var array
     */
    protected $config= array(
        // Initial configuration with which to seed the ServiceManager.
        // Should be compatible with Zend\ServiceManager\Config.
         'service_manager' => array(
             'invokables' => array(
                 //'test' => 'Utill\BLL\Test\Test'
                 'reportConfigurationBLL' => 'BLL\BLL\ReportConfiguration',
                 'cmpnyEqpmntBLL' => 'BLL\BLL\CmpnyEqpmnt',
                 'sysNavigationLeftBLL' => 'BLL\BLL\SysNavigationLeft',
                 'infoUsersBLL' => 'BLL\BLL\InfoUsers',
                 'sysCountrysBLL' => 'BLL\BLL\SysCountrys',
                 'sysCityBLL' => 'BLL\BLL\SysCity',
                 'sysLanguageBLL' => 'BLL\BLL\SysLanguage',
                 'sysBoroughBLL' => 'BLL\BLL\SysBorough',
                 'sysVillageBLL' => 'BLL\BLL\SysVillage',
                 'blLoginLogoutBLL' => 'BLL\BLL\BlLoginLogout',
                 'sysAclRolesBLL' => 'BLL\BLL\SysAclRoles',
                 'sysAclResourcesBLL' => 'BLL\BLL\SysAclResources',
                 'sysAclPrivilegeBLL' => 'BLL\BLL\SysAclPrivilege',
                 'sysAclRrpMapBLL' => 'BLL\BLL\SysAclRrpMap',  
                 'sysSpecificDefinitionsBLL' => 'BLL\BLL\SysSpecificDefinitions',   
                 'infoUsersCommunicationsBLL' => 'BLL\BLL\InfoUsersCommunications',   
                 'infoUsersAddressesBLL' => 'BLL\BLL\InfoUsersAddresses',   
                 'blActivationReportBLL' => 'BLL\BLL\BlActivationReport',
                 'sysOperationTypesBLL' => 'BLL\BLL\SysOperationTypes',
                 'sysOperationTypesToolsBLL' => 'BLL\BLL\SysOperationTypesTools',
                 'infoErrorBLL' => 'BLL\BLL\InfoError',
                 'sysUnitsBLL' => 'BLL\BLL\SysUnits',
                 'hstryLoginBLL' => 'BLL\BLL\HstryLogin',
                 'blAdminActivationReportBLL' => 'BLL\BLL\BlAdminActivationReport',
                 'sysCertificationsBLL' => 'BLL\BLL\SysCertifications',
                 'sysUnitSystemsBLL' => 'BLL\BLL\SysUnitSystems',
                 'infoUsersSocialmediaBLL' => 'BLL\BLL\InfoUsersSocialmedia',
                 'sysSocialMediaBLL' => 'BLL\BLL\SysSocialMedia',
                 'sysMailServerBLL' => 'BLL\BLL\SysMailServer',                 
                 'infoUsersVerbalBLL' => 'BLL\BLL\InfoUsersVerbal',
                 'infoUsersProductsServicesBLL' => 'BLL\BLL\InfoUsersProductsServices',
                 'sysMembershipTypesBLL' => 'BLL\BLL\SysMembershipTypes',
                 'sysAclRrpBLL' => 'BLL\BLL\SysAclRrp',
                 'sysUniversitiesBLL' => 'BLL\BLL\SysUniversities',
                 'sysMenuTypesBLL' => 'BLL\BLL\SysMenuTypes',
                 'sysAclModulesBLL' => 'BLL\BLL\SysAclModules',
                 'sysAclActionsBLL' => 'BLL\BLL\SysAclActions',
                 'sysAclMenuTypesActionsBLL' => 'BLL\BLL\SysAclMenuTypesActions',
                 'sysAclRrpRestservicesBLL' => 'BLL\BLL\SysAclRrpRestservices',
                 'sysServicesGroupsBLL' => 'BLL\BLL\SysServicesGroups',
                 'sysAclRestservicesBLL' => 'BLL\BLL\SysAclRestservices',
                 'sysAssignDefinitionBLL' => 'BLL\BLL\SysAssignDefinition',
                 'sysAssignDefinitionRolesBLL' => 'BLL\BLL\SysAssignDefinitionRoles',
                 'sysAclActionRrpBLL' => 'BLL\BLL\SysAclActionRrp',
                 'sysAclActionRrpRestservicesBLL' => 'BLL\BLL\SysAclActionRrpRestservices',
                 'infoUsersSendingMailBLL' => 'BLL\BLL\InfoUsersSendingMail',                      
                 
                 'logConnectionBLL' => 'BLL\BLL\LogConnection',  
                 'logServicesBLL' => 'BLL\BLL\LogServices',
                 'logAdminBLL' => 'BLL\BLL\LogAdmin',
                 
                 'opUserIdBLL' => 'BLL\BLL\InfoUsers', 
                 'operationsTypesBLL' => 'BLL\BLL\SysOperationTypesRrp',  
                 'languageIdBLL' => 'BLL\BLL\SysLanguage',
                 'operationTableNameBLL' => 'BLL\BLL\PgClass',
                 'consultantProcessSendBLL' => 'BLL\BLL\ActProcessConfirm',  
                 'SesionIdBLL' => 'BLL\BLL\InfoUsers', 
                 
                'pgClassBLL' => 'BLL\BLL\PgClass',
                'sysOperationTypesRrpBLL' => 'BLL\BLL\SysOperationTypesRrp',
                'actProcessConfirmBLL' => 'BLL\BLL\ActProcessConfirm',
                 
                'actUsersActionStatisticsBLL' => 'BLL\BLL\ActUsersActionStatistics',
                'sysNotificationRestservicesBLL' => 'BLL\BLL\SysNotificationRestservices',
                'sysSectorsBLL' => 'BLL\BLL\SysSectors',
                'mblLoginBLL' => 'BLL\BLL\mblLogin',
                'mobilSettingsBLL' => 'BLL\BLL\MobilSettings',
                'mobilSetDbConfigxBLL' => 'BLL\BLL\MobilSetDbConfigx',
                 
                 
                 
             ),
             'factories' => [
                 //'reportConfigurationPDO' => 'BLL\BLL\ReportConfiguration',
             ],  

         ),
     );
    
    /**
     * return config array for zend service manager config
     * @return array | null
     * @author Okan CIRAN
     */
    public function getConfig() {
        return $this->config['service_manager'];
    }

}




