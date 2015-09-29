<?php 
include_once('libraries/LiloMongo.php');
/*
 * API for Get List Stream Avatar
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/avastream/list/0/10/
 * Return : JSON
 */
function mobile_avastream_list() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    $output=array();
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('AvatarStream');
    $temp_data = $lilo_mongo->find_pagging(array(), $start_from, $limit,array('dateAdd'=>-1));    
    $output['count']=$lilo_mongo->count();
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $_id=(!isset($dt['_id'])?"":(string)$dt['_id']);
            $ID=(!isset($dt['ID'])?"":$dt['ID']);
            $name=(!isset($dt['name'])?"":$dt['name']);
            $brand_id=(!isset($dt['brand_id'])?"":$dt['brand_id']);
            $type=(!isset($dt['type'])?"":$dt['type']);
            $urlPicture=(!isset($dt['urlPicture'])?"":$dt['urlPicture']);
            $dataValue=(!isset($dt['dataValue'])?"":$dt['dataValue']);
            $picture=(!isset($dt['picture'])?"":$dt['picture']);
            $output['data'][] = array(
                '_id' => $_id,
                'ID' => $ID,
                'name' => $name,
                'brand_id' => $brand_id,
                'type' => $type,
                'dataValue' => $dataValue,
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Get Count Avatar Stream
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/avastream/count
 * Return : JSON
 */
function mobile_avastream_count() 
{
    $output['count']=0;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('AvatarStream');  
    $output['count']=$lilo_mongo->count();
    return json_encode($output);
}
?>