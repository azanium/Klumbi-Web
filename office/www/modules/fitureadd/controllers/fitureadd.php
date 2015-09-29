<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fitureadd extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Polls","module13",FALSE,TRUE,"home");
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
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",            
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",            
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js", 
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
        );
        $this->template_admin->header_web(TRUE,"Polls",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("fitureadd_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Polls");
        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
        $jns_sorting=-1;
        if($sSortDir_0=="asc")
        {
            $jns_sorting=1;
        }
        $keysearchdt="name";
        if($iSortCol_0==1)
        {
            $keysearchdt="name";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="question";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="enabled";
        }
        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
        $pencarian=array();
        if($sSearch!="")
        {
            $sSearch=(string) trim($sSearch);
            $sSearch = quotemeta($sSearch);
            $regex = "/$sSearch/i";
            $filter=$this->mongo_db->regex($regex);
            $pencarian=array(
                $keysearchdt=>$filter,
            );                       
        }
        if($this->session->userdata('brand')!="")
        {
            $pencarian=  array_merge($pencarian,array("brand_id"=>$this->session->userdata('brand')));
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $tglshow="";
            if($dt['date']!="")
            {
                $tglshow=date('Y-m-d', $dt['date']->sec);
            }
            $viewresult="";
            if($this->m_checking->actions("Polls","module13","Chart Report",TRUE,FALSE,"home"))
            {
                $viewresult=$this->template_icon->detail_onclick("seeresult('".$dt['_id']."')","",'Result',"chart_line.png","","linkdetail");
            }
            $detail="";
            if($this->m_checking->actions("Polls","module13","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","",'Edit',"pencil.png","","linkdetail"); 
            }
            $delete="";            
            if($this->m_checking->actions("Polls","module13","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Poll ".$dt['question']."')","",'Delete',"delete.png","","linkdelete");
            }            
            $output['aaData'][] = array(
                $i,
                $dt['name'],
                $dt['question'],
                $dt['enabled'],
                $tglshow,
                date('Y-m-d', $dt['create']->sec),
                $viewresult.$detail.$delete,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('fitureadd/index'); 
        }
    }
    function chart($id="")
    {
        $this->m_checking->actions("Polls","module13","Chart Report",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Polls");
        $poll_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $data['judul']="";        
        $data['data']=array();
        $data['date']="";
        $data['name']="";
        if($poll_detail)
        {
            $data['name']=$poll_detail['name'];
            $data['judul']=$poll_detail['question'];
            if($poll_detail['date']!="")
            {
                $data['date']="Date : ".date('Y-m-d', $poll_detail['date']->sec);
            }
            $data['data']=$poll_detail['options'];
        }
        $this->load->view("fitureadd_chart",$data);
    }
    function detail($id="")
    {
        $this->m_checking->actions("Polls","module13","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Polls");
        $output['message'] = "Your data is not found";
        $output['success'] = FALSE;
        $tampung=$this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        if($tampung)
        {
            $tglshow="";
            if($tampung['date']!="")
            {
                $tglshow=date('Y-m-d', $tampung['date']->sec);
            }
            $output['_id'] = (string)$tampung['_id'];
            $output['name'] = $tampung['name'];
            $output['question'] = $tampung['question'];
            $output['date'] = $tglshow;
            $output['enabled'] = $tampung['enabled'];
            $output['options'] = isset($tampung['options'])?$tampung['options']:array();
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('fitureadd/index'); 
        }
    }
    function cruid_poll()
    {        
        $this->form_validation->set_rules('questions','Question','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('name','Name','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('dateshow','Date','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('enable_show','Enabled','trim|htmlspecialchars|xss_clean');
        $url = current_url();
        $user = $this->session->userdata('username');
        $output['message'] = "";
        $output['success'] = FALSE;
        $generatetime=date("Y-m-d H:i:s");
        $time_start=  strtotime($generatetime);
        if($this->form_validation->run()==FALSE)
        {
            $output['message'] = validation_errors("<p class='error'>","</p>");
        }
        else
        {
            $id = $this->input->post('txtid',TRUE);
            $name = $this->input->post('name',TRUE);
            $question = $this->input->post('questions',TRUE);
            $dateshow = $this->input->post('dateshow',TRUE);
            $enable_show = $this->input->post('enable_show',TRUE);
            $options=array();
            if(isset($_POST['optionchild']))
            {
                for($i=0;$i<count($_POST['optionchild']);$i++)
                {
                    $options[]=array(
                        'option'=> $_POST['optionchild'][$i],
                        'values'=> (double)$_POST['valueschild'][$i],
                    );
                }
            }            
            $datatinsert=array(
                'name'  =>$name,
                'question'  =>$question,
                'date'  =>($dateshow=='')?'':$this->mongo_db->time(strtotime($dateshow." 00:00:00")),
                'create'  =>$this->mongo_db->time($time_start),
                'enabled'  =>$enable_show,
                'brand_id' =>$this->session->userdata('brand'),
                'options'  =>$options,
            );
            if($id=='')
            {
                $this->m_checking->actions("Polls","module13","Add",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Polls");
                $level_id=$this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Polls",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
            }
            else
            {
                $this->m_checking->actions("Polls","module13","Edit",FALSE,FALSE,"home");
                $this->mongo_db->select_db("Assets");
                $this->mongo_db->select_collection("Polls");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Polls",$url,$user);
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
            redirect('fitureadd/index'); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Polls","module13","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Polls");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Polls",$url,$user);
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
            redirect('fitureadd/index'); 
        }
    }
    function import($tofile="")
    {	
        $this->m_checking->actions("Polls","module13","Export",FALSE,TRUE,"fitureadd/index");
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Polls");
        $pencarian = array();
        if($this->session->userdata('brand')!="")
        {
            $pencarian =  array("brand_id"=>$this->session->userdata('brand'));
        }
        $datapageadd['listdata'] = $this->mongo_db->find($pencarian,0,0,array());        
        $this->load->library(array('reporting','table'));
        $filename="polls";
        $temp['data_title'] = "Cetak Data Polls";
        if($tofile=="doc")
        {
            $this->reporting->header_rtf($filename);
            $this->load->view("report/report_fitureadd",$datapageadd);
        }
        else if($tofile=="xls")
        {
            $this->reporting->header_xls($filename);
            $this->reporting->xls_bof();
            $this->reporting->xls_write_label(0,0,$temp['data_title'] );
            $this->reporting->xls_write_label(1,0,"No");
            $this->reporting->xls_write_label(1,1,"Name");
            $this->reporting->xls_write_label(1,2,"Questions");
            $this->reporting->xls_write_label(1,3,"Enabled");
            $this->reporting->xls_write_label(1,4,"Date Create");
            $this->reporting->xls_write_label(1,5,"Options");
            $this->reporting->xls_write_label(1,6,"Value");
            $index=4;
            foreach($datapageadd['listdata'] as $dt)
            {
                $this->reporting->xls_write_number($index,0,($index-3));
                $this->reporting->xls_write_label($index,1,(isset($dt['name'])?$dt['name']:""));
                $this->reporting->xls_write_label($index,2,(isset($dt['question'])?$dt['question']:""));
                $this->reporting->xls_write_label($index,3,(isset($dt['enabled'])?$dt['enabled']:""));
                $this->reporting->xls_write_label($index,4,date('Y-m-d', $dt['create']->sec));
                foreach($dt['options'] as $dt2)
                {
                    $this->reporting->xls_write_label($index,5,$dt2['option']);
                    $this->reporting->xls_write_number($index,6,$dt2['values']);
                    $index++;
                }
                $index++;
            }
            $this->reporting->xls_eof();
        }
        else
        {            
            $css=array();
            $js=array();
            $this->template_admin->header_web(FALSE,$temp['data_title'],$css,$js,FALSE,"");
            $this->load->view("report/report_fitureadd",$datapageadd);
            $this->template_admin->footer();
        }
    }
}