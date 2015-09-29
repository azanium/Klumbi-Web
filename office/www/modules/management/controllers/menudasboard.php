<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menudasboard extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Menu Dasboard","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Menu Frontend",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("menudasboard_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Website");
        $this->mongo_db->select_collection("Bigmenu");
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
                'Alias'=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('Order'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $order="";
            $priview="";
            $edit="";
            if($this->m_checking->actions("Menu Dasboard","module2","Edit",TRUE,FALSE,"home"))
            {
                $edit=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."','".$dt['Alias']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Menu Dasboard","module2","Order",TRUE,FALSE,"home"))
            {
                $order = $this->template_icon->detail_onclick("orderlist('".$dt['_id']."','".$dt['Order']."','Down')","",'Down',"bullet_arrow_down.png","","linkdelete");
                $order .= $this->template_icon->detail_onclick("orderlist('".$dt['_id']."','".$dt['Order']."','Up')","",'Up',"bullet_arrow_up.png","","linkdelete");
            }
            if($this->m_checking->actions("Menu Dasboard","module2","State",TRUE,FALSE,"home"))
            {
                $priview=$this->template_icon->detail_onclick("changeto('".$dt['_id']."','".$dt['State']."')","",'Show',"flag_blue.png","","","");
            }
            $output['aaData'][] = array(
                $i,
                $dt['Alias'],
                $dt['Order'],
                ($dt['State']==TRUE)?"<i class='label-success pading_5'>Active</i>":"<i class='label-danger pading_5'>Non-active</i>",
                $order.$priview.$edit,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/user/index'); 
        }
    }
    function changename()
    {
        $this->form_validation->set_rules('txtid','Menu ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','New Name','trim|htmlspecialchars|xss_clean');
        $output['message'] = "<i class='error'>Sorry, You are not allowed to access this page</i>";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Menu Dasboard","module2","Edit",FALSE,FALSE,"home");
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Bigmenu");
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('Alias'=>$name)));
            $output['message'] = "<i class='success'>Data is updated</i>";
            $this->m_user->tulis_log("Update Name of Menu Dasboard",$url,$user);
            $output['success'] = TRUE;           
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/menudasboard/index'); 
        }
    }
    function cruid_show()
    {
        $this->form_validation->set_rules('txtid','Menu ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtvalue','New State','trim|htmlspecialchars|xss_clean');
        $output['message'] = "<i class='error'>Sorry, You are not allowed to access this page</i>";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Menu Dasboard","module2","State",FALSE,FALSE,"home");
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Bigmenu");
            $id = $this->input->post('txtid',TRUE);
            $state = !(bool)$this->input->post('txtvalue',TRUE);
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('State'=>$state)));
            $output['message'] = "<i class='success'>Data is updated</i>";
            $this->m_user->tulis_log("Update State of Menu Dasboard",$url,$user);
            $output['success'] = TRUE;           
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/menudasboard/index'); 
        }
    }
    function order_menu()
    {
        $this->form_validation->set_rules('txtid','Menu ID','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtorder','State Order','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcurentorder','Value Order','trim|required|htmlspecialchars|xss_clean');
        $output['message'] = "<i class='error'>Sorry, You are not allowed to access this page</i>";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Menu Dasboard","module2","Order",FALSE,FALSE,"home");
            $this->mongo_db->select_db("Website");
            $this->mongo_db->select_collection("Bigmenu");
            $id = $this->input->post('txtid',TRUE);
            $state = $this->input->post('txtorder',TRUE);
            $valorder = $this->input->post('txtcurentorder',TRUE);
            $bigval = $this->mongo_db->count();
            $tempdate = $this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
            $neworder = "";
            if($tempdate)
            {
                if($state==="Up")
                {
                    $neworder = $tempdate['Order'] - 1;
                }
                else
                {
                    $neworder = $tempdate['Order'] + 1;
                }
            }
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('Order'=>$neworder)));
            $output['message'] = "<i class='success'>Order is updated</i>";
            $this->m_user->tulis_log("Update Order of Menu Dasboard",$url,$user);
            $output['success'] = TRUE;           
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('management/menudasboard/index'); 
        }
    }
}
