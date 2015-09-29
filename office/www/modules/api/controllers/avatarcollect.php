<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Avatarcollect extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model(array("m_avatar","m_userdata"));
    }
    /*
     * Methode : GET
     * API Get Count Avatar Collection User
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function count($iduser="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social"); 
            $filtering = array(
                'type'=>'AvatarCollection',
                "user_id"=>(string)$iduser,
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
     * API Delete Avatar Collection
     * Parameter :
     * 1. _id
     * Return JSON
     */
    function delete_collection($id_collect="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail to delete user avatar collection.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");   
            $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($id_collect)));
            if($tempdata)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("AvatarMix");
                $this->mongo_db->update(array(
                    "_id"=> $this->mongo_db->mongoid((string)$tempdata["id"])
                    ),array(
                        '$inc'=>array("collect"=>-1)
                )); 
            }
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $this->mongo_db->remove(array("_id"=> $this->mongo_db->mongoid((string)$tempdata["notification_id"])));   
            $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid($id_collect)));            
            $output['success'] = TRUE;
            $output['message'] = "Delete User Avatar Collection.";
            $url = current_url();
            $this->m_user->tulis_log("Delete User Mix Collection.",$url,"API Unity");
        }
        echo json_encode($output);
    }   
    /*
     * Methode : GET
     * API Add Avatar Collection User
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * 2. id_collection
     * Return JSON
     */
    function add_collection($userid="",$mix_id="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Fail to delete user avatar collection.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("AvatarMix");      
            $tempdatamix = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($mix_id)));
            $cekdata = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($mix_id))); 
            if($cekdata>0)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account");
                $cekdatauser = $this->mongo_db->count2(array("_id"=> $this->mongo_db->mongoid($userid))); 
                if($cekdatauser>0)
                {
                    if($userid != $tempdatamix["user_id"])
                    {
                        $this->mongo_db->select_db("Social");
                        $this->mongo_db->select_collection("Social");
                        $filtering = array(
                            'type'=>'AvatarCollection',
                            "user_id"=>(string)$userid,
                            "id" => (string)$mix_id,
                        ); 
                        $tempdata = $this->mongo_db->findOne($filtering);
                        if(!$tempdata)
                        {
                            $this->mongo_db->select_db("Users");
                            $this->mongo_db->select_collection("AvatarMix");
                            $this->mongo_db->update(array(
                                "_id"=> $this->mongo_db->mongoid((string)$tempdatamix["_id"]
                                )),array(
                                    '$inc'=>array("collect"=>1)
                                ),array('upsert' => TRUE)); 
                        }
                        $this->mongo_db->select_db("Social");
                        $this->mongo_db->select_collection("Social");
                        $this->mongo_db->remove(array(
                            'type'=>'UserCollectNotification',
                            "id" => (string)$mix_id,
                            "friend_id"=>$userid,
                            "user_id"=> $tempdatamix['user_id'],
                        )); 
                        $arrayinsert = array(
                            'type'=>'UserCollectNotification',
                            "id" => (string)$mix_id,
                            "friend_id"=>$userid,
                            "user_id"=> $tempdatamix['user_id'],
                            "read"=>FALSE,
                            'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                        );
                        $tempinsert = $this->mongo_db->insert($arrayinsert);
                        $datatinsert = array(
                            'type'=>'AvatarCollection',
                            "user_id"=>(string)$userid,
                            "id" => (string)$mix_id,
                            'notification_id'=>(string)$tempinsert,
                            "user_owner" => (string)$tempdatamix["user_id"],
                            'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                        );
                        $this->mongo_db->update($filtering,array('$set' => $datatinsert),array('upsert' => TRUE));         
                        $output['success'] = TRUE;
                        $output['message'] = "User Collec Avatar.";
                        $url = current_url();
                        $this->m_user->tulis_log("Update/Add User Mix Collection.",$url,"API Unity");
                    }
                    else
                    {
                        $output['message'] = "This is your mix, you can collect it.";
                    }
                }
                else
                {
                    $output['message'] = "ID User not Found";
                }
            }
            else
            {
                $output['message'] = "ID Mix not Found";
            }            
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get Detail Avatar Collection
     * Parameter :
     * 1. id (parameter _id field from database Social collection Social(required))
     * 2. tipedata [web,iOS,Android]
     * Return JSON
     */
    function detail_collections($id="",$tipedata="android")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['message'] = "Error load data.";
        if($ceklogin)
        {
            $this->mongo_db->select_db("Social");
            $this->mongo_db->select_collection("Social");
            $tempdatacollection = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($id)));
            if($tempdatacollection)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("AvatarMix");
                $tempdata = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($tempdatacollection["id"])));
                if($tempdata)
                {
                    $output['success'] = TRUE;
                    $output['data']["id"] = (string)$tempdata["_id"];
                    $output['data']["datetime"] = date('Y-m-d H:i:s', $tempdatacollection['datetime']->sec);
                    $tempdtuser = $this->m_userdata->user_properties($tempdatacollection["user_id"]);
                    $tempdtuserowner = $this->m_userdata->user_properties($tempdatacollection["user_owner"]);
                    $tempdtconfigall = $this->m_avatar->avatarconfig($tempdata['configuration'],$tempdata["bodytype"],$tipedata);
                    $output['data']["user"] = array(
                        "id" => (string)$tempdatacollection["user_id"],
                        "fullname" => $tempdtuser["fullname"],
                        "sex" => $tempdtuser["sex"],
                        "picture" => $tempdtuser["picture"],
                    );
                    $output['data']["owner"] = array(
                        "id" =>(string)$tempdatacollection["user_owner"],
                        "fullname" => $tempdtuserowner["fullname"],
                        "sex" => $tempdtuserowner["sex"],
                        "picture" => $tempdtuserowner["picture"],
                    );      
                    $tempconfigstring = str_replace('"',"'", json_encode($tempdtconfigall['configurations']));
                    $tempdataconf = $tempdata['configuration'];
                    if($tempconfigstring == "[[]]")
                    {
                        $tempconfigstring = "";
                        $tempdataconf = "";
                    }
                    $output['data']['configurations'] = $tempconfigstring;
//                    $output['data']['dataconf'] = $tempdataconf;
                    $output['data']['name'] = $tempdata['name'];
                    $output['data']['gender'] = $tempdata['gender'];
                    $output['data']['bodytype'] = $tempdata['bodytype'];
                    $output['data']['author'] = $tempdata['author'];
                    $output['data']['description'] = $tempdata['description'];
                    $output['data']['brand_id'] = $tempdata['brand_id'];
                    $output['data']['picture'] = $tempdata['picture'];
                    $output['data']['url_picture'] = $this->config->item('path_asset_img') . "preview_images/" . $tempdata['picture'];
                }
            }
            else
            {
                $output['message'] = "User Avatar Collection is not found.";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get List of User Avatar Collection
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function list_data($iduser="",$start=0)
    {        
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Game");        
            $this->mongo_db->select_collection("Settings");
            $tempdt = $this->mongo_db->findOne(array("code"=>"limitavitem"));
            $limit = 10;
            if($tempdt)
            {
                $limit = $tempdt['value'];
            }   
            $filtering = array(
                'type'=>'AvatarCollection',
                "user_id"=>(string)$iduser,
            ); 
            $output['success'] = TRUE;
            $listdata = array();
            $output['count'] = (int)$this->mongo_db->count2($filtering);
            $tempdata = $this->mongo_db->find($filtering,(int)$start,(int)$limit,array("datetime"=>-1));
            if($tempdata)
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("AvatarMix");
                foreach ($tempdata as $dtcoll)
                {
                    $tempdatamix = $this->mongo_db->findOne(array("_id"=>$this->mongo_db->mongoid($dtcoll["id"])));
                    if($tempdatamix)
                    {
                        $tempdtuser = $this->m_userdata->user_properties($dtcoll["user_id"]);
                        $tempdtuserowner = $this->m_userdata->user_properties($dtcoll["user_owner"]);
                        $listdata[] = array(
                            "id" => (string)$dtcoll["_id"],
                            "datetime" => date('Y-m-d H:i:s', $dtcoll['datetime']->sec),
                            "user" => array(
                                "id" => (string)$dtcoll["user_id"],
                                "fullname" => $tempdtuser["fullname"],
                                "sex" => $tempdtuser["sex"],
                                "picture" => $tempdtuser["picture"],
                            ),
                            "owner" => array(
                                "id" => (string)$dtcoll["user_owner"],
                                "fullname" => $tempdtuserowner["fullname"],
                                "sex" => $tempdtuserowner["sex"],
                                "picture" => $tempdtuserowner["picture"],
                            ),
                            'name' => $tempdatamix['name'],
                            'gender' => $tempdatamix['gender'],
                            'bodytype' => $tempdatamix['bodytype'],
                            'author' => $tempdatamix['author'],
                            'description' => $tempdatamix['description'],
                            'brand_id' => $tempdatamix['brand_id'],
                            'picture' => $tempdatamix['picture'],
                            "url_picture" => $this->config->item('path_asset_img') . "preview_images/" . $tempdatamix['picture'],
                        );
                    }
                }
                $output['data'] = $listdata;
            }      
        }
        echo json_encode($output);
    }
}
