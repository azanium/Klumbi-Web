<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avataritem extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model("m_userdata");
    }  
    /*
     * Methode : GET
     * API Cek Avatar Item Love
     * Parameter :
     * 1. avatar_id
     * 2. user_id
     * Return JSON
     */
    function cek_like($avatar_id="",$user_id="")
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
                'type'=>'AvatarItemLove',
                'avatar_id'=>$avatar_id,
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
                'type'=>'AvatarItemLove',
                'avatar_id'=>$avatar_id
            );
            $tempdatacount = $this->mongo_db->count2($filtering);
            $output['count'] = (int)$tempdatacount;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Button Avatar Item Love
     * Parameter :
     * 1. avatar_id
     * 2. user_id
     * Return JSON
     */
    function button_like($avatar_id="",$user_id="")
    {  
        $url = current_url();
        $output['success'] = TRUE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;        
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");        
            $cekdata = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($avatar_id))); 
            if($cekdata>0)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($user_id))); 
                if($cekdatauser>0)
                {
                    $this->mongo_db->select_db("Social");
                    $this->mongo_db->select_collection("Social"); 
                    $filtering = array(
                        'type'=>'AvatarItemLove',
                        'avatar_id'=>$avatar_id,
                        "user_id"=>$user_id,
                    );
                    $tempdata = $this->mongo_db->findOne($filtering);
                    if($tempdata)
                    {
                        $output['like'] = FALSE;
                        $output['message'] = "You UnLove this";
                        $this->mongo_db->remove($filtering); 
                        $this->m_user->tulis_log("Unlove an Avatar Item",$url,"API Unity");
                    }
                    else
                    {
                        $output['like'] = TRUE;
                        $output['message'] = "You Love this";
                        $filtering = array_merge($filtering,array('datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))));
                        $this->mongo_db->insert($filtering);
                        $this->m_user->tulis_log("Love an Avatar Item",$url,"API Unity");
                    }
                }
                else
                {
                    $output['like'] = FALSE;
                    $output['message'] = "ID User not Found";
                }
            }
            else
            {
                $output['like'] = FALSE;
                $output['message'] = "ID Avatar Item not Found";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count Avatar Item Love
     * Parameter :
     * 1. avatar_id
     * Return JSON
     */
    function count_like($avatar_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'AvatarItemLove',
                'avatar_id'=>$avatar_id
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
     * API Get List of Avatar Item Love
     * Parameter :
     * 1. avatar_id
     * Return JSON
     */
    function list_like($avatar_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $filtering = array(
                'type'=>'AvatarItemLove',
                'avatar_id'=>$avatar_id
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
                    $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
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
     * API Add Avatar Item Comments
     * Parameter :
     * 1. avatar_id
     * 2. user_id
     * 3. comment
     * Return JSON
     */
    function add_comment()
    {  
        $avatar_id = isset($_POST['avatar_id'])?$_POST['avatar_id']:"";
        $user_id = isset($_POST['user_id'])?$_POST['user_id']:"";
        $comment = isset($_POST['comment'])?$_POST['comment']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail to add Comment.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Assets");
            $this->mongo_db->select_collection("Avatar");        
            $cekdata = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($avatar_id))); 
            if($cekdata>0)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($user_id))); 
                if($cekdatauser>0)
                {
                    $this->mongo_db->select_db("Social");
                    $this->mongo_db->select_collection("Social");
                    $datatinsert = array(
                        'type'=>'AvatarItemComment',
                        'avatar_id' =>$avatar_id,
                        "comment" => $comment,
                        "user_id" => $user_id,
                        'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                    );
                    $tempdata = $this->mongo_db->insert($datatinsert);
                    if($tempdata)
                    {
                        $output['success'] = TRUE;
                        $output['message'] = "Comment is Add.";
                        $url = current_url();
                        $this->m_user->tulis_log("Add Comment Avatar Item",$url,"API Unity");
                    }
                }
                else
                {
                    $output['message'] = "ID User not Found";
                }
            }
            else
            {
                $output['message'] = "ID Avatar Item not Found";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Delete Avatar Item Comments
     * Parameter :
     * 1. _id
     * Return JSON
     */
    function delete_comment($id_="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail to delete comment.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $tempdata = $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id_)));            
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['message'] = "Comment is deleted.";
                $url = current_url();
                $this->m_user->tulis_log("Delete Comment Avatar Item",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Count Avatar Item Comment
     * Parameter :
     * 1. avatar_id
     * Return JSON
     */
    function count_comment($avatar_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'AvatarItemComment',
                'avatar_id'=>$avatar_id
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
     * API Get List of Avatar Item Comments
     * Parameter :
     * 1. page
     * 2. avatar_id
     * Return JSON
     */
    function list_comment($avatar_id="",$start=0)
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
                'type'=>'AvatarItemComment',
                'avatar_id'=>$avatar_id
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
                        'comment'=>$dt['comment'],
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
}