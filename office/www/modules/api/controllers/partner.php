<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partner extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Partner
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
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Partner");        
            $tempdata = $this->mongo_db->count2(array("state"=>"Approved"));
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
     * API Get One Detail Partner
     * Parameter :
     * 1. _id field _id from database Website collection Partner (this param is required)
     * Return JSON
     */
    function get_one($_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Partner");     
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($_id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "partner_id" => $tempdata['partner_id'],
                    "address" => $tempdata['address'],
                    "PIC" => $tempdata['PIC'],
                    "phone" => $tempdata['phone'],
                    "mobile" => $tempdata['mobile'],
                    "email" => $tempdata['email'],
                    "website" => $tempdata['website'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Partner
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
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitpartner"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Partner");
            $dtorder = 1;
            if($ordering==="za")
            {
                $dtorder = -1;
            }
            $tempdata = $this->mongo_db->find(array("state"=>"Approved"),(int)$start,(int)$limit,array("name"=>$dtorder));
            $output['count'] = (int)$this->mongo_db->count2(array("state"=>"Approved"));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "partner_id" => $dt['partner_id'],
                        "address" => $dt['address'],
                        "PIC" => $dt['PIC'],
                        "phone" => $dt['phone'],
                        "mobile" => $dt['mobile'],
                        "email" => $dt['email'],
                        "website" => $dt['website'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
