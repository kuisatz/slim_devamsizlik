<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace BLL\BLL;

/**
 * Business Layer class for report Configuration entity
 */
class MblLogin extends \BLL\BLLSlim{
    
    /**
     * constructor
     */
    public function __construct() {
        //parent::__construct();
    }
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insert($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->insert($params);
    }
    
    /**
     * Data update function
     * @param array | null $params
     * @return array
     */
    public function update($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->update($params);
    }
    
    /**
     * Data delete function
     * @param array | null $params
     * @return array
     */
    public function delete($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->delete($params);
    }

    /**
     * get all data
     * @param array | null $params
     * @return array
     */
    public function getAll($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->getAll($params);
    }
    
    
    /**
     * get private key  from public key
     * @param array$params
     * @return array
     */
    public function pkControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkControl($params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * check if user belongs to specific company
     * @param array$params
     * @return array
     * @author Okan CIRAN
     * @since 10/06/2016
     */
    public function isUserBelongToCompany($requestHeaderParams = array(), $params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->isUserBelongToCompany($requestHeaderParams, $params);  
        return $resultSet['resultSet'];
    }
    
    /**
     * get private key temp from public temp key
     * @param array$params
     * @return array
     * @author Okan CIRAN
     * @since 0.3 27/01/2016
     */
    public function pkTempControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkTempControl($params);  
        return $resultSet['resultSet'];
    }

    
    public function pkLoginControl($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->pkLoginControl($params);  
        return $resultSet['resultSet'];
    }

    public function gnlKullaniciMebKoduFindByTcKimlikNo($params = array()) { 
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->gnlKullaniciMebKoduFindByTcKimlikNo($params);  
        return $resultSet['resultSet'];
    }
 
    
    public function gnlKullaniciFindForLoginByTcKimlikNo($params = array()) { 
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gnlKullaniciFindForLoginByTcKimlikNo($params);  
    return $resultSet['resultSet'];
    }
 
     
 
   /* public function mobilfirstdata($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    return $DAL->mobilfirstdata($params);
   
    }
    */ 
    
    public function mobilfirstdata($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->mobilfirstdata($params);  
    return $resultSet['resultSet'];
    }
   
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function mobilMenu($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->mobilMenu($params);  
        return $resultSet['resultSet'];
    }
    
    
       /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function gnlKisiOkulListesi($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->gnlKisiOkulListesi($params);  
        return $resultSet['resultSet'];
    }
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgrami($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgrami($params);  
    return $resultSet['resultSet'];
    }
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgramiDersSaatleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgramiDersSaatleri($params);  
    return $resultSet['resultSet'];
    }
     
     
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersPrgDersSaatleriOgrencileri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersPrgDersSaatleriOgrencileri($params);  
    return $resultSet['resultSet'];
    } 
    
       /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenVeliRandevulari($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenVeliRandevulari($params);  
    return $resultSet['resultSet'];
    } 
    
    
     /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function insertDevamsizlik($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->insertDevamsizlik($params);
    }
    
    
      /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function veliOgrencileri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->veliOgrencileri($params);  
    return $resultSet['resultSet'];
    } 
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciDevamsizlikListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciDevamsizlikListesi($params);  
    return $resultSet['resultSet'];
    }  
    
     
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumyoneticisisubelistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumyoneticisisubelistesi($params);  
    return $resultSet['resultSet'];
    }
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kysubeogrencilistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kysubeogrencilistesi($params);  
    return $resultSet['resultSet'];
    } 
           
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kySubeOgrenciDersListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kySubeOgrenciDersListesi($params);  
    return $resultSet['resultSet'];
    } 
     
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmensinavlistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmensinavlistesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function yakinisinavlistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->yakinisinavlistesi($params);  
    return $resultSet['resultSet'];
    } 
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumYoneticisiSinavListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumYoneticisiSinavListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gelenMesajListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gelenMesajListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiOgretmen($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiOgretmen($params);  
    return $resultSet['resultSet'];
    } 
    
       
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiKurumYoneticisi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiKurumYoneticisi($params);  
    return $resultSet['resultSet'];
    } 
    
      /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciVeYakiniDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciVeYakiniDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params
        * pk zorunlu 
     * @return array
     */
    public function kurumPersoneliSinifListesi($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        $resultSet = $DAL->kurumPersoneliSinifListesi($params);  
        return $resultSet['resultSet'];
    }
      
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumPersoneliDersProgramiListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumPersoneliDersProgramiListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function sinifSeviyeleriCombo($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->sinifSeviyeleriCombo($params);  
    return $resultSet['resultSet'];
    } 
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function sinifSeviyeleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->sinifSeviyeleri($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gnlProfil($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gnlProfil($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kurumVePersonelDevamsizlik($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kurumVePersonelDevamsizlik($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gelenMesajDetay($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gelenMesajDetay($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevListesiOgrenciveYakin($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevListesiOgrenciveYakin($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function muhBorcluSozlesmeleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->muhBorcluSozlesmeleri($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function muhBorcluOdemePlani($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->muhBorcluOdemePlani($params);  
    return $resultSet['resultSet'];
    } 
       /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function dashboarddataDersProgrami($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    
    $RolID = -11;
    if ((isset($params['RolID']) && $params['RolID'] != "")) {
        $RolID = $params['RolID'];
    }   
    switch ($RolID) {
    case 4:
        $resultSet = $DAL->dashboarddataYonetici($params);    
        break;
    case 5:
        $resultSet = $DAL->dashboarddataYonetici($params);  
        break;
    case 6:
        $resultSet = $DAL->dashboarddataYonetici($params);  
        break;
    case 7:
        $resultSet = $DAL->dashboarddataOgretmen($params); 
        break;
    case 8:
        $resultSet = $DAL->dashboarddataOgrenci($params);   
        break;
    case 9:
         $resultSet = $DAL->dashboarddataYakini($params);    
        break;
    case 10:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 17:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 18:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 19:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 20:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 21:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 22:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 23:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 28:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 29:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    case 31:
        $resultSet = $DAL->dashboarddataOgrenci($params);  
        break;
    
    }
    
    
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function dashboardIconCounts($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->dashboardIconCounts($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function sendMesajDefault($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->sendMesajDefault($params);
    }
    
     
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function sendMesajDefaultMesajKutusuSave($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->sendMesajDefaultMesajKutusuSave($params);
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function gidenMesajListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->gidenMesajListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function muhYapilacakTahsilatlarA($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->muhYapilacakTahsilatlarA($params);  
    return $resultSet['resultSet'];
    } 
    
      
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function muhYapilacakTahsilatlarB($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->muhYapilacakTahsilatlarB($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function muhYapilacakTahsilatlarC($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->muhYapilacakTahsilatlarC($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevTipleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevTipleri($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function odevAtama($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->odevAtama($params);
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciKarnesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciKarnesi($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjGonderilecekRoller($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjGonderilecekRoller($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjIcinOkulListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjIcinOkulListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjIcinOkuldakiSinifListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjIcinSinifOgrenciVeliListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjIcinPersonelListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjIcinPersonelListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjIcinOgretmenListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->msjIcinOgretmenListesi($params);  
    return $resultSet['resultSet'];
    }  
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjcombo1($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    
    $RolID = -11;
    if ((isset($params['RolID']) && $params['RolID'] != "")) {
        $RolID = $params['RolID'];
    }   
    switch ($RolID) {
    case 4:
        $resultSet = $DAL->msjGonderilecekRoller($params);    
        break;
    case 5:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 6:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 7:
        $resultSet = $DAL->msjGonderilecekRoller($params); 
        break;
    case 8:
        $resultSet = $DAL->msjGonderilecekRoller($params);   
        break;
    case 9:
         $resultSet = $DAL->msjGonderilecekRoller($params);    
        break;
    case 10:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 17:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 18:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 19:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 20:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 21:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 22:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 23:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 28:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 29:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    case 31:
        $resultSet = $DAL->msjGonderilecekRoller($params);  
        break;
    
    }
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjcombo2($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    
    $RolID = -11;
    if ((isset($params['RolID']) && $params['RolID'] != "")) {
        $RolID = $params['RolID'];
    }   
    $SendrolID = -22;
    if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
        $SendrolID = $params['SendrolID'];
    }  
    switch ($RolID) {
    case 4:
         switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }  
        break;
    case 5:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         } 
        break;
    case 6:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         } 
        break;
    case 7:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         } 
        break;
    case 8:
         switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOgretmenListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         } 
            
        break;
    case 9:
         switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOgretmenListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }   
        break;
    case 10:
        switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }  
        break;
    case 17:
        switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         } 
        break;
    case 18:
       switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
         }   
        break;
    case 19:
        switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
         }   
        break;
    case 20:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }   
        break;
    case 21:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            
         }  
        break; 
    case 28:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 28:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }  
        break;
    case 29:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 28:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         }  
        break;
    case 31:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinOkulListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
            case 31:$resultSet = $DAL->msjIcinOkulListesi($params);break; 
         } 
        break;
    
    }
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjcombo3($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    
    $RolID = -11;
    if ((isset($params['RolID']) && $params['RolID'] != "")) {
        $RolID = $params['RolID'];
    }   
    $SendrolID = -22;
    if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
        $SendrolID = $params['SendrolID'];
    }  
    switch ($RolID) {
    case 4:
    
         switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         } 
        
        break;
    case 5:
       switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         } 
        break;
    case 6:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 10:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 22:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 23:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 28:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }   
        break;
    case 7:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
         } 
        break;
    case 8:
     //   $resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);   
        break;
    case 9:  
            switch ($SendrolID) { 
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }    
        break;
    case 10:
        switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
         }  
        break;
    case 17:
         switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }   
        break;
    case 18:
        switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }    
        break;
    case 19:
         switch ($SendrolID) { 
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 17:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 18:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 19:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }     
        break;
    case 20:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }    
        break;
    case 21:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break;  
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
            case 20:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 21:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }     
        break; 
    case 28:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 28:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }   
        break;
    case 29:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 28:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 29:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }   
        break;
    case 31:
        switch ($SendrolID) {
            case 4:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 5:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 6:$resultSet = $DAL->msjIcinPersonelListesi($params);break;
            case 7:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
            case 8:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinOkuldakiSinifListesi($params);break; 
            case 31:$resultSet = $DAL->msjIcinPersonelListesi($params);break; 
         }  
        break;
    
    }
    return $resultSet['resultSet'];
    } 
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function msjcombo4($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    
    $RolID = -11;
    if ((isset($params['RolID']) && $params['RolID'] != "")) {
        $RolID = $params['RolID'];
    }   
    $SendrolID = -22;
    if ((isset($params['SendrolID']) && $params['SendrolID'] != "")) {
        $SendrolID = $params['SendrolID'];
    }  
    switch ($RolID) {
    case 4:
    
         switch ($SendrolID) { 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         } 
        
        break;
    case 5:
       switch ($SendrolID) { 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         } 
        break;
    case 6:
        switch ($SendrolID) { 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         }   
        break;
    case 7:
        switch ($SendrolID) { 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         } 
        break;
    case 8:
            
        break;
    case 9:  
        break;
    case 10:
        switch ($SendrolID) {  
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
         }  
        break;
    case 17: 
        break;
    case 18:
            
        break;
    case 19:
            
        break;
    case 20:
        switch ($SendrolID) { 
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;  
         }    
        break;
    case 21:
        switch ($SendrolID) { 
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;  
         }     
        break; 
    case 28: 
        break;
    case 29: 
        break;
    case 31:
        switch ($SendrolID) { 
            case 8:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break; 
            case 9:$resultSet = $DAL->msjIcinSinifOgrenciVeliListesi($params);break;  
         }  
        break;
    
    }
    return $resultSet['resultSet'];
    } 
    
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function mesajTipleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->mesajTipleri($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmensubelistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmensubelistesi($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenSinavDersleriListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenSinavDersleriListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenSinavaGirenSubeler($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenSinavaGirenSubeler($params);  
    return $resultSet['resultSet'];
    } 
            
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function kyOgretmenOdevListeleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->kyOgretmenOdevListeleri($params);  
    return $resultSet['resultSet'];
    } 
     
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrenciVeliIcinOgretmenListesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrenciVeliIcinOgretmenListesi($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrencininAldigiNotlar($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrencininAldigiNotlar($params);  
    return $resultSet['resultSet'];
    } 
    
     /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrencilerinAldigiNotlarSinavBazli($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrencilerinAldigiNotlarSinavBazli($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogretmenSinavSorulariKDK($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenSinavSorulariKDK($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function ogrenciOdeviGordu($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->ogrenciOdeviGordu($params);
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function odevOnayTipleri($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->odevOnayTipleri($params);  
    return $resultSet['resultSet'];
    } 
            
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function topluOgrenciCevap($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->topluOgrenciCevap($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function ogrenciSinavitapcikKaydet($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->ogrenciSinavitapcikKaydet($params);
    }
    
      /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function ogrenciSinaviSonuclariKaydet($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->ogrenciSinaviSonuclariKaydet($params);
    }
     /**
     * DAta insert function
     * @param array | null $params
     * @return array
     */
    public function ogrenciSinaviSonuclariOnay($params = array()) {
        $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
        return $DAL->ogrenciSinaviSonuclariOnay($params);
    }
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function sinavdaKullanilanKitaplar($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->sinavdaKullanilanKitaplar($params);  
    return $resultSet['resultSet'];
    } 
    
    /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */
    public function ogrencininSinavlistesi($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogrencininSinavlistesi($params);  
    return $resultSet['resultSet'];
    } 
    
        /**
     * Function to get datagrid row count on user interface layer
     * @param array | null $params 
     * @return array
     */ 
    public function ogretmenProgramindakiDersler($params = array()) {
    $DAL = $this->slimApp->getDALManager()->get('mblLoginPDO');
    $resultSet = $DAL->ogretmenProgramindakiDersler($params);  
    return $resultSet['resultSet'];
    } 
}

