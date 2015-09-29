<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->library(array('pagination','form_validation'));
        $this->load->helper('text');
    }    
    function index()
    {
        $numberpage= (isset($_GET["per_page"])?$_GET["per_page"]:0);
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentNews");
        $config['base_url'] = $this->template_admin->link("home/news/index","?page=news&dt=list");
        $config['total_rows'] = $this->mongo_db->count2(array('state_document'=>'publish'));
        $config['uri_segment'] = 4;
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['full_tag_open'] = '<div><ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = '<i class="icon-long-arrow-left"></i> Newer';
        $config['first_tag_open'] = '<li class="previous">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Older <i class="icon-long-arrow-right"></i>';
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
        $datapage['paging']= $this->pagination->create_links();
        $datapage['count']= $config['total_rows'];
        $datapage['datalist'] = $this->mongo_db->find(array('state_document'=>'publish'),(int)$numberpage,$config['per_page'],array('update'=>-1));
        $css=array(
            base_url()."resources/plugin/codeprettifier/prettify.css",
            base_url()."resources/plugin/form-toggle/toggles.css",
        );
        $js=array();
        $this->template_admin->header_web(TRUE,"News Stream",$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"News");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("list_news",$datapage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    }  
    function detail($alias="")
    {
        $alias = urldecode($alias);
        $this->mongo_db->select_db("Articles");
        $this->mongo_db->select_collection("ContentNews");
        $temp = $this->mongo_db->findOne(array('alias' => $alias,'state_document'=>'publish'));        
        $addpage['listdata']=array();
        $addpage['txtcount']=0;        
        $addpage['txtlimit']=10;
        $addpage['txtawal']=0;
        $addpage['txtid']="";
        $addpage['txtalias']="";
        $addpage['title']="No Title";
        $addpage['data']="<i class='error'>No data Found</i>";
        $addpage['btnlove']=FALSE;
        $addpage['totallike']=0;
        if($temp)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $addpage['txtcount']= $this->mongo_db->count2(array('id'=>(string)$temp['_id'],'type'=>'NewsComment'));
            $addpage['listdata'] = $this->mongo_db->find(array('id'=> (string)$temp['_id'],'type'=>'NewsComment'),$addpage['txtawal'],$addpage['txtlimit'], array("datetime"=>-1));
            $addpage['totallike'] = $this->mongo_db->count2(array('id'=>(string)$temp['_id'],'type'=>'NewsLove'));
            $data_cek_love = $this->mongo_db->findOne(array("user_id"=>$this->session->userdata('user_id'),"id"=>(string)$temp['_id'],'type'=>'NewsLove'));
            if($data_cek_love)
            {
                $addpage['btnlove']=TRUE;
            }
            $addpage['txtid']=(string)$temp['_id'];
            $addpage['txtalias']=  urlencode($temp['alias']);
            $addpage['title']=$temp['title'];
            $addpage['data']=$temp['text'];
        }
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
            base_url()."resources/plugin/form-autosize/jquery.autosize-min.js", 
        );
        $this->template_admin->header_web(TRUE,$addpage['title'],$css,$js,TRUE," class='horizontal-nav' ");
        $this->template_admin->top_menu(FALSE);
        $this->template_admin->big_top_menu(FALSE,"News");
        $this->load->view("panel/panel_utama");
        $this->load->view("panel/panel_content");
        $this->load->view("panel/panel_wrap");   
        $this->load->view("news_detail",$addpage);
        $this->load->view("panel/close_div");
        $this->load->view("panel/close_div");
        $this->template_admin->addresbar();
        $this->load->view("panel/close_div");
        $this->template_admin->footer();
    } 
    function send($alias="")
    {
        $alias = urldecode($alias);
        $this->form_validation->set_rules('txtmessage','Message','trim|required|htmlspecialchars|xss_clean');
        $this->form_validation->set_rules('txtid','ID','trim|required|htmlspecialchars|xss_clean'); 
        $this->form_validation->set_rules('txtjudul','Judul','trim|required|htmlspecialchars|xss_clean');
        $output['count'] = 0;
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
            $judul = $this->input->post('txtjudul',TRUE);
            $comment = $this->input->post('txtmessage',TRUE);
            $datatinsert = array(
                'user_id'  =>$this->session->userdata('user_id'),
                'id'  =>$id,      
                'comment'  =>$comment, 
                'datetime'  =>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))), 
                'type'=>'NewsComment'
            );
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $tempid = $this->mongo_db->insert($datatinsert);
            $output['_id'] =(string)$tempid;
            $this->m_user->tulis_log("Add New Comment news ".$judul,$url,$user);
            $output['count'] = $this->mongo_db->count2(array('news_id'=>$id));
            $output['message'] = "<i class='success'>New Comment is added</i>";
            $output['success'] = TRUE;
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/news/detail/'.urlencode($alias)); 
        }
    }
    function loadmoredata($idnews="",$alias="",$awal=0,$limit=0,$count=0)
    {        
        $alias = urldecode($alias);
        $output['message'] = "Fail to load more data";
        $output['success'] = FALSE;
        $output['jml'] = 0;
        $output['page'] = (int)$awal;
        $output['limit'] = (int)$limit;
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $output['count'] = $this->mongo_db->count2(array('id'=>(string)$idnews,'type'=>'NewsComment'));
        $indexke =0;
        if($count==$output['count'])
        {
            $indexke =(int)$awal;
        }
        else
        {
            $indexke =(int)$awal+($output['count']-$count);
        }
        $cekdata = $this->mongo_db->find(array('id'=> (string)$idnews,'type'=>'NewsComment'), $indexke, (int)$limit, array("datetime"=>-1));
        if($cekdata)
        {
            $output['success'] = TRUE;
            $output['page'] += $output['limit'];
            $temp_msg="";
            $i = 0;
            foreach($cekdata as $hasil)
            {
                $id_panel = "oldest".$i."_".$this->tambahan_fungsi->global_get_random(6);
                $picture = base_url()."resources/image/index.jpg";
                $namauser = "";
                $namauserprof = "";
                $temp_msg .="<li id='".$id_panel."'>";
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $temp_account=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$hasil["user_id"])));
                if($temp_account)
                {
                    $namauserprof = (!isset($temp_account['username'])?"":$temp_account['username']);
                    if(isset($temp_account['fb_id']))
                    {
                        if($temp_account['fb_id']!="")
                        {
                            $picture = "https://graph.facebook.com/".$temp_account['fb_id']."/picture?type=large";
                        }
                    }                        
                }
                $this->mongo_db->select_collection("Properties");                    
                $temp_user=$this->mongo_db->findOne(array('lilo_id'=>(string)$hasil["user_id"]));
                if($temp_user)
                {
                    $namauser = (!isset($temp_user['avatarname'])?"No Name":$temp_user['avatarname']);
                    if(isset($temp_user['picture']))
                    {
                        if($temp_user['picture']!="")
                        {
                            $picture = $temp_user['picture'];
                        }
                    }
                }
                $temp_msg .= "<img src='".$picture."' alt='".$namauser."' />";
                $temp_msg .= "<div class='content'>";
                if($hasil["user_id"]==$this->session->userdata('user_id'))
                {
                    $temp_msg .= "<a href='#' onclick='hapuscomment(\"".(string)$hasil["_id"]."\",\"".$id_panel."\");return false;' class='close tooltips' title='Delete this Comment' data-trigger='hover' data-original-title='Delete this Comment'>&times;</a>";
                }     
                $temp_msg .= "<span class='commented'>";
                $temp_msg .= "<a href='".$this->template_admin->link("home/account/".$namauserprof)."'>".$namauser."</a>";
                $temp_msg .= "<p>".$hasil["comment"]."</p>";
                $temp_msg .= "</span>";
                $temp_msg .= "<span class='actions'>";
                $temp_msg .= "<i class='error alignRight'>".date('Y-m-d H:i:s', $hasil['datetime']->sec)."</i>";
                $temp_msg .= "</span>";
                $temp_msg .= "</div>";
                $temp_msg .="</li>";
                $i++;
            }
            $output['jml'] = $i;
            $output['message'] = $temp_msg;
        }       
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/news/detail/'. urlencode($alias)); 
        }
    }
    function loadnewdata($idnews="",$alias="",$count=0)
    {        
        $alias = urldecode($alias);
        $output['message'] = "Fail to load more data";
        $output['success'] = TRUE;
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $output['count'] = $this->mongo_db->count2(array('id'=>(string)$idnews,'type'=>'NewsComment'));
        $output['jml'] = $output['count']-$count;
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/news/detail/'.urlencode($alias)); 
        }
    }
    function loaddatanew($idnews="",$alias="",$count=0)
    {        
        $alias = urldecode($alias);
        $output['message'] = "Fail to load more data";
        $output['success'] = FALSE;
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $output['count'] = $this->mongo_db->count2(array('id'=>(string)$idnews,'type'=>'NewsComment'));
        $indexke =0;
        if($count<$output['count'])
        {
            $jmlload =(int)($output['count']-$count);
            $cekdata = $this->mongo_db->find(array('id'=> (string)$idnews), 0, $jmlload, array("datetime"=>-1));
            if($cekdata)
            {
                $output['success'] = TRUE;
                $temp_msg="";
                $i = 0;
                foreach($cekdata as $hasil)
                {
                    $id_panel = "newest".$i."_".$this->tambahan_fungsi->global_get_random(6);
                    $picture = base_url()."resources/image/index.jpg";
                    $namauser = "";
                    $namauserprof = "";
                    $temp_msg .="<li id='".$id_panel."'>";
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("Account");
                    $temp_account=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$hasil["user_id"])));
                    if($temp_account)
                    {
                        $namauserprof = (!isset($temp_account['username'])?"":$temp_account['username']);
                        if(isset($temp_account['fb_id']))
                        {
                            if($temp_account['fb_id']!="")
                            {
                                $picture = "https://graph.facebook.com/".$temp_account['fb_id']."/picture?type=large";
                            }
                        }                        
                    }
                    $this->mongo_db->select_collection("Properties");                    
                    $temp_user=$this->mongo_db->findOne(array('lilo_id'=>(string)$hasil["user_id"]));
                    if($temp_user)
                    {
                        $namauser = (!isset($temp_user['avatarname'])?"No Name":$temp_user['avatarname']);
                        if(isset($temp_user['picture']))
                        {
                            if($temp_user['picture']!="")
                            {
                                $picture = $temp_user['picture'];
                            }
                        }
                    }
                    $temp_msg .= "<img src='".$picture."' alt='".$namauser."' />";
                    $temp_msg .= "<div class='content'>";
                    if($hasil["user_id"]==$this->session->userdata('user_id'))
                    {
                        $temp_msg .= "<a href='#' onclick='hapuscomment(\"".(string)$hasil["_id"]."\",\"".$id_panel."\");return false;' class='close tooltips' title='Delete this Comment' data-trigger='hover' data-original-title='Delete this Comment'>&times;</a>";
                    }     
                    $temp_msg .= "<span class='commented'>";
                    $temp_msg .= "<a href='".$this->template_admin->link("home/account/".$namauserprof)."'>".$namauser."</a>";
                    $temp_msg .= "<p>".$hasil["comment"]."</p>";
                    $temp_msg .= "</span>";
                    $temp_msg .= "<span class='actions'>";
                    $temp_msg .= "<i class='error alignRight'>".date('Y-m-d H:i:s', $hasil['datetime']->sec)."</i>";
                    $temp_msg .= "</span>";
                    $temp_msg .= "</div>";
                    $temp_msg .="</li>";
                    $i++;
                }
                $output['message'] = $temp_msg;
            }
        }   
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/news/detail/'.urlencode($alias)); 
        }
    }
    function delete($id="",$alias="")
    {
        $alias = urldecode($alias);
        $this->cek_session->cek_login();
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id)));
        $url = current_url();
        $user = $this->session->userdata('username');
        $this->m_user->tulis_log("Delete Comment on News Stream",$url,$user);
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
            redirect('home/news/detail/'.urlencode($alias)); 
        }
    }
    function userlike($id="",$alias="")
    {
        $alias = urldecode($alias);
        $this->cek_session->cek_login();
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $filter = array('user_id' => $this->session->userdata('user_id'),"id"=>(string)$id,'type'=>'NewsLove');
        $cekstate = $this->mongo_db->findOne($filter);
        if($cekstate)
        {
            $output = array(
                "message" =>"You not Like this",
                "success" =>FALSE,
            );
            $this->mongo_db->remove($filter);
        }
        else
        {
            $filter = array(
                'user_id' => $this->session->userdata('user_id'),
                "id"=>(string)$id,
                'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                'type'=>'NewsLove'
            );
            $this->mongo_db->insert($filter);
            $output = array(
                "message" =>"You Like this",
                "success" =>TRUE,
            );
        }
        if(IS_AJAX)
        {
            echo json_encode( $output );
        }
        else
        {
            redirect('home/news/detail/'.urlencode($alias)); 
        }
    }
}
