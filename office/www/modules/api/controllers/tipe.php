<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tipe extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get list Avatar Bodypart tipe
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
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("AvatarBodyPart");
            $output['count'] = (int)$this->mongo_db->count2(array());
            $tempdata = $this->mongo_db->find(array(),0,0,array("name"=>1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "name" => $dt['name'],
                        "title" => $dt['title'],
                        "parent" => $dt['parent'],
                    );
                }                
                $output['data'] = $listdata;                
            }
        }
        echo json_encode($output);
    }
}
