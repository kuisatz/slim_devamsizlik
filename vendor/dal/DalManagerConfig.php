<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL;

/**
 * class called for DAL manager config 
 * DAL manager uses Zend Service manager and 
 * config class is compliant zend service config structure
 * @author Okan CIRAN
 */
class DalManagerConfig {

    /**
     * constructor
     */
    public function __construct() {
        
    }

    /**
     * config array for zend service manager config
     * @var array
     */
    protected $config = array(
        // Initial configuration with which to seed the ServiceManager.
        // Should be compatible with Zend\ServiceManager\Config.
        'service_manager' => array(
            'invokables' => array(
            //'test' => 'Utill\BLL\Test\Test'
            ),
            'factories' => [
                'reportConfigurationPDO' => 'DAL\Factory\PDO\ReportConfigurationFactory',
                'cmpnyEqpmntPDO' => 'DAL\Factory\PDO\CmpnyEqpmntFactory',
                'sysNavigationLeftPDO' => 'DAL\Factory\PDO\SysNavigationLeftFactory',                
                'infoUsersPDO' => 'DAL\Factory\PDO\InfoUsersFactory',
                'sysCountrysPDO' => 'DAL\Factory\PDO\SysCountrysFactory',
                'sysCityPDO' => 'DAL\Factory\PDO\SysCityFactory',
                'sysLanguagePDO' => 'DAL\Factory\PDO\SysLanguageFactory',
                'sysBoroughPDO' => 'DAL\Factory\PDO\SysBoroughFactory',
                'sysVillagePDO' => 'DAL\Factory\PDO\SysVillageFactory',      
                'blLoginLogoutPDO' => 'DAL\Factory\PDO\BlLoginLogoutFactory',                   
                'sysAclRolesPDO' => 'DAL\Factory\PDO\SysAclRolesFactory',   
                'sysAclResourcesPDO' => 'DAL\Factory\PDO\SysAclResourcesFactory',   
                'sysAclPrivilegePDO' => 'DAL\Factory\PDO\SysAclPrivilegeFactory',   
                'sysAclRrpMapPDO' => 'DAL\Factory\PDO\SysAclRrpMapFactory',  
                'sysSpecificDefinitionsPDO' => 'DAL\Factory\PDO\SysSpecificDefinitionsFactory', 
                'infoUsersCommunicationsPDO' => 'DAL\Factory\PDO\InfoUsersCommunicationsFactory', 
                'infoUsersAddressesPDO' => 'DAL\Factory\PDO\InfoUsersAddressesFactory', 
                'blActivationReportPDO' => 'DAL\Factory\PDO\BlActivationReportFactory',                 
                'sysOperationTypesPDO' => 'DAL\Factory\PDO\SysOperationTypesFactory',
                'sysOperationTypesToolsPDO' => 'DAL\Factory\PDO\SysOperationTypesToolsFactory', 
                'infoErrorPDO' => 'DAL\Factory\PDO\InfoErrorFactory',                 
                'sysUnitsPDO' => 'DAL\Factory\PDO\SysUnitsFactory',                                
                'hstryLoginPDO' => 'DAL\Factory\PDO\HstryLoginFactory',
                'blAdminActivationReportPDO' => 'DAL\Factory\PDO\BlAdminActivationReportFactory',                
                'sysCertificationsPDO' => 'DAL\Factory\PDO\SysCertificationsFactory', 
                'sysUnitSystemsPDO' => 'DAL\Factory\PDO\SysUnitSystemsFactory',                                                
                'infoUsersSocialmediaPDO' => 'DAL\Factory\PDO\InfoUsersSocialmediaFactory',                
                'sysSocialMediaPDO' => 'DAL\Factory\PDO\SysSocialMediaFactory',                
                'sysMailServerPDO' => 'DAL\Factory\PDO\SysMailServerFactory',                                
                'infoUsersVerbalPDO' => 'DAL\Factory\PDO\InfoUsersVerbalFactory',
                'infoUsersProductsServicesPDO' => 'DAL\Factory\PDO\InfoUsersProductsServicesFactory',                
                'sysMembershipTypesPDO' => 'DAL\Factory\PDO\SysMembershipTypesFactory',
                'sysAclRrpPDO' => 'DAL\Factory\PDO\SysAclRrpFactory',
                'sysUniversitiesPDO' => 'DAL\Factory\PDO\SysUniversitiesFactory',                
                'sysMenuTypesPDO' => 'DAL\Factory\PDO\SysMenuTypesFactory',
                'sysAclModulesPDO' => 'DAL\Factory\PDO\SysAclModulesFactory',
                'sysAclActionsPDO' => 'DAL\Factory\PDO\SysAclActionsFactory',
                'sysAclMenuTypesActionsPDO' => 'DAL\Factory\PDO\SysAclMenuTypesActionsFactory',
                'sysAclRrpRestservicesPDO' => 'DAL\Factory\PDO\SysAclRrpRestservicesFactory',
                'sysServicesGroupsPDO' => 'DAL\Factory\PDO\SysServicesGroupsFactory',
                'sysAclRestservicesPDO' => 'DAL\Factory\PDO\SysAclRestservicesFactory',
                'sysAssignDefinitionPDO' => 'DAL\Factory\PDO\SysAssignDefinitionFactory',   
                'sysAssignDefinitionRolesPDO' => 'DAL\Factory\PDO\SysAssignDefinitionRolesFactory',   
                'sysAclActionRrpPDO' => 'DAL\Factory\PDO\SysAclActionRrpFactory',   
                'sysAclActionRrpRestservicesPDO' => 'DAL\Factory\PDO\SysAclActionRrpRestservicesFactory',                                   
                'infoUsersSendingMailPDO' => 'DAL\Factory\PDO\InfoUsersSendingMailFactory',
                                 
                'logConnectionPDO' => 'DAL\Factory\PDO\LogConnectionFactory',
                'logServicesPDO' => 'DAL\Factory\PDO\LogServicesFactory',                
                'logAdminPDO' => 'DAL\Factory\PDO\LogAdminFactory',
                
                'sysOperationTypesRrpPDO' => 'DAL\Factory\PDO\SysOperationTypesRrpFactory',   
                'pgClassPDO' => 'DAL\Factory\PDO\PgClassFactory',
                'actProcessConfirmPDO' => 'DAL\Factory\PDO\ActProcessConfirmFactory',
                
                'actUsersActionStatisticsPDO' => 'DAL\Factory\PDO\ActUsersActionStatisticsFactory',
                'sysNotificationRestservicesPDO' => 'DAL\Factory\PDO\SysNotificationRestservicesFactory',
                'sysSectorsPDO' => 'DAL\Factory\PDO\SysSectorsFactory',
                'mblLoginPDO' => 'DAL\Factory\PDO\MblLoginFactory',
                'mobilSettingsPDO' => 'DAL\Factory\PDO\MobilSettingsFactory',
                'mobilSetDbConfigxPDO' => 'DAL\Factory\PDO\MobilSetDbConfigxFactory',
                
                
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
