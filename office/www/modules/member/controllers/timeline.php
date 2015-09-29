<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Timeline extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/css/timeline.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"My Timeline",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("timeline_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function save()
    {        
        $this->form_validation->set_rules('txtstatus','Text Status','trim|required|htmlspecialchars|xss_clean');
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "";
        $output['success'] = FALSE;
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = (string)$this->session->userdata('user_id');
            $txtstatus = $this->input->post('txtstatus',TRUE);
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $datatinsert = array(
                'type'=>'StateOfMind',
                "StateMind" => $txtstatus,
                "user_id" => $id,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
            );
            $this->mongo_db->insert($datatinsert);
            $this->m_user->tulis_log("Add New Status",$url,$user);
            $output['message'] = "<i class='success'>New Data is added</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('member/timeline/index'); 
        }
    }
}
