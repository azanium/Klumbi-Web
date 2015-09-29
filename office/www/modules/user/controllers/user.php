<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation','session','template_admin','template_icon','tambahan_fungsi','cek_session'));
        $this->cek_session->cek_login();
        $this->load->model("m_userdata");
        $this->m_checking->module("User","module2",FALSE,TRUE,"home");
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
        $this->template_admin->header_web(TRUE,"User Account",$css,$js,TRUE,"");
        $this->template_admin->headerbar(); 
        $this->template_admin->top_menu(TRUE);
        $this->load->view("panel/panel_utama");
        $this->template_admin->panel_menu();
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");
        $this->load->view("user_view");
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }
    function list_data()
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
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
                'username'=>$filter,
            );
        }
        $data=$this->mongo_db->find($pencarian,$awal,$limit,array('username'=>1));
        $output = array(
		"sEcho" => intval($sEcho),
		"iTotalRecords" => $this->mongo_db->count($pencarian),
		"iTotalDisplayRecords" => $this->mongo_db->count($pencarian),
		"aaData" => array()
	);
        $i=$awal+1;
        foreach($data as $dt)
        {
            $delete="";
            $priview2="";
            $isartist="";
            $data_configurations="";
            $data_configurations22="";
            $data_userstat="";
            $statususervalid = $this->template_icon->img_icon("Not Valid","cross.png");
            $statususerartist = "status_offline.png";
            $statususerartist_text = "Unverify User";
            $userfollowericon = "";
            $userfollowingicon = "";
            $valartist = 1;
            if($this->m_checking->actions("User","module2","Delete",TRUE,FALSE,"home"))
            {
                $delete=$this->template_icon->detail_onclick("hapusdata('".$dt['_id']."','Are you sure want to delete User ".$dt['username']."')","",'Delete',"delete.png","","linkdelete");
            }
            if($this->m_checking->actions("User","module2","View Detail",TRUE,FALSE,"home"))
            {
                $priview2 = $this->template_icon->link_icon_notext("home/account/index/".$dt['username'],'Profile',"user_gray.png");
            }
            if($this->m_checking->actions("User","module2","View Avatar Mix",TRUE,FALSE,"home"))
            {
                $data_configurations = $this->template_icon->link_icon_notext("user/priviewavatar/index/".$dt['_id'],'Mix',"emoticon_happy.png");
            }
            if($this->m_checking->actions("User","module2","View Avatar Collection",TRUE,FALSE,"home"))
            {
                $data_configurations22 = $this->template_icon->link_icon_notext("user/collections/index/".$dt['_id'],'Mix Collections',"basket.png");
            }
//            if($this->m_checking->actions("User","module2","View Status",TRUE,FALSE,"home"))
//            {
//                $data_userstat=$this->template_icon->link_icon_notext("user/userstatus/index/".$dt['_id'],'View Status',"comments.png");
//            }
            if($this->m_checking->actions("User","module2","View Follower",TRUE,FALSE,"home"))
            {
                $userfollowericon = $this->template_icon->link_icon_notext("user/follower/index/".$dt['_id'],'Follower',"star.png");
            }
            if($this->m_checking->actions("User","module2","View Following",TRUE,FALSE,"home"))
            {
                $userfollowingicon = $this->template_icon->link_icon_notext("user/following/index/".$dt['_id'],'Following',"medal_bronze_2.png");
            }
            if(isset($dt['valid']))
            {
                if($dt['valid']==TRUE)
                {
                    $statususervalid = $this->template_icon->img_icon("Valid User","accept.png");
                }
            }
            if(isset($dt['artist']))
            {
                if($dt['artist']==TRUE)
                {
                    $statususerartist = "status_online.png";
                    $statususerartist_text = "Verify User";
                    $valartist = 0;
                }
            }
            $tempdtuser = $this->m_userdata->user_properties((string)$dt["_id"]);
            if($this->m_checking->actions("User","module2","set Artist",TRUE,FALSE,"home"))
            {
                $isartist = $this->template_icon->detail_onclick("setdataartist('".$dt['_id']."','".$valartist."','Set Status verify User ?')","",$statususerartist_text,$statususerartist,"","linkdelete");
            }
            $output['aaData'][] = array(
                $i,
                $dt['username'],
                $dt['email'],
                $tempdtuser["fullname"],
                $tempdtuser["handphone"],
                $statususervalid.$isartist.$userfollowingicon.$userfollowericon.$data_configurations.$data_configurations22.$data_userstat.$priview2.$delete,
            );
            $i++;           
        }  
	if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/index'); 
        }
    }
    function detail_properties($id="")
    {
        $this->m_checking->actions("User","module2","View Detail",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Properties");
        $data=$this->mongo_db->findOne(array('lilo_id' => $id));
        if($data)
        {
            $picture = base_url()."resources/image/index.jpg";
            $usernametemp = "";
            $this->mongo_db->select_collection("Account");
            $dataaccount = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($id)));
            if($dataaccount)
            {
                $usernametemp = $dataaccount["username"];
                if(isset($dataaccount['fb_id']))
                {
                    if($dataaccount['fb_id']!="")
                    {
                        $picture = "https://graph.facebook.com/".$dataaccount['fb_id']."/picture?type=smaller";//large,smaller,square
                    }
                }
                if(isset($dataaccount['twitter_id']))
                {
                    if($dataaccount['twitter_id']!="")
                    {
                        $picture = "http://api.twitter.com/1/users/profile_image/".$dataaccount['twitter_id']."/image1327396628_normal.png";
                    }
                }
            }
            if(isset($data['picture']))
            {
                if($data['picture']!="")
                {
                    $picture = $data['picture'];
                }
            }
            $variabel['picture']=$picture;
            $variabel['fullname']=isset($data['fullname'])?$data['fullname']:"";
            $variabel['avatarname']=isset($data['avatarname'])?$data['avatarname']:"";
            $variabel['birthday']=isset($data['birthday'])?$data['birthday']:"";
            $variabel['sex']=isset($data['sex'])?$data['sex']:"";
            $variabel['website']=isset($data['website'])?$data['website']:"";
            $variabel['link']=isset($data['link'])?$data['link']:"";
            $variabel['about']=isset($data['about'])?$data['about']:"";
            $variabel['handphone']=isset($data['handphone'])?$data['handphone']:"";
            $variabel['location']=isset($data['location'])?$data['location']:"";
            $variabel['bodytype']=isset($data['bodytype'])?$data['bodytype']:"";
            $variabel['username']=$usernametemp;
            $this->load->view("user_properties",$variabel);
        }
    }
    function delete($id="")
    {
        $this->m_checking->actions("User","module2","Delete",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $this->mongo_db->select_collection("Properties");
        $this->mongo_db->remove(array('lilo_id' => $id));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete User Account",$url,$user);
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
            redirect('user/index'); 
        }
    }
    function setasartist($id="",$newvalue=0)
    {
        $this->m_checking->actions("User","module2","set Artist",FALSE,FALSE,"home");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $datatinsert = array(
            "artist" => (($newvalue==0)?FALSE:TRUE)
        );
        $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($id)),array('$set'=>$datatinsert));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Set Field Artist User Account",$url,$user);
        $output = array(
            "message" =>"Data Artist is set",
            "success" =>TRUE,
        );
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('user/index'); 
        }
    }
    function import($tofile="")
    {	
        $this->m_checking->actions("User","module2","Export",FALSE,TRUE,"user/index");
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Account");
        $pencarian = array();
        $datapageadd['listdata'] = $this->mongo_db->find($pencarian,0,0,array());        
        $this->load->library(array('reporting','table'));
        $filename="userdata";
        $temp['data_title'] = "Cetak Data Users";
        if($tofile=="doc")
        {
            $this->reporting->header_rtf($filename);
            $this->load->view("report/report_user");
        }
        else if($tofile=="xls")
        {
            $this->reporting->header_xls($filename);
            $this->reporting->xls_bof();
            $this->reporting->xls_write_label(0,0,$temp['data_title'] );
            $this->reporting->xls_write_label(1,0,"No");
            $this->reporting->xls_write_label(1,1,"Name");
            $this->reporting->xls_write_label(1,2,"Email");
            $this->reporting->xls_write_label(1,3,"Phone");
            $this->reporting->xls_write_label(1,4,"Sex");
            $this->reporting->xls_write_label(1,5,"Location");
            $index=4;
            foreach($datapageadd['listdata'] as $dt)
            {
                $this->reporting->xls_write_number($index,0,($index-3));
                $this->reporting->xls_write_label($index,1,(isset($dt['username'])?$dt['username']:""));
                $this->reporting->xls_write_label($index,2,(isset($dt['email'])?$dt['email']:""));
                $this->mongo_db->select_collection("Properties");
                $temp_data = $this->mongo_db->findOne(array('lilo_id' => (string)$dt['_id']));
                $phone = "";
                $sex = "";
                $location = "";
                if($temp_data)
                {
                    $phone = (isset($temp_data['handphone'])?$temp_data['handphone']:"");
                    $sex = (isset($temp_data['sex'])?$temp_data['sex']:"");
                    $location = (isset($temp_data['location'])?$temp_data['location']:"");
                }
                $this->reporting->xls_write_label($index,3,$phone);
                $this->reporting->xls_write_label($index,4,$sex);
                $this->reporting->xls_write_label($index,5,$location);
                $index++;
            }
            $this->reporting->xls_eof();
        }
        else
        {            
            $css=array();
            $js=array();
            $this->template_admin->header_web(FALSE,$temp['data_title'],$css,$js,FALSE,"");
            $this->load->view("report/report_user");
            $this->template_admin->footer();
        }
    }
}
