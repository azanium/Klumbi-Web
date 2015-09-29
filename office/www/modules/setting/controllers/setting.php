<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Lobby Setting","module2",FALSE,TRUE,"home");
    }
    function index()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("LobbySetting");
        $setting_detail = $this->mongo_db->findOne();
        $isiform['txtid']=set_value('txtid');
        $isiform['ip']=set_value('ip');
        $isiform['port']=set_value('port');
        $isiform['room_history']=set_value('room_history');
        if($setting_detail)
        {
            $isiform['txtid']=$setting_detail['_id'];
            $isiform['ip']=isset($setting_detail['ip'])?$setting_detail['ip']:"";
            $isiform['port']=isset($setting_detail['port'])?$setting_detail['port']:"";
            $isiform['room_history']=isset($setting_detail['room_history'])?$setting_detail['room_history']:"";
        }
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Lobby Setting",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("form_setting",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function cruid_setting()
    {        
        $this->form_validation->set_rules('ip','IP','trim|required|valid_ip|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('port','PORT','trim|numeric|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $this->m_checking->actions("Lobby Setting","module2","Edit",FALSE,FALSE,"home");
            $id = $this->input->post('txtid',TRUE);
            $ip = $this->input->post('ip',TRUE);
            $port = $this->input->post('port',TRUE);
            $room_history = $this->input->post('room_history',TRUE);
            $this->mongo_db->select_db("Game");
            $this->mongo_db->select_collection("LobbySetting");
            $datatinsert=array(
                'ip'  =>$ip,
                'port'=>$port,
                'room_history'=>$room_history,
            );
            $output['success'] = TRUE;
            if($id=='')
            {
                $this->mongo_db->insert($datatinsert);
            }
            else
            {
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert),array('upsert'=>TRUE));
            }
            $output['message']="<p class='success'>Data Update Success fully</p>";
            $url = current_url();
            $user = $this->session->userdata('username');
            $this->m_user->tulis_log("Update Lobby Setting",$url,$user);
        }
        if(IS_AJAX)
        {            
            echo json_encode($output);
        }
        else
        {
            redirect('setting/index'); 
        }
    }
}
