<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Random Data
     * Parameter :
     * 1. count data random
     * Return JSON
     */
    function random($jml=5)
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $output['success'] = TRUE;
            $output['data'] = $this->tambahan_fungsi->global_get_random((int)$jml);
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Template Default
     * Parameter :
     * 1. code {keytemplate}
     * Return JSON
     */
    function data_template($keytemplate="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");       
            $tempdata = $this->mongo_db->findOne(array("code"=>$keytemplate));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "type" => $tempdata['type'],
                    "value" => $tempdata['value'],
                    "descriptions" => $tempdata['descriptions'],
                );
            }
        }
        echo json_encode($output);
    }
}
