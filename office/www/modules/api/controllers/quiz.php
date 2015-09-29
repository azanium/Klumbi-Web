<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quiz extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Quiz
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
            $this->mongo_db->select_collection("Quiz");        
            $tempdata = $this->mongo_db->count2(array("State"=>"publish"));
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
     * API Get One Detail Quiz
     * Parameter :
     * 1. ID
     * Return JSON
     */
    function get_one($ID="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Quiz");      
            $tempdata = $this->mongo_db->findOne(array("ID"=>$ID));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "_id" => (string)$tempdata['_id'],
                    "ID" => $tempdata['ID'],
                    "Title" => $tempdata['Title'],
                    "Description" => $tempdata['Description'],
                    "Count" => $tempdata['Count'],
                    "BrandId" => $tempdata['BrandId'],
                    "State" => $tempdata['State'],
                    "StartDate" => $tempdata['StartDate'],
                    "StartTime" => $tempdata['StartTime'],
                    "EndDate" => $tempdata['EndDate'],
                    "EndTime" => $tempdata['EndTime'],
                    "IsRandom" => (bool)$tempdata['IsRandom'],
                    "RequiredQuiz" => $tempdata['RequiredQuiz'],
                    "RequiredQuest" => $tempdata['RequiredQuest'],
                    "RequiredItem" => $tempdata['RequiredItem'],
                    "Reward" => $tempdata['Reward'],
                    "Questions" => $tempdata['Questions']
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Quiz
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
            $this->mongo_db->select_collection("Quiz");
            $tempdata = $this->mongo_db->find(array("State"=>"publish"),(int)$start,(int)$limit,array("Title"=>1));
            $output['count'] = (int)$this->mongo_db->count2(array("State"=>"publish"));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "_id" => (string)$dt['_id'],
                        "ID" => $dt['ID'],
                        "Title" => $dt['Title'],
                        "Description" => $dt['Description'],
                        "Count" => $dt['Count'],
                        "BrandId" => $dt['BrandId'],
                        "State" => $dt['State'],
                        "StartDate" => $dt['StartDate'],
                        "StartTime" => $dt['StartTime'],
                        "EndDate" => $dt['EndDate'],
                        "EndTime" => $dt['EndTime'],
                        "IsRandom" => $dt['IsRandom'],
                        "RequiredQuiz" => $dt['RequiredQuiz'],
                        "RequiredQuest" => $dt['RequiredQuest'],
                        "RequiredItem" => $dt['RequiredItem'],
                        "Reward" => $dt['Reward'],
                        "Questions" => $dt['Questions']
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
