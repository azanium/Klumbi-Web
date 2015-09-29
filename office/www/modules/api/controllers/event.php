<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }    
    /*
     * Methode : GET
     * API Get Count Event 
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
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("Event");        
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
     * API Get One Detail Event
     * Parameter :
     * 1. _id field _id from Database Articles dan collection Event (this param is required)
     * Return JSON
     */
    function get_one($_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("Event");        
            $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($_id) ));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['data'] = array(
                    "id" => (string)$tempdata['_id'],
                    "allDay" => (bool)$tempdata['allDay'],
                    "title" => $tempdata['title'],
                    "color" => $tempdata['color'],
                    "description" => $tempdata['description'],
                    "url" => $tempdata['url'],
                    "start_date" => date('Y-m-d',$tempdata['start']->sec),
                    "start_time" => date('H:i:s',$tempdata['start']->sec),
                    "end_date" => date('Y-m-d',$tempdata['end']->sec),
                    "end_time" => date('H:i:s',$tempdata['end']->sec),
                    "picture" => $tempdata['picture'],
                    "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $tempdata['picture']
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Event
     * Parameter :
     * 1. start
     * 2. end
     * Return JSON
     */
    function list_data($start="",$end="")
    {  
        $filter = array(); 
        if($start!="")
        {
            $filter = array(
                        "start" => array( 
                                '$gte'=> $this->mongo_db->time((double) strtotime($start." 00:00:00")), 
                                '$lt'=>  $this->mongo_db->time((double) strtotime($end." 23:59:59"))
                        )
                );
        }
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {       
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("Event");        
            $tempdata = $this->mongo_db->find($filter,0,0,array("start"=>-1));
            $output['count'] = (int)$this->mongo_db->count2($filter);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $listdata[] = array(
                        "id" => (string)$dt['_id'],
                        "allDay" => (bool)$dt['allDay'],
                        "title" => $dt['title'],
                        "color" => $dt['color'],
                        "description" => $dt['description'],
                        "url" => $dt['url'],
                        "start_date" => date('Y-m-d',$dt['start']->sec),
                        "start_time" => date('H:i:s',$dt['start']->sec),
                        "end_date" => date('Y-m-d',$dt['end']->sec),
                        "end_time" => date('H:i:s',$dt['end']->sec),
                        "picture" => $dt['picture'],
                        "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $dt['picture']
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}