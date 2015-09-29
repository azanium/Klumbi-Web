<?php 
include_once('libraries/LiloMongo.php');
/*
 * API for Get List Article
 * Methode: GET
 * Urutan Parameter
 * 1. Start Page from
 * 2. Limit Data
 * Example : [server host]/[server path]/api/mobile/article/list/0/10/
 * Return : JSON
 */
function mobile_article_list() 
{
    $start_from = (int)func_arg(0);
    $limit = (int)func_arg(1);
    
    $output=array();
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('Article');
    $filter=array("state_document"=>"publish");
    $temp_data = $lilo_mongo->find_pagging($filter, $start_from, $limit,array("document_update"=>-1));    
    $output['count']=$lilo_mongo->count($filter);
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $tgl="";
            if ($dt['document_update'] != "") 
            {
                $tgl = date('Y-m-d H:i:s', $dt['document_update']->sec);
            }
            $title=(!isset($dt['title'])?"":$dt['title']);
            $text=(!isset($dt['text'])?"":$dt['text']);
            $alias=(!isset($dt['alias'])?"":$dt['alias']);
            $state_document=(!isset($dt['state_document'])?"":$dt['state_document']);
            $output['data'][] = array(
                '_id' => (string)$dt['_id'],
                'title' => $title,
                'alias' => $alias,
                'text' => replace_text_content(filter_text($text)),
                'document_update' => $tgl,
                'state_document' => $state_document,
            );
        }
    }
    return json_encode($output);
}
/*
 * API for get Detail Article _id
 * Methode: GET
 * Urutan Parameter
 * 1. _id Article.
 * Example : [server host]/[server path]/api/mobile/article/detail/[_id Content]
 * Return : JSON
 */
function mobile_article_detail() 
{
    $_id=(func_arg(0)!="")?func_arg(0):"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('Article');
    $dt = $lilo_mongo->findOne(array("_id"=>$lilo_mongo->mongoid($_id)));
    $output=array(
        'title' => "No Title",
        'text' => "no Text",
        'alias' => "no alias",
        'document_update' => date("Y-m-d H:i:s"),
        'state_document' => "",
    );
    if($dt)
    {
        $tgl="";
        if ($dt['document_update'] != "") 
        {
            $tgl = date('Y-m-d H:i:s', $dt['document_update']->sec);
        }
        $title=(!isset($dt['title'])?"":$dt['title']);
        $text=(!isset($dt['text'])?"":$dt['text']);
        $alias=(!isset($dt['alias'])?"":$dt['alias']);
        $state_document=(!isset($dt['state_document'])?"":$dt['state_document']);
        $output = array(
            'title' => $title,
            'alias' => $alias,
            'text' => replace_text_content(filter_text($text)),
            'document_update' => $tgl,
            'state_document' => $state_document,
        );
    }
    return json_encode($output);
}
/*
 * API for Get Count Article
 * Methode: GET
 * Example : [server host]/[server path]/api/mobile/article/count
 * Return : JSON
 */
function mobile_article_count() 
{
    $output['count']=0;
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Articles');
    $lilo_mongo->selectCollection('Article');  
    $output['count']=$lilo_mongo->count(array("state_document"=>"publish"));
    return json_encode($output);
}
?>