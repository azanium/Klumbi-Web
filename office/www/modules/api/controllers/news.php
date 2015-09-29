<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count News
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
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("ContentNews");        
            $tempdata = $this->mongo_db->count2(array("state_document"=>"publish"));
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
     * API Get One Detail News
     * Parameter :
     * 1. _id 
     * Return JSON
     */
    function get_one($_id="")
    {           
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("ContentNews");        
            $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($_id), "state_document"=>"publish"));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "title" => $tempdata['title'],
                    "alias" => $tempdata['alias'],
                    "text" => $tempdata['mobile'],
                    "update" => date('Y-m-d H:i:s',$tempdata['update']->sec),
                    "state_document" => $tempdata['state_document']
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of News
     * Parameter :
     * 1. page
     * 2. search
     * Return JSON
     */
    function list_data($start=0)
    {  
        $valuesearch = isset($_GET['search'])?$_GET['search']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitnews"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("ContentNews");   
            $filtering = array();
            if($valuesearch!="")
            {
                $sSearch=(string) trim($valuesearch);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
                $filtering = array(
                    "title" => $filter,
                );
            }
            $filtering = array_merge($filtering,array("state_document"=>"publish"));
            $output['count'] = $this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,(int)$start,(int)$limit,array("update"=>-1));
            $output['count'] = (int)$this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "title" => $dt['title'],
                        "alias" => $dt['alias'],
                        "text" => $dt['mobile'],
                        "update" => date('Y-m-d H:i:s',$dt['update']->sec),
                        "state_document" => $dt['state_document']
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}