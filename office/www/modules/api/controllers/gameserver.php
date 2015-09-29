<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gameserver extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Data Game Server
     * Parameter :
     * Return JSON
     */
    function index()
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        { 
            $this->mongo_db->select_db("Servers");
            $this->mongo_db->select_collection("GameServer");
            $output['count'] = (int)$this->mongo_db->count2(array());
            $tempdata = $this->mongo_db->find(array(),0,0,array("name"=>1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "ip" => $dt['ip'],
                        "port" => $dt['port'],
                        "max_ccu" => (int)$dt['max_ccu'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
