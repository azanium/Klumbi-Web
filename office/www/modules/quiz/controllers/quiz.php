<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Quiz extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Quizes","module5",FALSE,TRUE,"home");
    }
    function generatecode()
    {
        $output = array(
            "message" =>"Data Code is Generated",
            "success" =>TRUE,
            "data" =>$this->tambahan_fungsi->global_get_random(6),
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quiz/index'); 
        }
    }
    function index()
    {
        $this->mongo_db->select_db("Assets");
        $this->mongo_db->select_collection("Brand");        
        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
        $this->mongo_db->select_collection("AvatarBodyPart");
        $isiform['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1));  
        $this->mongo_db->select_db("Game");        
        $this->mongo_db->select_collection("RequiredRewards");
        $isiform['data']=$this->mongo_db->find(array('type'=>'Required'),0,0,array('name'=>1));
        $isiform['datareward']=$this->mongo_db->find(array('type'=>'Rewards'),0,0,array('name'=>1));
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/datatables/dataTables.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.css",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker-bs3.css",
        );
        $js=array(
            base_url()."resources/plugin/jquery-validation-1.10.0/lib/jquery.metadata.js",    
            base_url()."resources/plugin/jquery-validation-1.10.0/dist/jquery.validate.js",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/datatables/jquery.dataTables.js",
            base_url()."resources/plugin/datatables/dataTables.bootstrap.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
            base_url()."resources/plugin/jqueryui-timepicker/jquery.ui.timepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/daterangepicker.min.js",
            base_url()."resources/plugin/form-daterangepicker/moment.min.js",
            base_url()."resources/plugin/form-inputmask/jquery.inputmask.bundle.min.js",
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js",
        );
        $this->template_admin->header_web(TRUE,"Quiz",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("quiz_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data_child()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quiz");
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
        $keysearchdt="ID";
        if($iSortCol_0==1)
        {
            $keysearchdt="ID";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="Title";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="Description";
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
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting));
        $output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $this->mongo_db->count(),
		"iTotalDisplayRecords" => $this->mongo_db->count(),
		"aaData" => array()
	);
        $i=1;
        foreach($data as $dt)
        {
            $selected=$this->template_icon->detail_onclick("selectthisquiz('".$dt['ID']."')","",'Detail',"accept.png","","linkdetail");
            $output['aaData'][] = array(
                $i,
                $dt['ID'],
                $dt['Title'],
                $dt['Description'],
                $selected,
            );
            $i++;           
        }  
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quiz/index'); 
        }
    }
    function list_data()
    {
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quiz");
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
        $keysearchdt="ID";
        if($iSortCol_0==1)
        {
            $keysearchdt="ID";
        }
        else if($iSortCol_0==2)
        {
            $keysearchdt="Title";
        }
        else if($iSortCol_0==3)
        {
            $keysearchdt="Description";
        }
        else if($iSortCol_0==4)
        {
            $keysearchdt="BrandId";
        }
        else if($iSortCol_0==5)
        {
            $keysearchdt="State";
        }
        else if($iSortCol_0==6)
        {
            $keysearchdt="StartDate";
        }
        else if($iSortCol_0==7)
        {
            $keysearchdt="EndDate";
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
            $pencarian= array_merge($pencarian,array("BrandId"=>$this->session->userdata('brand')));
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
            $detail="";
            $delete="";
            if($this->m_checking->actions("Quizes","module5","Edit",TRUE,FALSE,"home"))
            {
                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
            } 
            if($this->m_checking->actions("Quizes","module5","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Quiz ".$dt['ID']."')","",'Delete',"delete.png","","linkdelete");
            } 
            $tglawal="";
            if($dt['StartDate']!="")
            {
                $tglawal=date('Y-m-d', $dt['StartDate']->sec);
            }
            $tglakhir="";
            if($dt['EndDate']!="")
            {
                $tglakhir=date('Y-m-d', $dt['EndDate']->sec);
            }
            $output['aaData'][] = array(
                $i,
                $dt['ID'],
                $dt['Title'],
                $dt['Description'],
                $dt['BrandId'],
                $dt['State'],
                $tglawal,
                $tglakhir,
                $detail.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quiz/index'); 
        }
    }
    function cruid_quiz()
    {        
        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('code','Quiz ID','trim|required|htmlspecialchars|xss_clean');        
        $this->form_validation->set_rules('txttitle','Quiz Title','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdesc','Quiz Description','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtisrandom','Random Question','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtcount','Count Question','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdatebegin','Date Begin','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimebegin','Time Begin','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtdateend','Date End','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txttimeend','Time End','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtquizid','Required Quiz','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtquestid','Required Quest(ID)','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('requireditem','Required Game Items','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('rewards','Reward Game Item','trim|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('state','State','trim|required|htmlspecialchars|xss_clean');
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
            $id = $this->input->post('txtid',TRUE);
            $txtkode = $this->input->post('code',TRUE);
            $txttitle = $this->input->post('txttitle',TRUE);
            $txtdesc = $this->input->post('txtdesc',TRUE);
            $txtisrandom = $this->input->post('txtisrandom',TRUE);
            $txtcount = $this->input->post('txtcount',TRUE);
            if($this->session->userdata('brand')=="")
            {
                $brand = $_POST['brand'];
            }
            else
            {
                $brand = $this->session->userdata('brand');
            }
            $startdate = $this->input->post('txtdatebegin',TRUE);
            $starttime = $this->input->post('txttimebegin',TRUE);
            $enddate = $this->input->post('txtdateend',TRUE);
            $endtime = $this->input->post('txttimeend',TRUE);
            $txtquizid = $this->input->post('txtquizid',TRUE);
            $txtquestid = $this->input->post('txtquestid',TRUE);
            $txtrequireditem = $this->input->post('requireditem',TRUE);
            $rewards = $this->input->post('rewards',TRUE);
            $state = $this->input->post('state',TRUE);
            $dataquestion=array();
            if(isset($_POST['chiledcount']))
            {
                for($i=0;$i<count($_POST['chiledcount']);$i++)
                {
                    $variablename = $_POST['chiledcount'][$i];
                    $optionchild=array();
                    if(isset($_POST['optiontextof'.$variablename]))
                    {
                        for($j=0;$j<count($_POST['optiontextof'.$variablename]);$j++)
                        {
                            $option_answer=isset($_POST['optiontextof'.$variablename][$j])?$_POST['optiontextof'.$variablename][$j]:"";
                            $option_value=isset($_POST['optionvalueof'.$variablename][$j])?$_POST['optionvalueof'.$variablename][$j]:"";
                            if($option_answer!="")
                            {
                                $optionchild[]=array(
                                    'Answer'=>$option_answer,
                                    'IsCorrect'=>(bool)$option_value,
                                );
                            }
                        }
                    }
                    $dataquestion[]=array(
                        'QuestionId'=>$_POST['chiledquestionid'][$i],
                        'Question'=>$_POST['questiontext'][$i],
                        'Tipe'=>$_POST['tipeanswer'][$i], 
                        'Difficulty'=>$_POST['txtlevel'][$i],
                        'QuestionTime'=>(double)$_POST['txttime'][$i],                         
                        'Image'=>$_POST['chiledquestionimage'][$i],
                        'Options'=>$optionchild,
                    );
                }
            }
            $datatinsert=array(
                'ID'  =>$txtkode,
                'Title'  =>$txttitle,
                'Description'  =>$txtdesc,
                'Count'  =>$txtcount,
                'BrandId'  =>$brand,
                'State'  =>$state,
                'StartDate'  =>($startdate=='')?'':$this->mongo_db->time(strtotime($startdate." 00:00:00")),
                'StartTime'  =>$starttime,
                'EndDate'  =>($enddate=='')?'':$this->mongo_db->time(strtotime($enddate." 00:00:00")),
                'EndTime'  =>$endtime,
                'IsRandom'  =>(bool)$txtisrandom,                
                'RequiredQuiz'  =>$txtquizid,
                'RequiredQuest'  =>$txtquestid,
                'RequiredItem'  =>$txtrequireditem,
                'Reward'  =>$rewards,
                'Questions'=>$dataquestion,
            );
            if($id=='')
            {
                $this->m_checking->actions("Quizes","module5","Add",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Quiz");
                $this->mongo_db->insert($datatinsert);
                $this->m_user->tulis_log("Add Quiz",$url,$user);
                $output['message'] = "<i class='success'>New Data is added</i>";
                $output['success'] = TRUE;
            }
            else
            {
                $this->m_checking->actions("Quizes","module5","Edit",FALSE,TRUE,"home");
                $this->mongo_db->select_db("Game");
                $this->mongo_db->select_collection("Quiz");
                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
                $this->m_user->tulis_log("Update Quiz",$url,$user);
                $output['message'] = "<i class='success'>Data is updated</i>";
                $output['success'] = TRUE;
            }  
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('quiz/index'); 
        }
    }
    function detail($id="")
    {
        $this->m_checking->actions("Quizes","module5","Edit",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quiz");
        $output['message'] = "Fail to load data Quiz";
        $output['success'] = FALSE;
        $tampung = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
        $datebegintemp="";
        $dateendtemp="";
        if($tampung)
        {
            $output['success'] = TRUE;
            $output['_id'] = (string)$tampung['_id'];
            $output['ID'] = $tampung['ID'];
            $output['Title'] = $tampung['Title'];
            $output['Description'] = $tampung['Description'];
            $output['BrandId'] = $tampung['BrandId'];
            $output['State'] = $tampung['State'];
            $output['Count'] = $tampung['Count'];
            $output['StartTime'] = $tampung['StartTime'];
            $output['EndTime'] = $tampung['EndTime'];
            $output['IsRandom'] = $tampung['IsRandom'];
            $output['Number'] = $tampung['Number'];            
            $output['RequiredQuiz'] = $tampung['RequiredQuiz'];
            $output['RequiredQuest'] = $tampung['RequiredQuest'];            
            $output['RequiredItem'] = $tampung['RequiredItem'];
            $output['Reward'] = $tampung['Reward'];
            $output['Questions'] = $tampung['Questions'];
            if($tampung['EndDate']!='')
            {
                $dateendtemp = date('Y-m-d', $tampung['EndDate']->sec);
            }
            if($tampung['StartDate']!='')
            {
                $datebegintemp = date('Y-m-d', $tampung['StartDate']->sec);
            }
            $output['EndDate'] = $dateendtemp;
            $output['StartDate'] = $datebegintemp;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect("quiz/index"); 
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("Quizes","module5","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Game");
        $this->mongo_db->select_collection("Quiz");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Quiz",$url,$user);
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
            redirect('quiz/index'); 
        }
    }
}
