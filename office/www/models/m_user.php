<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_user extends CI_Model 
{
    public function __construct() 
    {
        parent::__construct();     
    }
    function tulis_log($action,$url,$user)
    {
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("logActivities");
        $datatinsert=array(
            'user'  =>$user,
            'url'  =>$url,
            'datetime'  =>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'action'  =>$action,
        );
        $this->mongo_db->insert($datatinsert);
    }
    function error_log($errnumber,$errmesage,$errdesc,$url,$user)
    {
        $this->mongo_db->select_db("Logs");
        $this->mongo_db->select_collection("Error");
        $datatinsert=array(
            'user'  => $user,
            'url'  => $url,
            'datetime'  => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            'ip_address'  => $this->input->ip_address(),
            "error_number" => $errnumber,
            "error_message" => $errmesage,
            "error_desc" => $errdesc,
        );
        $this->mongo_db->insert($datatinsert);
    }
}