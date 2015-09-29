<?php 
include_once('libraries/LiloMongo.php');
include_once('config/config.php');
/*
 * API for Get List News
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/stream/list/0/10/
 * Return : JSON
 */
function mobile_stream_list() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    $output=array();
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('ContentNews');
    $filter=array("state_document"=>"publish");
    $temp_data = $lilo_mongo->find_pagging($filter, $start_from, $limit,array("update"=>-1));    
    $output['count']=$lilo_mongo->count($filter);
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $tgl="";
            if ($dt['update'] != "") 
            {
                $tgl = date('Y-m-d H:i:s', $dt['update']->sec);
            }
            $path_upload = URL_ASSET_IMAGE."images/";
            $title=(!isset($dt['title'])?"":$dt['title']);
            $text=(!isset($dt['text'])?"":$dt['text']);
            $state_document=(!isset($dt['state_document'])?"":$dt['state_document']);
            $picture=(!isset($dt['picture'])?"":$dt['picture']);
            $output['data'][] = array(
                'id' => (string)$dt['_id'],
                'title' => $title,
                'text' => replace_text_content(filter_text($text)),
                'update' => $tgl,
                'imageName' => $picture,
                'imagePath' => $path_upload,
                'state_document' => $state_document,
            );
        }
    }
    return json_encode($output);
}
/*
 * API for get List News by _id
 * Methode: GET
 * Urutan Parameter
 * 1. _id news.
 * Example : [server host]/[server path]/api/mobile/stream/news/[_id news]
 * Return : JSON
 */
function mobile_stream_news() 
{
    $_id=(func_arg(0)!="")?func_arg(0):"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('ContentNews');
    $dt = $lilo_mongo->findOne(array("_id"=>$lilo_mongo->mongoid($_id)));
    $output=array(
        'title' => "No Title",
        'text' => "no Text",
        'update' => date("Y-m-d H:i:s"),
        'state_document' => "",
    );
    if($dt)
    {
        $tgl="";
        if ($dt['update'] != "") 
        {
            $tgl = date('Y-m-d H:i:s', $dt['update']->sec);
        }
        $path_upload = URL_ASSET_IMAGE."images/";
        $title=(!isset($dt['title'])?"":$dt['title']);
        $text=(!isset($dt['text'])?"":$dt['text']);
        $state_document=(!isset($dt['state_document'])?"":$dt['state_document']);
        $picture=(!isset($dt['picture'])?"":$dt['picture']);
        $output = array(
            'title' => $title,
            'text' => replace_text_content(filter_text($text)),
            'update' => $tgl,
            'imageName' => $picture,
            'pathImage' => $path_upload,
            'state_document' => $state_document,
        );
    }
    return json_encode($output);
}
/*
 * API for Get Count Content
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/stream/count
 * Return : JSON
 */
function mobile_stream_count() 
{
    $output['count']=0;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('ContentNews');  
    $output['count']=$lilo_mongo->count(array("state_document"=>"publish"));
    return json_encode($output);
}
?>