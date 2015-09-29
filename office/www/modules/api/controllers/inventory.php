<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inventory extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Inventory
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
            $this->mongo_db->select_collection("Inventory");        
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
     * API Get One Detail Inventory
     * Parameter :
     * 1. _id Field _id from database Assets collection Inventory (this param is required)
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
            $this->mongo_db->select_collection("Inventory");        
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($_id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "picture" => $tempdata['file'],
                    "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['file'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Inventory
     * Parameter :
     * Return JSON
     */
    function list_data()
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Inventory");
            $tempdata = $this->mongo_db->find(array(),0,0,array("name"=>1));
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
                        "picture" => $dt['file'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['file'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
