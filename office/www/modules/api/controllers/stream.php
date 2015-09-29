<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Stream extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    }
    /*
     * Methode : GET
     * API Get Random Data
     * Parameter :
     * 1. count data random
     * Return JSON
     */
    function getdata($iduser="",$tglsebelumnya=1)
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $tanggal = date('Y-m-d H:i:s', strtotime( "-".(int)$tglsebelumnya." days"));
        if($ceklogin)
        {        
            $output['success'] = TRUE;
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");                
            $filtering = array("join_date" => array('$gte'=>$this->mongo_db->time(strtotime($tanggal))) );
            $tempdata = $this->mongo_db->find($filtering,0,0,array("join_date" => -1));
            $output['count_register'] = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['join_date']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['join_date']->sec);
                    }
                    $tempdtuser = $this->m_userdata->user_properties((string)$dt["_id"]);
                    $listdata[] = array(
                        "id"=>(string)$dt["_id"],
                        "email"=>$dt["email"],
                        "artist"=>(bool)$dt["artist"],
                        'datetime'=>$tglcreate,                            
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data_new_register'] = $listdata;
            }
            $this->mongo_db->select_db("Articles");
            $this->mongo_db->select_collection("ContentNews"); 
            $filtering = array("state_document" => "publish", "update" => array('$gte'=>$this->mongo_db->time(strtotime($tanggal))) );
            $tempdata = $this->mongo_db->find($filtering,0,0,array("update" => -1));
            $output['count_news'] = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['update']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['update']->sec);
                    }
                    $listdata[] = array(
                        "id"=>(string)$dt["_id"],
                        "title"=>$dt["title"],
                        "text"=>$dt["mobile"],
                        'datetime'=>$tglcreate,
                    );
                }                
                $output['data_news'] = $listdata;
            }
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar"); 
            $filtering = array("last_update" => array('$gte'=>$this->mongo_db->time(strtotime($tanggal))) );
            $tempdata = $this->mongo_db->find($filtering,0,0,array("last_update" => -1));
            $output['count_avataritem'] = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['update']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['last_update']->sec);
                    }
                    $listdata[] = array(
                        "id"=>(string)$dt["_id"],
                        "name"=>$dt["name"],
                        "category"=>$dt["category"],
                        "tipe"=>$dt["tipe"],
                        'datetime'=>$tglcreate,
                    );
                }                
                $output['data_avataritem'] = $listdata;
            }
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Follower',
                "user_id"=>$iduser,
                "datetime" => array('$gte'=>$this->mongo_db->time(strtotime($tanggal))) );
            $tempdata = $this->mongo_db->find($filtering,0,0,array("datetime" => -1));
            $output['count_new_follower'] = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['datetime']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
                    }      
                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                    $listdata[] = array(
                        "id"=>(string)$dt["_id"],
                        'datetime'=>$tglcreate,
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data_new_follower'] = $listdata;
            }
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>array('$in'=>array("NewsLove","NewsComment","BrandLove","BrandComment","StateOfMind","Register","AvatarMixlove","AvatarMixComment","AvatarCollection","BannerLove","BannerComment","ChangeStateOfMind","ChangePicture")), 
                "datetime" => array('$gte'=>$this->mongo_db->time(strtotime($tanggal))) );
            $tempdata = $this->mongo_db->find($filtering,0,0,array("datetime" => -1));
            $output['count_notification'] = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['datetime']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
                    }
                    $action = "";
                    $detail = "";
                    $detaillink="";
                    if($dt['type'] === "NewsLove")
                    {
                        $this->mongo_db->select_db("Articles");
                        $this->mongo_db->select_collection("ContentNews");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id']))); 
                        $action = "Love News";
                        $detail = "This user love news ".$tempdata["title"];
                        $detaillink = $dt['id'];
                    }
                    else if($dt['type'] === "NewsComment")
                    {
                        $this->mongo_db->select_db("Articles");
                        $this->mongo_db->select_collection("ContentNews");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));  
                        $action = "Comment on News ".$tempdata["title"];
                        $detail = $dt["comment"];
                        $detaillink = $dt['id'];
                    }
                    else if($dt['type'] === "BrandLove")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Brand");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['brand_id'])));  
                        $action = "Love Store";
                        $detail = "This user love store ".$tempdata["name"];
                        $detaillink = $dt['brand_id'];
                    }
                    else if($dt['type'] === "BrandComment")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Brand");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['brand_id'])));  
                        $action = "Comment on Store ".$tempdata["name"];
                        $detail = $dt["comment"];
                        $detaillink = $dt['brand_id'];
                    }
                    else if($dt['type'] === "StateOfMind")
                    {           
                        $action = "Change Status";
                        $detail = $dt["StateMind"];
                    }
                    else if($dt['type'] === "Register")
                    {                                   
                        $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                        $action = "Register";
                        $detail = "New User ".$tempdtuser["fullname"]." Register";
                        $detaillink = $dt['user_id'];
                    }
                    else if($dt['type'] === "Follower")
                    {         
                        $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                        $action = "Follow A Friend";
                        $detail = "Follow of ".$tempdtuser["fullname"];
                        $detaillink = $dt['friend_id'];
                    }
                    else if($dt['type'] === "AvatarMixlove")
                    {
                        $this->mongo_db->select_db("Users");
                        $this->mongo_db->select_collection("AvatarMix");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id'])));
                        $action = "Love Mix User";
                        $detail = "This user love MixUser ".$tempdata["name"];
                        $detaillink = $dt['mix_id'];
                    }
                    else if($dt['type'] === "AvatarMixComment")
                    {
                        $this->mongo_db->select_db("Users");
                        $this->mongo_db->select_collection("AvatarMix");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id']))); 
                        $action = "Comment a Mix ".$tempdata["name"];
                        $detail = $dt["comment"];
                        $detaillink = $dt['mix_id'];
                    }
                    else if($dt['type'] === "AvatarCollection")
                    {
                        $this->mongo_db->select_db("Users");
                        $this->mongo_db->select_collection("AvatarMix");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));   
                        $action = "Collect a Mix ";
                        $detail = "Collect a mix ".$tempdata["name"];
                        $detaillink = $dt['id'];
                    }
                    else if($dt['type'] === "BannerLove")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Banner");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['banner_id'])));
                        $action = "Love a Banner";
                        $detail = "This user love ".$tempdata["name"];
                        $detaillink = $dt['banner_id'];
                    }
                    else if($dt['type'] === "BannerComment")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Banner");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['banner_id'])));
                        $action = "Comment on Banner ".$tempdata["name"];
                        $detail = $dt["comment"];
                        $detaillink = $dt['banner_id'];
                    }
                    else if($dt['type'] === "AvatarItemLove")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Avatar");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['avatar_id'])));  
                        $action = "Love an Avatar Item ";
                        $detail = "This user love ".$tempdata["name"];
                        $detaillink = $dt['avatar_id'];
                    }
                    else if($dt['type'] === "AvatarItemComment")
                    {
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Avatar");        
                        $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['avatar_id']))); 
                        $action = "Comment on Avatar Item ".$tempdata["name"];
                        $detail = $dt["comment"];
                        $detaillink = $dt['avatar_id'];
                    }
                    else if($dt['type'] === "ChangeStateOfMind")
                    {
                        $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                        $action = "Change State of Mind ".$tempdtuser["fullname"];
                        $detail = isset($dt["text"])?$dt["text"]:"";
                        $detaillink = $dt['user_id'];
                    }
                    else if($dt['type'] === "ChangePicture")
                    {
                        $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                        $action = "Change Picture ".$tempdtuser["fullname"];
                        $detail = isset($dt["pic"])?$dt["pic"]:"";
                        $detaillink = $dt['user_id'];
                    }
                    $listdata[] = array(
                        "id"=>(string)$dt["_id"],
                        "header"=>$action,
                        "detail"=>$detail,
                        "link_id"=>$detaillink,
                        'datetime'=>$tglcreate,
                    );
                }                
                $output['data_notification'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}
