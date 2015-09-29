<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Brand extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Brand
     * Parameter :
     * Return JSON
     */
    function count()
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");        
            $tempdata = $this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get One Detail Brand
     * Parameter :
     * 1. _id field _id from database Assets collection Brand (this param is required)
     * Return JSON
     */
    function get_one($_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");        
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($_id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "brand_id" => $tempdata['brand_id'],
                    "description" => $tempdata['description'],
                    "picture" => $tempdata['picture'],
                    "poster" => $tempdata['banner'],
                    "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['picture'],
                    "url_poster" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['banner'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Brand
     * Parameter :
     * 1. page
     * 2. ordering = [az / za ]
     * Return JSON
     */
    function list_data($ordering="az",$start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitbrand"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Brand");
            $dtorder = 1;
            if($ordering==="za")
            {
                $dtorder = -1;
            }
            $tempdata = $this->mongo_db->find(array(),(int)$start,(int)$limit,array("name"=>$dtorder));
            $output['count'] = (int)$this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "brand_id" => $dt['brand_id'],
                        "description" => $dt['description'],
                        "picture" => $dt['picture'],
                        "poster" => $dt['banner'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['picture'],
                        "url_poster" => $this->config->item('path_asset_img') . "preview_images/" . $dt['banner'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
