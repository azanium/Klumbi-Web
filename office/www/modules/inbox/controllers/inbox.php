<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Inbox extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
        $this->load->helper('text');
        $this->load->library(array('pagination','form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
    }
    function index($keymessage="inbox")
    {
        $start = (isset($_GET["per_page"])?$_GET["per_page"]:0);
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $keysrc = (isset($_GET['q'])?$_GET['q']:"");
        $datapage['keysearch'] = $keysrc;
        if($keymessage === "send")
        {
            $filteringmesagelist = array('type'=>"send","friend_id" => (string)$this->session->userdata('user_id'));
        }
        else if($keymessage === "important")
        {
            $filteringmesagelist = array('type'=>"important","friend_id" => (string)$this->session->userdata('user_id'));
        }
        else if($keymessage === "search")
        {
            if($keysrc!="")
            {
                $sSearch=(string) trim($keysrc);
                $sSearch = quotemeta($sSearch);
                $regex = "/$sSearch/i";
                $filter=$this->mongo_db->regex($regex);
            }
            $filteringmesagelist = array("subject" => $filter , "friend_id" => (string)$this->session->userdata('user_id'));
        }
        else
        {
            $filteringmesagelist = array('type'=>array('$in'=>array("inbox","bcmesage")),"friend_id" => (string)$this->session->userdata('user_id'));
        }
        $datapage['unreadinbox'] = (int)$this->mongo_db->count2(array('type'=>array('$in'=>array("inbox","bcmesage")),'read'=>FALSE,"friend_id" => (string)$this->session->userdata('user_id')));
        $datapage['unreadimportant'] = (int)$this->mongo_db->count2(array('type'=>"important",'read'=>FALSE,"friend_id" => (string)$this->session->userdata('user_id')));
        $config['base_url'] = $this->template_admin->link("inbox/index/".$keymessage,"?q=".$keysrc);
        $config['total_rows'] = $this->mongo_db->count2($filteringmesagelist);
        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $config['num_links'] = 1;
        $config['page_query_string'] = TRUE;
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<i class="icon-long-arrow-left"></i>';
        $config['first_tag_open'] = '<li class="previous">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="icon-long-arrow-right"></i>';
        $config['last_tag_open'] = '<li class="next">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="icon-double-angle-right"></i>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="icon-double-angle-left"></i>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="disabled active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';        
        $this->pagination->initialize($config);
        $datapage['paging'] = $this->pagination->create_links();
        $datapage['count']= $config['total_rows'];
        $datapage['pagedetail']= (int)$start." - ".($start + $config['per_page'] )." of ".$config['total_rows'];
        $datapage['datalist'] = $this->mongo_db->find($filteringmesagelist,(int)$start,$config['per_page'],array('datetime'=>-1));           
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/form-select2/select2.css",
            base_url()."resources/plugin/form-multiselect/css/multi-select.css", 
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-multiselect/js/jquery.multi-select.min.js",
            base_url()."resources/plugin/quicksearch/jquery.quicksearch.min.js",
            base_url()."resources/plugin/form-typeahead/typeahead.min.js",
            base_url()."resources/plugin/form-select2/select2.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Inbox",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("inbox_view",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function detail($id="")
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $output['html'] = "<i class='error'>Fail to load message</i>";
        $output['success'] = FALSE;        
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>array('read'=>TRUE)));
            $namapengirim = "Admin ".$this->config->item('aplicationname');
            if($tampung["type"] === "inbox")
            {
                $tempdtuser = $this->m_userdata->user_properties($tampung["user_id"]);
                $namapengirim = $tempdtuser['fullname'];
            } 
            $output['html'] = "<strong>From : ".$namapengirim."</strong>";
            $output['html'] .= "<h4>".$tampung['subject']."</h4>";            
            $output['html'] .= "<p>".$tampung['message']."</p>";
            $output['html'] .= "<i class='text-primary'>".date('l, d F Y H:i:s', $tampung['datetime']->sec)."</i>";
            $output['success'] = TRUE; 
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('inbox/index'); 
        }
    }
    function setmessage()
    {        
        $this->form_validation->set_rules('action','Action Name','trim|required|htmlspecialchars|xss_clean');
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
            $action = $this->input->post('action',TRUE);
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Inbox");
            if(isset($_POST["idchkinbox"]))
            {
                for($i=0;$i<count($_POST["idchkinbox"]);$i++)
                {
                    $filtering = array('_id'=> $this->mongo_db->mongoid($_POST["idchkinbox"][$i]));
                    if($action === "setread")
                    {
                        $dataupdate = array('read'=>TRUE);
                        $this->mongo_db->update($filtering,array('$set'=>$dataupdate));
                    }
                    else if($action === "setunread")
                    {
                        $dataupdate = array('read'=>FALSE);
                        $this->mongo_db->update($filtering,array('$set'=>$dataupdate));
                    }
                    else if($action === "setimportant")
                    {
                        $dataupdate = array('type'=>"important");
                        $this->mongo_db->update($filtering,array('$set'=>$dataupdate));
                    }
                    else if($action === "delete")
                    {
                        $this->mongo_db->remove($filtering);
                    }
                }
            }                
            $this->m_user->tulis_log("Update Message State",$url,$user);
            $output['message'] = "<i class='success'>Data is updated</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('inbox/index'); 
        }
    }
    function delete($id="")
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Inbox");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Message User",$url,$user);
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
            redirect('inbox/index'); 
        }
    }
}




