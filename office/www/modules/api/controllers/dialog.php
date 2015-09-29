<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dialog extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    /*
     * Methode : GET
     * API Get Count Dialog
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
            $this->mongo_db->select_collection("DialogStory");         
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
     * API Get One Detail Dialog Story
     * Parameter :
     * 1. name
     * Return JSON
     */
    function get_one($name="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("DialogStory");        
            $tempdata = $this->mongo_db->findOne(array("name"=>$name));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $datatempdialods= array();
                if ($tempdata['dialogs']) 
                {
                    foreach ($tempdata['dialogs'] as $key => $val) 
                    {
                        $dtoption = array();
                        foreach ($val['options'] as $nilai) 
                        {
                            $dtoption[] = array(
                                'Tipe' => (int) $nilai['option_type'],
                                'Content' => $nilai['description'],
                                'Next' => (int) $nilai['next_id'],
                            );
                        }
                        $datatempdialods[] = array(
                            'ID' => (int) $val['id'],
                            'Description' => $val['description'],
                            'Options' => $dtoption
                        );
                    }
                }
                $output['data'] = array(
                    "_id" => (string)$tempdata['_id'],
                    'name' => $tempdata['name'],
                    'description' => $tempdata['description'],
                    'typedialog' => $tempdata['typedialog'],
                    'dialogs' => $datatempdialods
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Dialog Story
     * Parameter :
     * 1. page
     * Return JSON
     */
    function list_data($start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitstore"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("DialogStory");
            $tempdata = $this->mongo_db->find(array(),(int)$start,(int)$limit,array("name"=>1));
            $output['count'] = (int)$this->mongo_db->count2(array());
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $datatempdialods= array();
                    if($dt['dialogs']) 
                    {
                        foreach ($dt['dialogs'] as $key => $val) 
                        {
                            $dtoption = array();
                            foreach ($val['options'] as $nilai) 
                            {
                                $dtoption[] = array(
                                    'Tipe' => (int) $nilai['option_type'],
                                    'Content' => $nilai['description'],
                                    'Next' => (int) $nilai['next_id'],
                                );
                            }
                            $datatempdialods[] = array(
                                'ID' => (int) $val['id'],
                                'Description' => $val['description'],
                                'Options' => $dtoption
                            );
                        }
                    }
                    $listdata[] = array(
                        "_id" => (string)$dt['_id'],
                        'name' => $dt['name'],
                        'description' => $dt['description'],
                        'typedialog' => $dt['typedialog'],
                        'dialogs' => $datatempdialods,
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
