<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Category extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get list Category
     * Parameter :
     * 1. tipename
     * Return JSON
     */
    function index($tipename="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {  
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Category");
            $output['count'] = $this->mongo_db->count2(array('tipe'  =>$tipename));
            $tempdata = $this->mongo_db->find(array('tipe'  =>$tipename),0,0,array("name"=>1));
            $output['count'] = (int)$this->mongo_db->count2(array('tipe'  =>$tipename));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $fileimage = isset($dt['image'])?$dt['image']:"";
                    $listdata[] = array(
                        "name" => $dt['name'],
                        "image" => $fileimage,
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $fileimage,
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
