<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Event extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Calendar Event","module9",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(                        
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/fullcalendar/fullcalendar.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
            base_url()."resources/plugin/jquery-fileupload/css/jquery.fileupload-ui.css",
            base_url()."resources/css/jqueryui.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",            
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/fullcalendar/fullcalendar.min.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-colorpicker/js/bootstrap-colorpicker.min.js",
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
            base_url()."resources/plugin/jquery-fileupload/js/vendor/jquery.ui.widget.js",
            base_url()."resources/plugin/jquery-fileupload/js/jquery.fileupload.js",
        );
        $this->template_admin->header_web(TRUE,"Calendar Event",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("event_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function cruid_event()
    {        
        $this->form_validation->set_rules('name','Event Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txturl','Url Site','trim|prep_url|xss_clean');
        $this->form_validation->set_rules('txtfileimgname','Image','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdatebegin','Date Begin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdateend','Date End','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimebegin','Time Begin','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimeend','Time End','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcolor','Color','trim|required|htmlspecialchars|xss_clean');
	$this->form_validation->set_rules('txtdescriptions','Description','trim|required|htmlspecialchars|xss_clean'); 
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
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $txturl = $this->input->post('txturl',TRUE);
            $datebegin = $this->input->post('txtdatebegin',TRUE);
            $dateend = $this->input->post('txtdateend',TRUE);
            $timebegin = $this->input->post('txttimebegin',TRUE);
            $timeend = $this->input->post('txttimeend',TRUE);
            $color = $this->input->post('txtcolor',TRUE);
            $description = $this->input->post('txtdescriptions',TRUE);
            $picture = $this->input->post('txtfileimgname',TRUE);
            $allday = isset($_POST['txtchkall'])?TRUE:FALSE;
            $isitanggalawal = $this->mongo_db->time(strtotime($datebegin . " ". $timebegin));
            $isitanggalakhir = $this->mongo_db->time(strtotime($dateend . " ". $timeend));
            $datatinsert=array(
                'allDay'  =>$allday,
                'title'  =>$name,
                'start'  => $isitanggalawal,
                'color'  =>$color,    
                'description'  =>$description,
                'url'  =>$txturl,
                'end'  => $isitanggalakhir,
            );
            if($picture!="")
            {
                $datatinsert= array_merge($datatinsert,array('picture'  =>$picture));
            }
            if($id==='')
            {
                $this->m_checking->actions("Calendar Event","module9","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Event");
                if($picture === "")
                {
                    $datatinsert= array_merge($datatinsert,array('picture'  =>""));
                }
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add New Event",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Calendar Event","module9","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Articles");
                $this->mongo_db->select_collection("Event");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Event",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
            } 
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('setting/event/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Calendar Event","module9","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("Event");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Data Event",$url,$user);
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
            redirect('setting/event/index'); 
        }
    }
}
