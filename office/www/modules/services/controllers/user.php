<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"asc";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                'username'=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('username'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $actionmenu=$this->template_icon->detail_onclick("getdatauserval('".$dt['_id']."')","",'Set Value',"bullet_go.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $dt['username'],
                $dt['email'],
                $actionmenu,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect($this->session->userdata('urlsebelumnya')); 
        }
    }
}
