<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Poll extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count Poll 
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
            $this->mongo_db->select_collection("Polls");        
            $tempdata = $this->mongo_db->count2(array("enabled"=>"true"));
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
     * API Get One Detail Poll
     * Parameter :
     * 1. id
     * Return JSON
     */
    function get_one($id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Polls");         
            $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($id) ));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $tgl = "";
                if(isset($tempdata['date']) && $tempdata['date']!="")
                {
                    $tgl = date('Y-m-d',$tempdata['date']->sec);
                }
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "enabled" => (bool)$tempdata['enabled'],
                    "name" => $tempdata['name'],
                    "question" => $tempdata['question'],
                    "date" => $tgl,
                    "options" => $tempdata['options'],
                    "create" => date('Y-m-d H:i:s',$tempdata['create']->sec),
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Poll
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
            $this->mongo_db->select_collection("Polls");     
            $tempdata = $this->mongo_db->find(array("enabled"=>"true"),0,0,array("date"=>-1, "name"=>1));
            $output['count'] = (int)$this->mongo_db->count2(array("enabled"=>"true"));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tgl = "";
                    if(isset($dt['date']) && $dt['date']!="")
                    {
                        $tgl = date('Y-m-d',$dt['date']->sec);
                    }
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "enabled" => (bool)$dt['enabled'],
                        "name" => $dt['name'],
                        "question" => $dt['question'],
                        "date" => $tgl,
                        "options" => $dt['options'],
                        "create" => date('Y-m-d H:i:s',$dt['create']->sec),
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get One Detail Poll
     * Parameter :
     * 1. id
     * Return JSON
     */
    function inc_one($id="",$indexke="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Polls");       
            $dataupdate = array(
                '$inc'=>array('options.'.(int)$indexke.'.values'=>1)
            );
            $filtering = array(
                "_id"=> $this->mongo_db->mongoid($id),
            );
            $tempdata = $this->mongo_db->update($filtering,$dataupdate);
            if($tempdata)
            {
                $url = current_url();
                $this->m_user->tulis_log("Increment Polls",$url,"API Unity");
                $output['success'] = TRUE;
                $output['message'] = "Success update data";
            }
        }
        echo json_encode($output);
    }
}