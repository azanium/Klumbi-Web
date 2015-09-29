<?php
include_once('libraries/LiloMongo.php'); 
/*
 * API for SMS Request Avatar
 * Methode: GET
 * Parameter :
 * 1. msisdn
 * 2. message
 * 3. contentid
 * 4. realmsisdn
 * Example : [server host]/[server path]/api/pm/sms/query?realmsisdn=&contentid=&msisdn=&message=av [koderedeem] [Kode Avatar] 
 * Return : XML
 */
function pm_sms_query()
{
    header("Content-Type: text/xml");
    echo "<?xml version='1.0' encoding='UTF-8' ?>";    
    $msisdn=isset($_GET['msisdn'])?$_GET['msisdn']:"";
    $message=isset($_GET['message'])?$_GET['message']:"";
    $contentid=isset($_GET['contentid'])?$_GET['contentid']:"";
    $realmsisdn=isset($_GET['realmsisdn'])?$_GET['realmsisdn']:"";
    $parameter=  explode(" ", $message);        
    $jns=  strtolower(isset($parameter[1])?$parameter[1]:"");
    $koderedeem=isset($parameter[1])?$parameter[1]:"";
    $kodeavatar=isset($parameter[3])?$parameter[3]:"";
    $notelepon=isset($parameter[4])?$parameter[4]:"";
    $dataxml=array(
        'msisdn'=>$msisdn,
        'response'=>"1",
        'option'=>"0",
        'charge'=>"0",
        'appid'=>"",
        'partnerid'=>"",
        'mediaid'=>"",
        'trxid'=>"",
        'hptype'=>"ALL",
        'shortname'=>"",
        'contenttype'=>"1",
        'priority'=>"1",
        'contentid'=>$contentid,
        'desc'=>"",
        'mesage'=>"Maaf, avatar item ".$koderedeem." tidak valid, silakan coba lagi.",
    );        
    switch ($jns)
    {
        case "av":
                    $lilo_mongo = new LiloMongo();
                    $lilo_mongo->selectDB('Assets');
                    $lilo_mongo->selectCollection('Avatar');
                    $data=$lilo_mongo->find(array("code"=>$koderedeem,"payment"=>array('$in'=>array('Unlock','Paid'))));    
                    if($data)
                    {
                        $idavatar=$data['_id'];
                        $count = 1;
                        $kodegenerate=get_random(10);
                        $lilo_mongo->selectCollection('Redeem');
                        $datatinsert=array(
                                'code'  =>$kodegenerate,
                                'count'  =>$count,
                                'expire'  =>'',
                                'create'  =>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s"))),
                            );
                        $lilo_mongo->insert($datatinsert);
                        $data2=$lilo_mongo->findOne(array("code"=>$kodegenerate));
                        if($data2)
                        {
                            $idredeem=$data2['_id'];
                            $lilo_mongo->selectCollection('RedeemAvatar');
                            foreach($data as $tampung)
                            {
                                $datatinsert=array(
                                    'avatar_id'  =>(string)$tampung['_id'],
                                    'code_id'  =>(string)$idredeem,
                                );
                                $lilo_mongo->insert($datatinsert);
                            }
                            $dataxml['msisdn']=$msisdn;
                            $dataxml['contentid']=$contentid;
                            $dataxml['mesage']="Selamat, avatar item ".$koderedeem." menjadi milik anda. Ini redeem codenya: ".$kodegenerate.".";
                            echo return_xml($dataxml);
                        }
                        else
                        {
                            $dataxml['msisdn']=$msisdn;
                            $dataxml['contentid']=$contentid;
                            $dataxml['mesage']="Maaf, avatar item ".$koderedeem." Gagal tergenerate, silakan coba lagi.";
                            echo return_xml($dataxml);
                        }
                    }
                    else
                    {
                        echo return_xml($dataxml);
                    }
            break;
        default : echo return_xml($dataxml);
    }
}