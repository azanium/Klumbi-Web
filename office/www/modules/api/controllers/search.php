<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    }  
    /*
     * Methode : GET
     * API Search People
     * Parameter :
     * 1. keysearch
     * Return JSON
     */
    function people($start=0)
    {  
        $keysearch = isset($_GET['keysearch'])?$_GET['keysearch']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['follow'] = FALSE;
        $output['message'] = "No user found";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties"); 
            $pencarian=array();
            if($keysearch!="")
            {
                $sSearch=(string) trim($keysearch);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter = $this->mongo_db->regex($regex);
                $pencarian= array(
                    'fullname'=>$filter,
                );
            }
            $tempdata = $this->mongo_db->find($pencarian,(int)$start,100,array('join_date'=>1));
            $output['count'] = (int)$this->mongo_db->count2($pencarian);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tempdtuser = $this->m_userdata->user_account_byid($dt["lilo_id"]);
                    $listdata[] = array(
                        "_id" => $dt['lilo_id'],
                        "avatarname" => $dt['avatarname'],
                        "fullname" => $dt['fullname'],
                        "picture" => $dt['picture'],
                        "email" => $tempdtuser['email'],
                        "username" => $tempdtuser['username'],
                        "fb_id" => $tempdtuser['username'],
                        "twitter_id" => $tempdtuser['username']
                    );
                }
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}