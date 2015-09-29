<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Animation extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Animation
     * Parameter :
     * gender=[male/female]
     * Return JSON
     */
    function count($gender="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Animation"); 
            $filtering=array();
            if($gender!="")
            {
                $filtering=array("gender"=>$gender);
            }
            $tempdata = $this->mongo_db->count2($filtering);
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
     * API Get One Detail Animation
     * Parameter :
     * 1. _id field _id from Database Assets dan collection Animation (this param is required)
     * Return JSON
     */
    function get_one($_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Animation");        
            $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($_id)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "name" => $tempdata['name'],
                    "permission" => $tempdata['permission'],
                    "gender" => $tempdata['gender'],
                    "description" => $tempdata['description'],                    
                    "animation_file_web" => str_replace(".unity3d", "", $tempdata['animation_file']),
                    "animation_file_ios" => str_replace(".unity3d", "", $tempdata['animation_file_ios']),
                    "animation_file_android" => str_replace(".unity3d", "", $tempdata['animation_file_android']),
                    "picture" => $tempdata['preview_file'],
                    "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['preview_file'],
                    "url_file_web" => $this->config->item('path_asset_img') . "animations/web/" . $tempdata['animation_file'],
                    "url_file_ios" => $this->config->item('path_asset_img') . "animations/iOS/" . $tempdata['animation_file_ios'],
                    "url_file_android" => $this->config->item('path_asset_img') . "animations/Android/" . $tempdata['animation_file_android'],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Animation
     * Parameter :
     * 1. gender=[male/female]
     * Return JSON
     */
    function list_data($gender="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {  
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Animation");
            $filtering=array();
            if($gender!="")
            {
                $filtering = array("gender"=>$gender);
            }
            $tempdata = $this->mongo_db->find($filtering,0,0,array("name"=>1));
            $output['count'] = (int)$this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "name" => $dt['name'],
                        "permission" => $dt['permission'],
                        "gender" => $dt['gender'],
                        "description" => $dt['description'],                        
                        "animation_file_web" => str_replace(".unity3d", "", $dt['animation_file']),
                        "animation_file_ios" => str_replace(".unity3d", "", $dt['animation_file_ios']),
                        "animation_file_android" => str_replace(".unity3d", "", $dt['animation_file_android']),
                        "picture" => $dt['preview_file'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['preview_file'],
                        "url_file_web" => $this->config->item('path_asset_img') . "animations/web/" . $dt['animation_file'],
                        "url_file_ios" => $this->config->item('path_asset_img') . "animations/iOS/" . $dt['animation_file_ios'],
                        "url_file_android" => $this->config->item('path_asset_img') . "animations/Android/" . $dt['animation_file_android'],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Data Annimation
     * Parameter :
     * 1. gender
     * Return JSON
     */
    function commad_animation($gender = "")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Animation");
            $filtering=array();
            if($gender!="")
            {
                $filtering = array("gender"=>$gender);
            }
            $tempdata = $this->mongo_db->command_values(array("distinct" => "Animation", "key" => "animation_file", "query" => $filtering));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata['values'] as $dt)
                {
                    $listdata[] = str_replace(".unity3d", "", $dt);
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
