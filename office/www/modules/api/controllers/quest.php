<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quest extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Quest
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
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quest");         
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
     * API Get One Detail Quest
     * Parameter :
     * 1. ID
     * Return JSON
     */
    function get_one($idquest="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quest");        
            $tempdata = $this->mongo_db->findOne(array("ID"=>$idquest));
            if($tempdata)
            {
                $startdate = "";
                $enddate = "";
                if ($tempdata['StartDate'] != "") 
                {
                    $startdate = date('Y-m-d', $tempdata['StartDate']->sec);
                }
                if ($tempdata['EndDate'] != "") 
                {
                    $enddate = date('Y-m-d', $tempdata['EndDate']->sec);
                }
                $output['success'] = TRUE;
                $output['data'] = array(
                    "_id" => (string)$tempdata['_id'],
                    'IsActive' => (strtolower($tempdata['IsActive']) == 'true' ? TRUE : FALSE),
                    'IsDone' => (strtolower($tempdata['IsDone']) == 'true' ? TRUE : FALSE),
                    'IsReturn' => (strtolower($tempdata['IsReturn']) == 'true' ? TRUE : FALSE),
                    'ID' => intval($tempdata['ID']),
                    'RequiredEnergy' => intval($tempdata['RequiredEnergy']),
                    'Requirement' => intval($tempdata['Requirement']),
                    'Rewards' => $tempdata['Rewards'],
                    'RequiredItem' => $tempdata['RequiredItem'],
                    'Description' => $tempdata['Description'],
                    'DescriptionNormal' => $tempdata['DescriptionNormal'],
                    'DescriptionActive' => $tempdata['DescriptionActive'],
                    'DescriptionDone' => $tempdata['DescriptionDone'],
                    'StartDate' => $startdate,
                    'EndDate' => $enddate
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Quest
     * Parameter :
     * 1. page
     * Return JSON
     */
    function list_data($start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitstore"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quest");
            $tempdata = $this->mongo_db->find(array(),(int)$start,(int)$limit,array("Description"=>1));
            $output['count'] = (int)$this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $startdate = "";
                    $enddate = "";
                    if ($dt['StartDate'] != "") 
                    {
                        $startdate = date('Y-m-d', $dt['StartDate']->sec);
                    }
                    if ($dt['EndDate'] != "") 
                    {
                        $enddate = date('Y-m-d', $dt['EndDate']->sec);
                    }
                    $listdata[] = array(
                        "_id" => (string)$dt['_id'],
                        'IsActive' => (strtolower($dt['IsActive']) == 'true' ? TRUE : FALSE),
                        'IsDone' => (strtolower($dt['IsDone']) == 'true' ? TRUE : FALSE),
                        'IsReturn' => (strtolower($dt['IsReturn']) == 'true' ? TRUE : FALSE),
                        'ID' => intval($dt['ID']),
                        'RequiredEnergy' => intval($dt['RequiredEnergy']),
                        'Requirement' => intval($dt['Requirement']),
                        'Rewards' => $dt['Rewards'],
                        'RequiredItem' => $dt['RequiredItem'],
                        'Description' => $dt['Description'],
                        'DescriptionNormal' => $dt['DescriptionNormal'],
                        'DescriptionActive' => $dt['DescriptionActive'],
                        'DescriptionDone' => $dt['DescriptionDone'],
                        'StartDate' => $startdate,
                        'EndDate' => $enddate
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
