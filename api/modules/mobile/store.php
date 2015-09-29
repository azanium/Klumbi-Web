<?php 
include_once('libraries/LiloMongo.php');
/*
 * Hidden API for root Store
 */
function __store_root($tipe="newest",$id_brand="") 
{
    $output['data'][] = array(
        'tipe' => 'top',
        'filter'=>$tipe,
        'title' => 'Top',
        'picture' => 'top.png',
        'action' => '/Store?f=body&id='.$id_brand,
        'value' => ''
    );    
    $output['data'][] = array(
        'tipe' => 'bottom',
        'filter'=>$tipe,
        'title' => 'Bottom',
        'picture' => 'bottom.png',
        'action' => '/Store?f=pants&id='.$id_brand,
        'value' => ''
    );    
    $output['data'][] = array(
        'tipe' => 'footwear',
        'filter'=>$tipe,
        'title' => 'Footwear',
        'picture' => 'footwear.png',
        'action' => '/Store?f=shoes&id='.$id_brand,
        'value' => ''
    );    
    $output['data'][] = array(
        'tipe' => 'prop',
        'filter'=>$tipe,
        'title' => 'Prop',
        'picture' => 'prop.png',
        'action' => '/Store?f=prop&id='.$id_brand,
        'value' => ''
    );    
    $output['data'][] = array(
        'tipe' => 'mix',
        'filter'=>$tipe,
        'title' => 'My Mix',
        'picture' => 'mix.png',
        'action' => '/Store?f=mix&id='.$id_brand,
        'value' => ''
    );
    $output['count'] =(int) count($output['data']);
    return $output;
}
/*
 * Hidden API for Prop Store
 */
function __store_prop($tipe="newest",$id_brand="") 
{
    $output['data'][] = array(
        'tipe' => 'hat',
        'filter'=>$tipe,
        'title' => 'Headwear',
        'picture' => 'headwear.png',
        'action' => '/Store?f=hat&id='.$id_brand,
        'value' => ''
    );
    $output['count'] =(int) count($output['data']);
    return $output;
}
/*
 * Hidden API for Mix Store
 */
function __store_mix($email,$filter="newest",$brand_id,$start=0,$limit=10) 
{
    $mongo = new LiloMongo();
    
    $userDetails = __getUserDetails($email);
    $id = $userDetails["id"];
    $gender = $userDetails['gender'];
    $bodytype = $userDetails['bodytype'];
    $mongo->selectDB("Users");
    $mongo->selectCollection("AvatarMix");
    $query = $mongo->find_pagging(array('gender' => $gender, 'bodytype' => $bodytype, "brand_id"=>$brand_id),$start,$limit,array());
    $count = $mongo->count(array('gender' => $gender, 'bodytype' => $bodytype, "brand_id"=>$brand_id));
    
    $output = array();
    $output['count'] = $count;
    
    if ($query) {
        
        foreach ($query as $item) {
            $json = $item['configuration'];

            $output['data'][] = array(
                'tipe' => 'Mix',
                'title' => $item['name'],
                'picture' => $item['picture'],
                'action' => '/AvatarPreview?f=character'. '&id=' . (string)$item['_id'] ."&v=".$json,
                'value' => ''
            );
        }   
    }
    
    return $output;
}
/*
 * Hidden API for render Avatar Item
 */
function __explode_avatar_items($query, $gender, $count) {
    $output = array();
    $output['count'] = $count;
    
    foreach ($query as $item) {
        $element = array(
            'gender' => $gender,
            'tipe' => $item['tipe'],
            'element' => str_replace(".unity3d", "", $item['element']),
            'material' => str_replace(".unity3d", "", $item['material'])
        );
        $json = str_replace("\"", "'", json_encode($element));

        $output['data'][] = array(
            'tipe' => $item['tipe'],
            'title' => $item['name'],
            'picture' => $item['preview_image'],
            'action' => '/AvatarPreview?f='.$item['tipe']. "&id=". (string)$item['_id'] . "&v=".$json,
            'value' => ''
        );
    }
    
    return $output;
}
/*
 * Hidden API for Bodypart Store
 */
function __store_bodypart($email, $tipe, $filter, $id_brand, $start, $limit) 
{
    $userDetails = __getUserDetails($email);    
    $output = array();    
    if ($userDetails) 
    {
        $bodytype = !isset($userDetails['bodytype']) || $userDetails['bodytype'] == "" ? "medium" : $userDetails['bodytype'];
        $gender = $userDetails['gender'];
        
        $mongo = new LiloMongo();
        $mongo->selectDB("Assets");
        $mongo->selectCollection("Avatar");
        
        $avatar = null;
        $count = 0;
        
        if ($tipe == "hat") 
        {
            $avatar = $mongo->find_pagging(array('tipe' => $tipe, "brand_id"=>$id_brand), $start, $limit, array());
            $count = $mongo->count(array('tipe' => $tipe, "brand_id"=>$id_brand));
        } 
        else if ($tipe == "shoes") 
        {
            $avatar = $mongo->find_pagging(array('tipe' => $tipe, "brand_id"=>$id_brand), $start, $limit, array());
            $count = $mongo->count(array('tipe' => $tipe, "brand_id"=>$id_brand));
        } 
        else 
        {
            $avatar = $mongo->find_pagging(array('tipe' => $tipe, 'size' => $bodytype, 'gender' => $gender, "brand_id"=>$id_brand), $start, $limit, array());
            $count = $mongo->count(array('tipe' => $tipe, 'size' => $bodytype, 'gender' => $gender, "brand_id"=>$id_brand));
        }
        
        if ($avatar) 
        {
            $output = __explode_avatar_items($avatar, $gender, $count);
        }
        
    }    
    return $output;
}
/*
 * API to get Store Root List
 * Methode: GET
 * Urutan Parameters:
 * 1. user email
 * 2. function: root, prop, mix etc
 * 3. Tipe Filtering option : featured, atoz, newest, popular, all
 * 4. brand_id field (not _id because avatar item save brand_id not _id)
 * 5. Start load data
 * 6. Limit data view
 * Example : [server host]/[server path]/api/mobile/store/list/[user email]/root/all/[_id brand]
 * Return : JSON
 */
function mobile_store_list() 
{
   $email = func_arg(0);
   $func = func_arg(1);
   $filter = func_arg(2);
   $id_brand = func_arg(3);
   $start = func_arg(4);
   $limit = func_arg(5);
   
   $output = array();
   
   if ($func == "root") 
   {
       $output = __store_root($filter,$id_brand);
   }
   else if ($func == "prop") 
   {
       $output = __store_prop($filter,$id_brand);
   }
   else if ($func == "mix") 
   {
       $output = __store_mix($email,$filter,$id_brand,$start,$limit);
   }
   else 
   {
       $output = __store_bodypart($email, $func, $filter, $id_brand, $start, $limit);
   }
   return json_encode($output);
}
/*
 * API for Get List Brand
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/store/brandlist/0/10/
 * Return : JSON
 */
function mobile_store_brandlist() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Brand');
    $temp_data = $lilo_mongo->find_pagging(array(), $start_from, $limit,array());    
    $count = $lilo_mongo->count();
    $data = array();
    
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $brand_id=(!isset($dt['brand_id'])?"":$dt['brand_id']);
            $website=(!isset($dt['website'])?"":$dt['website']);
            $picture=(!isset($dt['picture'])?"":$dt['picture']);
            $data[] = array(
                'brand_id' => $brand_id,
                'website' => $website,
                'title' => $dt['name'],
                'picture' => $picture,
                'action' => '/Store?f=home',
                'value' => ''
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
 * API for Get Count Brand
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/brand/count
 * Return : JSON
 */
function mobile_store_brandcount() 
{
    $output['count']=0;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Brand');  
    $output['count']=$lilo_mongo->count();
    return json_encode($output);
}
?>