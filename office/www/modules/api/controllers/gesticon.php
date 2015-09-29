<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gesticon extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Gesticon
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
            $this->mongo_db->select_collection("Gesticon");        
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
     * API Get List of Gesticon
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
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("Gesticon"); 
            $output['count'] = (int)$this->mongo_db->count2(array());
            $tempdata = $this->mongo_db->find(array(),0,0,array("gender"=>1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "command" => $dt['command'],
                        "gender" => $dt['gender'],
                        "animation" => $dt['animation']
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
