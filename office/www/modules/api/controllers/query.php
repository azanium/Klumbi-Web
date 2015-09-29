<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Query extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Data Lobby Setting
     * Parameter :
     * Return JSON
     */
    function lobby()
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("LobbySetting");
            $tempdata = $this->mongo_db->findOne(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "IP"=>$tempdata['ip'],
                    "PORT"=>$tempdata['port']
                );
            }
        }
        echo json_encode($output);
    }     
}
