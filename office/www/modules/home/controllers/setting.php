<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Setting extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/form-stepy/jquery.stepy.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
        );
        $this->template_admin->header_web(TRUE,"Edit User Profile",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("setting");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function send()
    {        
        $this->form_validation->set_rules('txtname','Full Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtavatarname','Avatar Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtbirthday','Birthday','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbgender','Gender','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('cmbbodysize','Body Size','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtphone','Phone Number','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtlocation','Location','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtsite','Site','trim|prep_url|xss_clean');
        $this->form_validation->set_rules('txtmessage','State of Mind','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtabout','About','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('status','Status','trim|htmlspecialchars|xss_clean');
        $output['message'] = "";
        $output['success'] = FALSE;
        $url = current_url();
        $user = $this->session->userdata('username');
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $name = $this->input->post('txtname',TRUE);
            $avaname = $this->input->post('txtavatarname',TRUE);
            $birthday = $this->input->post('txtbirthday',TRUE);
            $sex = $this->input->post('cmbgender',TRUE);
            $phone = $this->input->post('txtphone',TRUE);
            $location = $this->input->post('txtlocation',TRUE);
            $website = $this->input->post('txtsite',TRUE);
            $message = $this->input->post('txtmessage',TRUE);
            $about = $this->input->post('txtabout',TRUE);
            $cmbbodysize = $this->input->post('cmbbodysize',TRUE);
            $datapicture = $this->input->post('datapicture',TRUE);
            $status = $this->input->post('status',TRUE);
            $datatinsert=array(
                'fullname'  =>$name,
                'avatarname'  =>$avaname,
                'sex'  =>$sex,
                'handphone'  =>$phone,  
                'location'  =>$location,
                'website'  =>$website,
                'state_of_mind'  =>$message,
                'about'  =>$about,
                'status'  =>$status,
                'bodytype'  =>$cmbbodysize,
            );
            if($message!="")
            {
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social");
                $addnotification = array(
                    'type'=>'ChangeStateOfMind',
                    "user_id"=>(string) $this->session->userdata('user_id'),
                    "text" => $message,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                );
                $this->mongo_db->insert($addnotification);
            }
            if($birthday!="")
            {
                $temptgllahir = explode("-", $birthday);
                $datatinsert = array_merge($datatinsert , array(
                    'birthday'  =>$birthday,  
                    'birthday_dd'  =>$temptgllahir[2],
                    'birthday_mm'  =>$temptgllahir[1],
                    'birthday_yy'  =>$temptgllahir[0],
                ));
            }
            if($datapicture!="")
            {
                $datatinsert = array_merge($datatinsert , array(
                    'picture'  =>$this->config->item('path_asset_img')."preview_images/".$datapicture,
                ));
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social");
                $addnotification = array(
                    'type'=>'ChangePicture',
                    "user_id"=>(string) $this->session->userdata('user_id'),
                    "pic" => $this->config->item('path_asset_img')."preview_images/".$datapicture,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                );
                $this->mongo_db->insert($addnotification);
            }
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $this->mongo_db->update(array('lilo_id'=> (string) $this->session->userdata('user_id')),array('$set'=>$datatinsert));
            $this->m_user->tulis_log("Update User Profile",$url,$user);
            $output['message'] = "<i class='success'>Data is updated</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/setting/index'); 
        }
    }
}
