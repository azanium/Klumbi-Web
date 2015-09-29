<?php 
include_once('libraries/LiloMongo.php');
include_once('config/config.php');
/*
 * API for Get List Banner
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/banner/list/0/10/
 * Return : JSON
 */
function mobile_banner_list() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Banner');
    $temp_data = $lilo_mongo->find_pagging(array(), $start_from, $limit,array('dateAdd'=>-1));    
    $count = $lilo_mongo->count();
    $data = array();
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $_id=(!isset($dt['_id'])?"":(string)$dt['_id']);
            $ID=(!isset($dt['ID'])?"":$dt['ID']);
            $name=(!isset($dt['name'])?"":$dt['name']);
            $Descriptions=(!isset($dt['Descriptions'])?"":$dt['Descriptions']);
            $type=(!isset($dt['type'])?"":$dt['type']);
            $urlPicture=(!isset($dt['urlPicture'])?"":$dt['urlPicture']);
            $dataValue=(!isset($dt['dataValue'])?"":$dt['dataValue']);
            $picture=(!isset($dt['picture'])?"":$dt['picture']);
            $path_upload = URL_ASSET_IMAGE."images/";
            $data[] = array(
                '_id' => $_id,
                'ID' => $ID,
                'name' => $name,
                'descriptions' => $Descriptions,
                'type' => $type,
                'urlPicture' => $urlPicture,
                'dataValue' => $dataValue,
                'picture' => $picture,
            );
        }
    }
    $output = array(
        'count' => $count,
        'data' => $data
    );
    return json_encode($output);
}
/*
 * API for Get Count Banner
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/banner/count
 * Return : JSON
 */
function mobile_banner_count() 
{
    $output['count']=0;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Banner');  
    $output['count']=$lilo_mongo->count();
    return json_encode($output);
}
?>