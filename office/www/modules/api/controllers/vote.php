<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Vote extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count Vote 
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
            $this->mongo_db->select_collection("Votes");        
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
     * API Get One Detail Vote
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
            $this->mongo_db->select_collection("Votes");         
            $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($id) ));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "enabled" => (bool)$tempdata['enabled'],
                    "name" => $tempdata['name'],
                    "question" => $tempdata['question'],
                    "count" => $tempdata['count'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Vote
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
            $this->mongo_db->select_collection("Votes");     
            $tempdata = $this->mongo_db->find(array("enabled"=>"true"),0,0,array("name"=>1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "enabled" => (bool)$dt['enabled'],
                        "name" => $dt['name'],
                        "question" => $dt['question'],
                        "count" => $dt['count'],
                    );
                }                
                $output['data'] = $listdata;
                $output['count'] = (int)$this->mongo_db->count2(array("enabled"=>"true"));
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get One Increment Vote
     * Parameter :
     * 1. id
     * Return JSON
     */
    function inc_one($id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail update data";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Votes");         
            $tempdata = $this->mongo_db->update(array("_id"=> $this->mongo_db->mongoid($id)),array('$inc'=>array("count"=>1)));
            if($tempdata)
            {
                $url = current_url();
                $this->m_user->tulis_log("Increment Vote",$url,"API Unity");
                $output['success'] = TRUE;
                $output['message'] = "Success update data";
            }
        }
        echo json_encode($output);
    }
}