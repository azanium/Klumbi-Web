<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Contest extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->m_checking->module("Contest","module8",FALSE,TRUE,"home");
    }
    function index()
    {
        $css=array(
            base_url()."resources/css/jqueryui.css",
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.default.css",
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.css",
        );
        $js=array(
            base_url()."resources/plugin/bootstrap3-dialog-master/bootstrap-dialog.js",
            base_url()."resources/plugin/pines-notify/jquery.pnotify.min.js",
        );
        $this->template_admin->header_web(TRUE,"Contest",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
//        $this->mongo_db->select_db("Assets");
//        $this->mongo_db->select_collection("Color");
//        $isiform['colorls']=$this->mongo_db->find(array(),0,0,array('name'=>1));
//        $this->mongo_db->select_collection("AvatarBodyPart");
//        $isiform['tipe']=$this->mongo_db->find(array("parent"=>''),0,0,array('name'=>1)); 
//        $this->mongo_db->select_collection("Brand");
//        $isiform['brand']=$this->mongo_db->find(array(),0,0,array('name'=>1));
//        $this->mongo_db->select_db("Articles");
//        $this->mongo_db->select_collection("ContentType");
//        $isiform['searchtype']=$this->mongo_db->find(array(),0,0,array('name'=>1));
//        $this->mongo_db->select_collection("ContentCategory");
//        $isiform['searchcategory']=$this->mongo_db->find(array(),0,0,array('name'=>1));
//        $this->mongo_db->select_db("Game");        
//        $this->mongo_db->select_collection("RequiredRewards");
//        $isiform['datareward']=$this->mongo_db->find(array('type'=>'Rewards'),0,0,array('name'=>1));
//        $this->load->view("leaderboard_view",$isiform);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
//    function generatecode()
//    {
//        $output = array(
//            "message" =>"Data Code is Generated",
//            "success" =>TRUE,
//            "data" =>$this->tambahan_fungsi->global_get_random(5),
//        );
//        if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }    
//    function list_data()
//    {
//        $this->mongo_db->select_db("Game");
//        $this->mongo_db->select_collection("Contest");
//        $awal=(isset($_GET['iDisplayStart']))?(int)$_GET['iDisplayStart']:0;
//        $limit=(isset($_GET['iDisplayLength']))?(int)$_GET['iDisplayLength']:10;
//        $sEcho=(isset($_GET['sEcho']))?(int)$_GET['sEcho']:1;
//        $sSortDir_0=(isset($_GET['sSortDir_0']))?$_GET['sSortDir_0']:"desc";
//        $iSortCol_0=(isset($_GET['iSortCol_0']))?(int)$_GET['iSortCol_0']:0;
//        $jns_sorting=-1;
//        if($sSortDir_0=="asc")
//        {
//            $jns_sorting=1;
//        }
//        $keysearchdt="name";
//        if($iSortCol_0==1)
//        {
//            $keysearchdt="code";
//        }
//        else if($iSortCol_0==2)
//        {
//            $keysearchdt="name";
//        }
//        $sSearch=(isset($_GET['sSearch']))?$_GET['sSearch']:"";
//        $pencarian=array();
//        if($sSearch!="")
//        {
//            $sSearch=(string) trim($sSearch);
//            $sSearch = quotemeta($sSearch);
//            $regex = "/$sSearch/i";
//            $filter=$this->mongo_db->regex($regex);
//            $pencarian=array(
//                $keysearchdt=>$filter,
//            );
//        }
//        if($this->session->userdata('brand')!="")
//        {
//            $pencarian= array_merge($pencarian,array("brand_id"=>$this->session->userdata('brand')));
//        }
//        $data=$this->mongo_db->find($pencarian,$awal,$limit,array($keysearchdt=>$jns_sorting));
//        $output = array(
//		"sEcho" => intval($sEcho),
//		"iTotalRecords" => $this->mongo_db->count($pencarian),
//		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
//		"aaData" => array()
//	);
//        $i=$awal+1;
//        foreach($data as $dt)
//        {
//            $detail="";
//            $delete="";
//            $approved="";
//            if($this->m_checking->actions("Contest","module8","Edit",TRUE,FALSE,"home"))
//            {
//                $detail=$this->template_icon->detail_onclick("ubahdata('".$dt['_id']."')","#editdata",'Edit',"pencil.png","","","data-toggle='modal'");
//            }
//            if($this->m_checking->actions("Contest","module8","Delete",TRUE,FALSE,"home"))
//            {
//                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete Contest ".$dt['name']."')","",'Delete',"delete.png","","linkdelete");
//            }
//            if($this->m_checking->actions("Contest","module8","Approved",TRUE,FALSE,"home"))
//            {
//                $icondt = "cross.png";
//                $textdata = "Valid";
//                $newval = TRUE;
//                if(isset($dt['valid']))
//                {
//                    if($dt['valid']==TRUE)
//                    {
//                        $icondt = "accept.png";
//                        $textdata = "Not Valid";
//                        $newval = FALSE;
//                    }
//                }
//                $approved=$this->template_icon->detail_onclick("setvalid('".$dt['_id']."','".$newval."','Are you sure want to Set Contest ".$dt['name']." to be ".$textdata."')","",'Valid Data',$icondt,"","linkdelete");
//            }
//            $pathimg = $this->config->item('path_asset_img')."preview_images/";
//            $picture = "<a class='fancybox' href='".$pathimg.$dt['imageicon']."'><img src='".$pathimg.$dt['imageicon']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a><br />";
//            $picture .= "<a class='fancybox' href='".$pathimg.$dt['imagebanner']."'><img src='".$pathimg.$dt['imagebanner']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a><br />";
//            $picture .= "<a class='fancybox' href='".$pathimg.$dt['imagecontent']."'><img src='".$pathimg.$dt['imagecontent']."' alt='' class='img-thumbnail' style='max-width:75px; max-height:75px;' /></a>";
//            $output['aaData'][] = array(
//                $i,
//                $dt['code'],
//                $dt['name'],
//                $dt['state'],
//                $dt['gender'],
//                date('Y-m-d H:i:s', $dt['begin']->sec),
//                date('Y-m-d H:i:s', $dt['end']->sec),
//                $dt['count'],
//                $picture,
//                $approved.$detail.$delete,
//            );
//            $i++;           
//        }  
//	if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }
//    function setvalid()
//    {
//        $this->form_validation->set_rules('id','ID','trim|required|htmlspecialchars|xss_clean');        
//        $this->form_validation->set_rules('newval','Value','trim|htmlspecialchars|xss_clean');
//        $output['message'] = "";
//        $output['success'] = FALSE;
//        $url = current_url();
//        $user = $this->session->userdata('username');
//        if($this->form_validation->run()==FALSE)
//        {
//            $output['message'] = validation_errors("<p class='error'>","</p>");
//        }
//        else
//        {
//            $id = $this->input->post('id',TRUE);
//            $isvalid = $this->input->post('newval',TRUE);
//            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
//            $datatinsert=array(
//                'valid'  =>(($isvalid==="")?FALSE:TRUE),
//                'update'  =>$tanggalupdate,
//            );
//            $this->m_checking->actions("Contest","module8","Approved",FALSE,FALSE,"home");
//            $this->mongo_db->select_db("Game");
//            $this->mongo_db->select_collection("Contest");
//            $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
//            $this->m_user->tulis_log("Update Valid Data Contest",$url,$user);
//            $output['message'] = "<i class='success'>Data is updated</i>";
//            $output['success'] = TRUE;
//        }
//        if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }
//    function detail()
//    {
//        $this->m_checking->actions("Contest","module8","Edit",FALSE,FALSE,"home");
//        $this->form_validation->set_rules('txtid','ID','trim|required|htmlspecialchars|xss_clean');        
//        $output['message'] = "";
//        $output['success'] = FALSE;
//        $url = current_url();
//        $user = $this->session->userdata('username');
//        if($this->form_validation->run()==FALSE)
//        {
//            $output['message'] = validation_errors("<p class='error'>","</p>");
//        }
//        else
//        {
//            $id = $this->input->post('txtid',TRUE);
//            $output['success'] = TRUE;
//            $this->mongo_db->select_db("Game");
//            $this->mongo_db->select_collection("Contest");
//            $tempdata = $this->mongo_db->findOne(array('_id'=> $this->mongo_db->mongoid($id)));
//            if($tempdata)
//            {
//                $output['id'] = (string)$tempdata['_id'];
//                $output['code'] = $tempdata['code'];
//                $output['name'] = $tempdata['name'];
//                $output['state'] = $tempdata['state'];
//                $output['bgcolor'] = $tempdata['bgcolor'];
//                $output['textcolor'] = $tempdata['textcolor'];
//                $output['gender'] = $tempdata['gender'];
//                $output['description'] = $tempdata['description'];
//                $output['info'] = $tempdata['info'];
//                $output['begindate'] = date('Y-m-d',$tempdata['begin']->sec);
//                $output['begintime'] = date('H:i:s',$tempdata['begin']->sec);
//                $output['enddate'] = date('Y-m-d',$tempdata['end']->sec);
//                $output['timeend'] = date('H:i:s',$tempdata['end']->sec);
//                $output['count'] = $tempdata['count'];
//                $output['order'] = $tempdata['order'];
//                $output['votestate'] = $tempdata['votestate'];
//                $output['imageicon'] = $tempdata['imageicon'];
//                $output['imagebanner'] = $tempdata['imagebanner'];
//                $output['imagecontent'] = $tempdata['imagecontent'];
//                $output['tag'] = $tempdata['tag'];
//                $output['typesearch'] = $tempdata['typesearch'];
//                $output['categorysearch'] = $tempdata['categorysearch'];
//                $output['brand_id'] = $tempdata['brand_id'];
//                $output['rewards'] = $tempdata['rewards'];
//                $output['requireds'] = $tempdata['requireds'];
//            }
//        }
//        if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }
//    function cruid_contest()
//    {        
//        $this->form_validation->set_rules('txtid','ID','trim|htmlspecialchars|xss_clean');        
//        $this->form_validation->set_rules('code','Code','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('name','Contest Name','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('cmbstate','State','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtbgcolor','Backroud Color','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtcolor','Text Color','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('cmbgender','Gender','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('descriptions','Description','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('infodetail','Info Detail','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtdatebegin','Date Begin','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txttimebegin','Time Begin','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtdateend','End Date','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txttimeend','Time End','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('count','Count','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('cmblistdata','Winner','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('cmbstatevote','Vote Status','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txticonname','Icon Name','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtbannername','Banner Name','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txtcontentname','Content Name','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('txttag','Tag','trim|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('searctype','Info Search','trim|required|htmlspecialchars|xss_clean');
//        $this->form_validation->set_rules('searchcategory','Info Category','trim|required|htmlspecialchars|xss_clean');
//        $output['message'] = "";
//        $output['success'] = FALSE;
//        $url = current_url();
//        $user = $this->session->userdata('username');
//        if($this->form_validation->run()==FALSE)
//        {
//            $output['message'] = validation_errors("<p class='error'>","</p>");
//        }
//        else
//        {
//            $id = $this->input->post('txtid',TRUE);
//            $code = $this->input->post('code',TRUE);
//            $name = $this->input->post('name',TRUE);
//            $cmbstate = $this->input->post('cmbstate',TRUE);
//            $txtbgcolor = $this->input->post('txtbgcolor',TRUE);
//            $txtcolor = $this->input->post('txtcolor',TRUE);
//            $cmbgender = $this->input->post('cmbgender',TRUE);
//            $descriptions = $this->input->post('descriptions',TRUE);
//            $infodetail = $this->input->post('infodetail',TRUE);
//            $txtdatebegin = $this->input->post('txtdatebegin',TRUE);
//            $txttimebegin = $this->input->post('txttimebegin',TRUE);
//            $txtdateend = $this->input->post('txtdateend',TRUE);
//            $txttimeend = $this->input->post('txttimeend',TRUE);
//            $count = $this->input->post('count',TRUE);
//            $cmblistdata = $this->input->post('cmblistdata',TRUE);
//            $cmbstatevote = $this->input->post('cmbstatevote',TRUE);
//            $txticonname = $this->input->post('txticonname',TRUE);
//            $txtbannername = $this->input->post('txtbannername',TRUE);
//            $txtcontentname = $this->input->post('txtcontentname',TRUE);
//            $txttag = $this->input->post('txttag',TRUE);
//            $searctype = $this->input->post('searctype',TRUE);
//            $searchcategory = $this->input->post('searchcategory',TRUE);
//            $rewards = $this->input->post('rewards',TRUE);
//            $tanggalupdate=$this->mongo_db->time(strtotime(date("Y-m-d H:i:s")));
//            if($this->session->userdata('brand')=="")
//            {
//                $dtbrand = $_POST['brand'];
//            }
//            else
//            {
//                $dtbrand = $this->session->userdata('brand');
//            }
//            $datarequired = array();
//            if(isset($_POST['txttyperequired']))
//            {
//                for($y=0; $y < count($_POST['txttyperequired']);$y++)
//                {
//                    $datarequired[] = array(
//                        'type'=>$_POST['txttyperequired'][$y],
//                        'value'=>$_POST['txtvaluerequired'][$y],
//                    );
//                }
//            }
//            $datatinsert=array(
//                'code'  =>$code,
//                'name'  =>$name,
//                'state'  =>$cmbstate,
//                'bgcolor'  =>$txtbgcolor,
//                'textcolor'  =>$txtcolor,
//                'gender'  =>$cmbgender,
//                'description'  =>$descriptions,
//                'info'  =>$infodetail,
//                'begin'  => $this->mongo_db->time(strtotime($txtdatebegin." ".$txttimebegin)),
//                'end'  => $this->mongo_db->time(strtotime($txtdateend." ".$txttimeend)),
//                'count'  =>$count,
//                'order'  =>$cmblistdata,
//                'votestate'  =>$cmbstatevote,
//                'imageicon'  =>$txticonname,
//                'imagebanner'  =>$txtbannername,
//                'imagecontent'  =>$txtcontentname,
//                'tag'  =>$txttag,
//                'typesearch'  =>$searctype,
//                'categorysearch'  =>$searchcategory,
//                'brand_id'  =>$dtbrand,
//                'update'  =>$tanggalupdate,
//                'requireds'  =>$datarequired,
//                'rewards'  =>$rewards
//            );
//            if($id=='')
//            {
//                $this->m_checking->actions("Contest","module8","Add",FALSE,FALSE,"home");
//                $this->mongo_db->select_db("Game");
//                $this->mongo_db->select_collection("Contest");
//                $level_id=$this->mongo_db->insert($datatinsert);
//                $this->m_user->tulis_log("Add Contest",$url,$user);
//                $output['message'] = "<i class='success'>New Data is added</i>";
//            }
//            else
//            {
//                $this->m_checking->actions("Contest","module8","Edit",FALSE,FALSE,"home");
//                $this->mongo_db->select_db("Game");
//                $this->mongo_db->select_collection("Contest");
//                $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
//                $this->m_user->tulis_log("Update Contest",$url,$user);
//                $output['message'] = "<i class='success'>Data is updated</i>";
//            }  
//            $output['success'] = TRUE;
//        }
//        if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }
//    function delete($id="")
//    {
//        $this->m_checking->actions("Contest","module8","Delete",FALSE,FALSE,"home");
//        $this->mongo_db->select_db("Game");
//        $this->mongo_db->select_collection("Contest");
//        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
//        $url = current_url();
//        $user = $this->session->userdata('username');
//        $this->m_user->tulis_log("Delete Contest",$url,$user);
//        $output = array(
//            "message" =>"Data is deleted",
//            "success" =>TRUE,
//        );
//        if(IS_AJAX)
//        {
//            echo json_encode( $output );
//        }
//        else
//        {
//            redirect('contest/index'); 
//        }
//    }
}
