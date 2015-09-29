<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    }  
    /*
     * Methode : GET
     * API Cek User Follow Or Not
     * Parameter :
     * 1. user_id
     * 2. friend_id
     * Return JSON
     */
    function cek_follower($user_id="",$friend_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['follow'] = FALSE;
        $output['message'] = "You are not Follow this user";
        if($ceklogin)
        {            
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Follower',
                'friend_id'=>$friend_id,
                "user_id"=>$user_id,
            );
            $tempdata = $this->mongo_db->findOne($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['follow'] = TRUE;
                $output['message'] = "You are Follow this user";
            }                
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Button Follower User
     * Parameter :
     * 1. user_id
     * 2. friend_id
     * Return JSON
     */
    function button_follower($user_id="",$friend_id="")
    {  
        $output['success'] = TRUE;
        $output['message'] = "Error, please try again letter.";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;        
        if($ceklogin)
        {
            if($friend_id != $user_id)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($user_id))); 
                if($cekdatauser>0)
                {
                    $cekdatafriend = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($friend_id))); 
                    if($cekdatafriend>0)
                    {
                        $this->mongo_db->select_db("Social");
                        $this->mongo_db->select_collection("Social"); 
                        $filtering = array(
                            'type'=>'Follower',
                            'friend_id'=>$friend_id,
                            "user_id"=>$user_id,
                        );
                        $tempdata = $this->mongo_db->findOne($filtering);
                        if($tempdata)
                        {
                            $output['follow'] = FALSE;
                            $output['message'] = "You unfollow this user";
                            $this->mongo_db->remove($filtering); 
                        }
                        else
                        {
                            $output['follow'] = TRUE;
                            $output['message'] = "You follow this user";
                            $filtering = array_merge($filtering,array('datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))));
                            $this->mongo_db->insert($filtering);
                        }
                    }
                    else
                    {
                        $output['follow'] = FALSE;
                        $output['message'] = "ID Friend not Found";
                    }
                }
                else
                {
                    $output['follow'] = FALSE;
                    $output['message'] = "ID User not Found";
                }
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count Follower
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function count_following($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Follower',
                "user_id"=>$user_id,
            );
            $tempdata = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Follower
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function list_following($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $filtering = array(
                'type'=>'Follower',
                "user_id"=>$user_id,
            );
            $output['count'] = $this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,0,0,array("datetime"=>-1));
            if($tempdata)
            {
                $output['success'] = TRUE;
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
                        'datetime'=>$tglcreate,
                        "user_id"=>$dt["user_id"],
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }   
    /*
     * Methode : GET
     * API Get Count Following
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function count_follower($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Follower',
                "friend_id"=>$user_id,
            );
            $tempdata = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Following
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function list_follower($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $filtering = array(
                'type'=>'Follower',
                "friend_id"=>$user_id,
            );
            $output['count'] = $this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,0,0,array("datetime"=>-1));
            if($tempdata)
            {
                $output['success'] = TRUE;
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
                        'datetime'=>$tglcreate,
                        "user_id"=>$dt["user_id"],
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }   
    /*
     * Methode : GET
     * API Add User Status
     * Parameter :
     * 1. user_id
     * 2. comment
     * Return JSON
     */
    function add_status()
    {
        $user_id = isset($_GET['user_id'])?$_GET['user_id']:"";
        $comment = isset($_GET['comment'])?$_GET['comment']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail add Status.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($user_id))); 
            if($cekdatauser>0)
            {
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social");
                $datatinsert = array(
                    'type'=>'StateOfMind',
                    "StateMind" => $comment,
                    "user_id" => (string)$user_id,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                );
                $tempdata = $this->mongo_db->insert($datatinsert);
                if($tempdata)
                {
                    $output['success'] = TRUE;
                    $output['message'] = "Status is Add.";
                    $url = current_url();
                    $this->m_user->tulis_log("Add Status User",$url,"API Unity");
                }
            }
            else
            {
                $output['success'] = FALSE;
                $output['message'] = "ID User not Found";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Delete Status User
     * Parameter :
     * 1. _id
     * Return JSON
     */
    function delete_status($id_="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail delete status.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $tempdata = $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id_)));            
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['message'] = "Status is deleted.";
                $url = current_url();
                $this->m_user->tulis_log("Delete Status User",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count User Status Comment
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function count_status($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'StateOfMind',
                'user_id'=>$user_id
            );
            $tempdata = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of User Status
     * Parameter :
     * 1. page
     * 2. user_id
     * Return JSON
     */
    function list_status($user_id="",$start=0)
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitcomment"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $filtering = array(
                'type'=>'StateOfMind',
                'user_id'=>$user_id
            );
            $output['count'] = $this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,(int)$start,(int)$limit,array("datetime"=>-1));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $listdata = array();
                foreach($tempdata as $dt)
                {
                    $tglcreate="";
                    if($dt['datetime']!="")
                    {
                        $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
                    }
                    $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                    $listdata[] = array(
                        "_id" => (string)$dt['_id'],
                        'datetime'=>$tglcreate,
                        'status'=>$dt['StateMind'],
                        "user_id"=>$dt["user_id"],
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Cek User Love Profile Friend
     * Parameter :
     * 1. user_id
     * 2. friend_id
     * Return JSON
     */
    function cek_pagelike($user_id="",$friend_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['like'] = FALSE;
        $output['message'] = "Love this";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Pagelove',
                'friend_id'=>$friend_id,
                "user_id"=>$user_id,
            );
            $tempdata = $this->mongo_db->findOne($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['like'] = TRUE;
                $output['message'] = "You Love this";
            }
            $filtering = array(
                'type'=>'Pagelove',
                "user_id"=>$user_id
            );
            $tempdatacount = $this->mongo_db->count2($filtering);
            $output['count'] = (int)$tempdatacount;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Button Avatar Mix Love
     * Parameter :
     * 1. mix_id
     * 2. user_id
     * Return JSON
     */
    function button_pagelike($user_id="",$friend_id="")
    {  
        $url = current_url();
        $output['success'] = TRUE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;        
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");      
            $tempdatamix = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($user_id)));
            $cekdata = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($user_id))); 
            if($cekdata>0)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($friend_id))); 
                if($cekdatauser>0)
                {
                    $this->mongo_db->select_db("Social");
                    $this->mongo_db->select_collection("Social"); 
                    $filtering = array(
                        'type'=>'Pagelove',
                        'friend_id'=>$friend_id,
                        "user_id"=>$user_id,
                    );
                    $tempdata = $this->mongo_db->findOne($filtering);
                    if($tempdata)
                    {
                        $output['like'] = FALSE;
                        $output['message'] = "You UnLove this";
                        $this->mongo_db->remove($filtering); 
                        $this->mongo_db->remove(array("_id"=> $this->mongo_db->mongoid($tempdata["notification_id"])));
                        $this->m_user->tulis_log("Unlove a Page User",$url,"API Unity");
                    }
                    else
                    {
                        $output['like'] = TRUE;
                        $output['message'] = "You Love this";
                        $arrayinsert = array(
                            'type'=>'UserLovePageNotification',
                            'friend_id'=>$friend_id,
                            "user_id"=>$user_id,
                            "read"=>FALSE,
                            'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                        );
                        $tempinsert = $this->mongo_db->insert($arrayinsert);
                        $filtering = array(
                            'type'=>'Pagelove',
                            'friend_id'=>$friend_id,
                            "user_id"=>$user_id,
                            'notification_id'=>(string)$tempinsert,
                            'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                        );
                        $this->mongo_db->insert($filtering);
                        $this->m_user->tulis_log("Love a Page User",$url,"API Unity");
                    }
                }
                else
                {
                    $output['like'] = FALSE;
                    $output['message'] = "ID Friend who love not Found";
                }
            }
            else
            {
                $output['like'] = FALSE;
                $output['message'] = "ID User Page not Found";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count Page User Love
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function count_pagelike($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['count'] = 0;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'Pagelove',
                "user_id"=>$user_id
            );
            $tempdata = $this->mongo_db->count2($filtering);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['count'] = (int)$tempdata;
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of Page User Love
     * Parameter :
     * 1. user_id
     * Return JSON
     */
    function list_like($user_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $filtering = array(
                'type'=>'Pagelove',
                "user_id"=>$user_id,
            );
            $output['count'] = $this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,0,0,array("datetime"=>-1));
            if($tempdata)
            {
                $output['success'] = TRUE;
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
                        'datetime'=>$tglcreate,
                        "user_id"=>$dt["friend_id"],
                        "fullname"=>$tempdtuser["fullname"],
                        "sex"=>$tempdtuser["sex"],
                        "picture"=>$tempdtuser["picture"],
                    );
                }                
                $output['data'] = $listdata;
            }
        }
        echo json_encode($output);
    }
}