<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sms extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library('tambahan_fungsi');
    }
    /*
     * Methode : GET
     * API SMS Gateway
     * Parameter :
     * 1. msisdn
     * 2. message
     * 3. contentid
     * 4. realmsisdn
     * Return XML
     */
    function index()
    {
        header("Content-Type: text/xml");
        echo "<?xml version='1.0' encoding='UTF-8' ?>";    
        $msisdn=isset($_GET['msisdn'])?$_GET['msisdn']:"";
        $message=isset($_GET['message'])?$_GET['message']:"";
        $contentid=isset($_GET['contentid'])?$_GET['contentid']:"";
        $realmsisdn=isset($_GET['realmsisdn'])?$_GET['realmsisdn']:"";
        $parameter=  explode(" ", $message);        
        $jns=  strtolower(isset($parameter[0])?$parameter[0]:"");
        $koderedeem=isset($parameter[1])?$parameter[1]:"";
        $kodeavatar=isset($parameter[2])?$parameter[2]:"";
        $notelepon=isset($parameter[3])?$parameter[3]:""; 
        $dataxml['msisdn'] = $msisdn;
        $dataxml['response'] = "1";
        $dataxml['option'] = "0";
        $dataxml['charge'] = "0";
        $dataxml['appid'] = "";
        $dataxml['partnerid'] = "";
        $dataxml['mediaid'] = "";
        $dataxml['trxid'] = "";
        $dataxml['hptype'] = "ALL";
        $dataxml['shortname'] = "";
        $dataxml['contenttype'] = "1";
        $dataxml['priority'] = "1";
        $dataxml['contentid'] = $contentid;
        $dataxml['desc'] = "";
        $dataxml['mesage'] = "Maaf, avatar item ".$koderedeem." tidak valid, silakan coba lagi.";
        switch ($jns)
        {
            case "av":
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Avatar");
                $tamp = $this->mongo_db->find(array("code"=>$koderedeem, "payment"=>array('$in'=>array('Unlock','Paid'))), 0, 0, array());
                if($tamp)
                {
                    $count = 1;
                    $generatorcode = $this->tambahan_fungsi->global_get_random(10);
                    $this->mongo_db->select_collection("Redeem");
                    $datatinsert=array(
                        'code'  =>$generatorcode,
                        'count'  =>$count,
                        'expire'  =>'',
                        'create'  =>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                    );
                    $_idinsert = $this->mongo_db->insert($datatinsert);
                    $this->mongo_db->select_collection("RedeemAvatar");
                    foreach($tamp as $dtlistrd)
                    {
                        $datatinsertsub = array(
                            'avatar_id'  =>(string)$dtlistrd['_id'],
                            'code_id'  =>(string)$_idinsert,
                        );
                        $this->mongo_db->insert($datatinsertsub);
                    }
                    $dataxml['mesage']="Selamat, avatar item ".$koderedeem." menjadi milik anda. Ini redeem codenya: ".$generatorcode.".";
                }
                else
                {
                    $dataxml['mesage']="Maaf, avatar item ".$koderedeem." Gagal tergenerate, silakan coba lagi.";
                }
            break;
            default : null;
        }
        echo $this->tambahan_fungsi->return_xml($dataxml);
    }
}
