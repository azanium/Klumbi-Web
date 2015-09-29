<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Newssocial extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function datalike($id_news="")
    {
        $this->m_checking->actions("News Stream","module9","View Likes",FALSE,TRUE,"content/news/index");
        $add_properties['id_news']=$id_news;
        $css=array(                        
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
                );
        $js=array(
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js", 
                );
        $this->template_admin->header_web(TRUE,"News Stream Loves",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("newslike_view",$add_properties);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }    
    function list_itemdata($id_news="")
    {
        $this->m_checking->actions("News Stream","module9","View Likes",FALSE,TRUE,"content/news/index");
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $pencarian=array('id'=>$id_news,'type'=>'NewsLove');
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('datetime' =>-1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $email="";
            $username="";
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $temp_user=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$dt['user_id'])));
            if($temp_user)
            {
                $email=(!isset($temp_user['email'])?"":$temp_user['email']); 
                $username=(!isset($temp_user['username'])?"":$temp_user['username']); 
            }
            $output['aaData'][] = array(
                $i,
                $email,
                $username,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('content/newssocial/datalike/'.$id_news); 
        }
    }
    function datacomments($id_news="")
    {
        $this->m_checking->actions("News Stream","module9","View Comments",FALSE,TRUE,"content/news/index");
        $add_properties['id_news']=$id_news;
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
        );
        $this->template_admin->header_web(TRUE,"New Stream Comments",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("newscomment_view",$add_properties);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_itemdatacommet($id_news="")
    {
        $this->m_checking->actions("News Stream","module9","View Comments",FALSE,TRUE,"content/news/index");
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $pencarian=array('id'=>$id_news,'type'=>'NewsComment');
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('datetime' =>-1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $email="";
            $username="";
            $delete="";
            $comment=(!isset($dt['comment'])?"":$dt['comment']);
            $date=(!isset($dt['datetime'])?"":date('Y-m-d H:i:s', $dt['datetime']->sec));
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $temp_user=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$dt['user_id'])));
            if($temp_user)
            {
                $email=(!isset($temp_user['email'])?"":$temp_user['email']); 
                $username=(!isset($temp_user['username'])?"":$temp_user['username']); 
            }
            if($this->m_checking->actions("News Stream","module9","Delete Comments",TRUE,FALSE,"content/news/index"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete this Comment ?')","",'Delete',"delete.png","","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $email,
                $username,
                $comment,
                $date,
                $delete,                
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('content/newssocial/datacomments/'.$id_news); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("News Stream","module9","Delete Comments",FALSE,FALSE,"content/news/index");
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Comment on News Stream",$url,$user);
        $output = array(
            "message" =>"Data is deleted",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('content/news/index'); 
        }
    }
}
