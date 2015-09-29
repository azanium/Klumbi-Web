<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
    }  
    /*
     * Methode : GET
     * API Change Status
     * Parameter :
     * 1. id
     * 2. status [online, busy, away, offline]
     * Return JSON
     */
    function change_state()
    {
        $_id = isset($_GET['id'])?$_GET['id']:"";     
        $status = isset($_GET['status'])?$_GET['status']:"offline"; 
        $output['success'] = FALSE;
        $output['message'] = "Fail change user state.";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $dataproperties = array('status'  =>$status);
            $tempdata = $this->mongo_db->update(array("lilo_id"=>(string)$_id),array('$set'=>$dataproperties));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['message'] = "Success change user State.";
                $url = current_url();
                $this->m_user->tulis_log("Change User State",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get User Status
     * Parameter :
     * 1. id
     * Return JSON
     */
    function get_state($_id="")
    {  
        $output['success'] = FALSE;
        $output['state'] = "offline";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");
            $tempdata = $this->mongo_db->findOne(array("lilo_id"=>(string)$_id));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['state'] = (isset($tempdata['status'])?$tempdata['status']:"offline"); 
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get User gender
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function gender($userid="")
    {  
        $output['success'] = FALSE;
        $dataval = "male";
        $datavalbody = "medium";
        $output['gender'] = $dataval;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['id'] = $userid; 
        if($ceklogin)
        {
            $output['success'] = TRUE;
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Avatar");        
            $tempdata = $this->mongo_db->findOne(array("user_id"=>$userid));
            if($tempdata)
            {                                
                $output['gender'] = isset($tempdata["gender"])?$tempdata["gender"]:$dataval; 
                $output['bodyType'] = isset($tempdata["bodytype"])?$tempdata["bodytype"]:$datavalbody; 
            }   
            else
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Properties");
                $temp_properties = $this->mongo_db->findOne(array("lilo_id"=>(string)$userid));
                if($temp_properties)
                {
                    $output['gender'] = isset($temp_properties["sex"])?$temp_properties["sex"]:$dataval;
                    $output['bodyType'] = isset($temp_properties["bodytype"])?$temp_properties["bodytype"]:$datavalbody;
                }
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get User Body Size
     * Parameter :
     * 1. userid (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function bodysize($userid="")
    {  
        $output['success'] = FALSE;
        $dataval = "medium";
        $output['bodysize'] = $dataval;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['id'] = $userid;
        if($ceklogin)
        {
            $output['success'] = TRUE;
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Avatar");        
            $tempdata = $this->mongo_db->findOne(array("user_id"=>$userid));
            if($tempdata)
            {                
                $output['bodysize'] = isset($tempdata["bodytype"])?$tempdata["bodytype"]:$dataval;                
            }   
            else
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Properties");
                $temp_properties = $this->mongo_db->findOne(array("lilo_id"=>(string)$userid));
                if($temp_properties)
                {
                    $output['bodysize'] = isset($temp_properties["bodytype"])?$temp_properties["bodytype"]:$dataval;
                }
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Cek User by Email
     * Parameter :
     * 1. email
     * Return JSON
     */
    function check_email($useremail="")
    {  
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['isvalid'] = TRUE;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("email"=>$useremail));
            if($tempdata)
            {                
                $output['isvalid'] = FALSE;
            }
            $output['success'] = TRUE;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Cek Username
     * Parameter :
     * 1. username
     * Return JSON
     */
    function check_username($username="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        $output['isvalid'] = TRUE;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("username"=>$username));
            if($tempdata)
            {                
                $output['isvalid'] = FALSE;
            }
            $output['success'] = TRUE;
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get User Data
     * Parameter :
     * 1. id (parameter find _id field from database Users collection Account(optional))
     * 2. email (parameter find user can use email user(optional))
     * 3. username (parameter find user can use username user(optional))
     * Return JSON
     */
    function get_profile()
    {
        $_id = isset($_GET['id'])?$_GET['id']:"";
        $email = isset($_GET['email'])?$_GET['email']:"";
        $username = isset($_GET['username'])?$_GET['username']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            if($_id!="")
            {
                $filduser = array('_id'=> $this->mongo_db->mongoid($_id));
            }
            else if($email!="")
            {
                $filduser = array('email'=> $email);
            }
            else if($username!="")
            {
                $filduser = array('username'=> $username);
            }
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account"); 
            $tempdata = $this->mongo_db->findOne($filduser);
            if($tempdata)
            {
                $output['success'] = TRUE;
                $this->mongo_db->select_collection("Properties");
                $temp_properties = $this->mongo_db->findOne(array("lilo_id"=>(string)$tempdata["_id"]));
                $output['data'] = array(
                    'username' => $tempdata["username"],
                    'email' => $tempdata["email"],
                    '_id' => (string)$tempdata["_id"],
                    'avatarname' => $temp_properties["avatarname"],
                    'fullname' => $temp_properties["fullname"],
                    'sex' => $temp_properties["sex"],
                    'website' => $temp_properties["website"],
                    'link' => $temp_properties["link"],
                    'state_of_mind' => $temp_properties["state_of_mind"],
                    'bodytype'=> (isset($temp_properties["bodytype"])?$temp_properties["bodytype"]:"medium"),
                    'artist'=> (isset($temp_properties["artist"])?$temp_properties["artist"]:FALSE),
                    'handphone' => $temp_properties["handphone"],
                    'location' => $temp_properties["location"],
                    'picture' => $temp_properties["picture"],
                    'about' => $temp_properties["about"],
                    'birthday' => $temp_properties["birthday"],
                    'birthday_dd' => $temp_properties["birthday_dd"],
                    'birthday_mm' => $temp_properties["birthday_mm"],
                    'birthday_yy' => $temp_properties["birthday_yy"],
                );
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : POST
     * API Change User Email
     * Parameter :
     * 1. email
     * 2. id (parameter find _id field from database Users collection Account(optional))
     * 3. username (parameter find user can use username user(optional))
     * Return JSON
     */
    function change_email()
    {
        $newemail = isset($_POST['email'])?$_POST['email']:""; 
        $_id = isset($_POST['id'])?$_POST['id']:""; 
        $username = isset($_POST['username'])?$_POST['username']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("email"=>$newemail));
            if($tempdata)
            {                
                $output['message'] = "This email was used by another user.";
            }
            else
            {
                if($_id!="")
                {
                    $filduser = array('_id'=> $this->mongo_db->mongoid($_id));
                }
                else if($username!="")
                {
                    $filduser = array('username'=> $username);
                }
                $dataupdate = array("email"=>$newemail,"valid" => FALSE);
                $this->mongo_db->update($filduser,array('$set'=>$dataupdate));
                $output['success'] = TRUE;
                $output['message'] = "Success change email.";
                $url = current_url();
                $this->m_user->tulis_log("Change User Email",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : POST
     * API Change Username
     * Parameter :
     * 1. username
     * 2. id (parameter find _id field from database Users collection Account(optional))
     * 3. email (parameter find user can use email user(optional))
     * Return JSON
     */
    function change_username()
    {
        $newusername = isset($_POST['username'])?$_POST['username']:""; 
        $_id = isset($_POST['id'])?$_POST['id']:"";
        $email = isset($_POST['email'])?$_POST['email']:"";
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            if($_id!="")
            {
                $filduser = array('_id'=> $this->mongo_db->mongoid($_id));
            }
            else if($email!="")
            {
                $filduser = array('email'=> $email);
            }
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("username"=>$newusername));
            if($tempdata)
            {                
                $output['message'] = "This username was used by another user.";
            }
            else
            {
                $dataupdate = array("username"=>$newusername);
                $this->mongo_db->update($filduser,array('$set'=>$dataupdate));
                $output['success'] = TRUE;
                $output['message'] = "Success change username.";
                $url = current_url();
                $this->m_user->tulis_log("Change Username",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : POST
     * API Change Password
     * Parameter :
     * 1. password
     * 2. id (parameter find _id field from database Users collection Account(optional))
     * 3. email (parameter find user can use email user(optional))
     * 4. username (parameter find user can use username user(optional))
     * Return JSON
     */
    function change_password()
    {
        $newpassword = isset($_POST['password'])?$_POST['password']:""; 
        $_id = isset($_POST['id'])?$_POST['id']:"";
        $email = isset($_POST['email'])?$_POST['email']:"";
        $username = isset($_POST['username'])?$_POST['username']:"";
        $output['success'] = FALSE;
        $output['message'] = "Fail change password.";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account"); 
            $dataupdate = array("password"=> md5($newpassword) );
            if($_id!="")
            {
                $filduser = array('_id'=> $this->mongo_db->mongoid($_id));
            }
            else if($email!="")
            {
                $filduser = array('email'=> $email);
            }
            else if($username!="")
            {
                $filduser = array('username'=> $username);
            }
            $tempdata = $this->mongo_db->update($filduser,array('$set'=>$dataupdate));
            if($tempdata)
            {                
                $output['message'] = "Success change password.";
                $output['success'] = TRUE;
                $url = current_url();
                $this->m_user->tulis_log("Change User Password",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }    
    /*
     * Methode : POST
     * API Login User
     * Parameter :
     * 1. email (parameter find user can use email user(optional))
     * 2. username (parameter find user can use username user(optional))
     * 3. password
     * Return JSON
     */
    function login()
    {
        $useremail = isset($_POST['email'])?$_POST['email']:""; 
        $username = isset($_POST['username'])?$_POST['username']:""; 
        $password = isset($_POST['password'])?$_POST['password']:""; 
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("email"=>$useremail, "password"=> md5($password)));
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['id'] = (string)$tempdata["_id"];
            }
            else
            {
                $tempdata2 = $this->mongo_db->findOne(array("username"=>$username, "password"=> md5($password)));
                if($tempdata2)
                {
                    $output['success'] = TRUE;
                    $output['id'] = (string)$tempdata2["_id"];
                }
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Change User Profile
     * Parameter :
     * 1. id
     * 2. sex
     * 3. birthday
     * 4. avatarname
     * 5. fullname
     * 6. website
     * 7. link
     * 8. about
     * 9. location
     * 10. phone
     * 11. picture
     * 12. bodytipe
     * Return JSON
     */
    function change_profile()
    {
        $_id = isset($_GET['id'])?$_GET['id']:"";     
        $sex = isset($_GET['sex'])?$_GET['sex']:"male";   
        $birthday = isset($_GET['birthday'])?$_GET['birthday']:"";    
        $avatarname = isset($_GET['avatarname'])?$_GET['avatarname']:""; 
        $fullname = isset($_GET['fullname'])?$_GET['fullname']:""; 
        $website = isset($_GET['website'])?$_GET['website']:""; 
        $link = isset($_GET['link'])?$_GET['link']:""; 
        $about = isset($_GET['about'])?$_GET['about']:""; 
        $location = isset($_GET['location'])?$_GET['location']:"";
        $phone = isset($_GET['phone'])?$_GET['phone']:"";
        $picture = isset($_GET['picture'])?$_GET['picture']:"";
        $bodytype = isset($_GET['bodytipe'])?$_GET['bodytipe']:"";
        $state_of_mind = isset($_GET['state_of_mind'])?$_GET['state_of_mind']:"";
        $newpassword = isset($_GET['newpassword'])?$_GET['newpassword']:"";
        $star = isset($_GET['star'])?$_GET['star']:"";
        $diamond = isset($_GET['diamond'])?$_GET['diamond']:"";
        $output['success'] = FALSE;
        $output['message'] = "Fail change user profile.";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Properties");                        
            $pecahtgllahir =  explode("-", $birthday);
            $pecahtgllahir2 =  explode("/", $birthday);
            $dataproperties = array();
            if($star!="")
            {
                $dataproperties = array_merge($dataproperties, array('stars'  =>(double)$star));
            }
            if($diamond!="")
            {
                $dataproperties = array_merge($dataproperties, array('diamonds'  =>(double)$diamond));
            }
            if($state_of_mind!="")
            {
                $dataproperties = array_merge($dataproperties, array('state_of_mind'  =>$state_of_mind));
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social");
                $addnotification = array(
                    'type'=>'ChangeStateOfMind',
                    "user_id"=>(string)$_id,
                    "text" => $state_of_mind,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                );
                $this->mongo_db->insert($addnotification);
            }
            if($avatarname!="")
            {
                $dataproperties = array_merge($dataproperties, array('avatarname'  =>$avatarname));
            }
            if($fullname!="")
            {
                $dataproperties = array_merge($dataproperties, array('fullname'  =>$fullname));
            }
            if($sex!="")
            {
                $dataproperties = array_merge($dataproperties, array('sex'  =>$sex));
            }
            if($website!="")
            {
                $dataproperties = array_merge($dataproperties, array('website'  =>$website));
            }
            if($link!="")
            {
                $dataproperties = array_merge($dataproperties, array('link'  =>$link));
            }
            if($bodytype!="")
            {
                $dataproperties = array_merge($dataproperties, array('bodytype'  =>$bodytype));
            }
            if($about!="")
            {
                $dataproperties = array_merge($dataproperties, array('about'  =>$about));
            }
            if($phone!="")
            {
                $dataproperties = array_merge($dataproperties, array('handphone'  =>$phone));
            }
            if($location!="")
            {
                $dataproperties = array_merge($dataproperties, array('location'  =>$location));
            }
            if($picture!="")
            {
                $dataproperties = array_merge($dataproperties, array('picture'  =>$picture));
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social");
                $addnotification = array(
                    'type'=>'ChangePicture',
                    "user_id"=>(string)$_id,
                    "pic" => $picture,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                );
                $this->mongo_db->insert($addnotification);
            }
            if($birthday!="")
            {
                $dataproperties = array_merge($dataproperties, array(
                    'birthday'  =>$birthday,
                    'birthday_dd'=>isset($pecahtgllahir[2])?$pecahtgllahir[2]:$pecahtgllahir2[0],
                    'birthday_mm'=>isset($pecahtgllahir[1])?$pecahtgllahir[1]:$pecahtgllahir2[1],
                    'birthday_yy' =>isset($pecahtgllahir2[2])?$pecahtgllahir2[2]:$pecahtgllahir[0]
                    ));
            }
            $tempdata = $this->mongo_db->update(array("lilo_id"=>(string)$_id),array('$set'=>$dataproperties),array('upsert' => TRUE));
            if($newpassword!="")
            {
                $this->mongo_db->select_db("Users");
                $this->mongo_db->select_collection("Account"); 
                $dataupdate = array("password"=> md5($newpassword) );
                $tempdata = $this->mongo_db->update(array('_id'=> $this->mongo_db->mongoid($_id)),array('$set'=>$dataupdate));
            }
            if($tempdata)
            {
                $output['success'] = TRUE;
                $output['message'] = "Success change user properties.";
                $url = current_url();
                $this->m_user->tulis_log("Change User Profile",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Registration User
     * Parameter :
     * 1. email
     * 2. password
     * 3. username
     * 4. sex
     * 5. birthday
     * 6. twitterid
     * 7. facebookid
     * 8. avatarname
     * 9. fullname
     * 10. website
     * 11. link
     * 12. about
     * 13. location
     * 14. phone
     * 15. bodytipe
     * Return JSON
     */
    function register()
    {
        $useremail = isset($_GET['email'])?$_GET['email']:"";   
        $password = isset($_GET['password'])?$_GET['password']:"";   
        $username = isset($_GET['username'])?$_GET['username']:"";   
        $sex = isset($_GET['sex'])?$_GET['sex']:"male";   
        $birthday = isset($_GET['birthday'])?$_GET['birthday']:"";   
        $twitterid = isset($_GET['twitterid'])?$_GET['twitterid']:"";   
        $facebookid = isset($_GET['facebookid'])?$_GET['facebookid']:"";  
        $avatarname = isset($_GET['avatarname'])?$_GET['avatarname']:""; 
        $fullname = isset($_GET['fullname'])?$_GET['fullname']:""; 
        $website = isset($_GET['website'])?$_GET['website']:""; 
        $link = isset($_GET['link'])?$_GET['link']:""; 
        $about = isset($_GET['about'])?$_GET['about']:""; 
        $location = isset($_GET['location'])?$_GET['location']:"";
        $phone = isset($_GET['phone'])?$_GET['phone']:"";
        $picture = isset($_GET['picture'])?$_GET['picture']:"";
        $bodytype = isset($_GET['bodytype'])?$_GET['bodytype']:"";
        $output['success'] = FALSE;
        $output['message'] = "Fail generate new user.";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");        
            $tempdata = $this->mongo_db->findOne(array("email"=>$useremail));
            if(!$tempdata)
            {
                $output['success'] = TRUE;
                $key = $this->tambahan_fungsi->global_get_random(25);
                $id = $this->mongo_db->mongoid($key.date('YmdHis'));
                $activkey = date('Y-m-d H:i:s');
                $datainsert=array(
                    'email'  =>$useremail,
                    "valid" => FALSE,
                    "artist" => FALSE,
                    "point" => 10,
                    'password'  =>md5($password),
                    'username'=>$username,
                    'join_date'=>$this->mongo_db->time(strtotime(date("Y-m-d H:i:s"))),
                    'activation_key' =>md5($activkey), 
                    'token_key' =>md5($key), 
                    'fb_id'  =>$facebookid,
                    "twitter_id" =>$twitterid,
                    'brand_id'=>'',
                    'access'=>'',
                );
                $tempinseraccount = $this->mongo_db->insert($datainsert);
                $this->mongo_db->select_collection("Properties");
                $pecahtgllahir =  explode("-", $birthday);
                $pecahtgllahir2 =  explode("/", $birthday);
                $dataproperties=array(
                    'lilo_id'=>(string)$tempinseraccount,
                    'avatarname'  =>$avatarname,
                    'fullname' =>$fullname,
                    'sex'=>$sex,                        
                    'website'=>$website,
                    'link'=>$link,
                    'bodytype'=>$bodytype,
                    'birthday'  =>$birthday,
                    'birthday_dd'=>isset($pecahtgllahir[2])?$pecahtgllahir[2]:$pecahtgllahir2[0],
                    'birthday_mm'=>isset($pecahtgllahir[1])?$pecahtgllahir[1]:$pecahtgllahir2[1],
                    'birthday_yy' =>isset($pecahtgllahir2[2])?$pecahtgllahir2[2]:$pecahtgllahir[0],    
                    'about'  =>$about,
                    'state_of_mind' => '',
                    'picture'  =>$picture,
                    'location'=>$location,                        
                    'handphone'  =>$phone
                );                
                $this->mongo_db->insert($dataproperties);
                $this->mongo_db->select_db("Social");
                $this->mongo_db->select_collection("Social"); 
                $filtering = array(
                    'type'=>'Register',
                    "user_id"=>(string)$tempinseraccount,
                    'datetime' => $this->mongo_db->time(strtotime(date("Y-m-d H:i:s")))
                );
                $this->mongo_db->insert($filtering);
                $output['data'] = array(
                    'username' => $username,
                    'email' => $useremail,
                    '_id' => (string)$tempinseraccount,
                    'avatarname' => $avatarname,
                    'fullname' => $fullname,
                    'sex' => $sex,
                    'website' => $website,
                    'link' => $link,
                    'state_of_mind' => "",
                    'bodytype'=> $bodytype,
                    'artist'=> FALSE,
                    'handphone' => $phone,
                    'location' => $location,
                    'picture' => $picture,
                    'about' => $about,
                    'birthday' => $birthday,
                    'birthday_dd' => isset($pecahtgllahir[2])?$pecahtgllahir[2]:$pecahtgllahir2[0],
                    'birthday_mm' => isset($pecahtgllahir[1])?$pecahtgllahir[1]:$pecahtgllahir2[1],
                    'birthday_yy' => isset($pecahtgllahir2[2])?$pecahtgllahir2[2]:$pecahtgllahir[0],
                );
                $output['message'] = "Success generate new user.";
                $url = current_url();
                $this->m_user->tulis_log("New User Register",$url,"API Unity");
            }
            else
            {
                $output['message'] = "Duplicat Email User.";
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Set User Account
     * Parameter :
     * 1. user_id (parameter _id field from database Users collection Account(required))
     * 2. chkemail Set User hide Email value must[1=true,0=false] (this param is optional)
     * 3. chksex Set User hide Sex value must[1=true,0=false] (this param is optional)
     * 4. chkphonenumber Set User hide Phone Number value must[1=true,0=false] (this param is optional)
     * 5. optbirthdayshow Set User birthday value must[showall,hideyear,hideall] (this param is optional)
     * 6. chkeventupdate Set User Set Notification Event Update value must[1=true,0=false] (this param is optional)
     * 7. chkfrienreq Set User Set Notification Friend Request value must[1=true,0=false] (this param is optional)
     * 8. chkmention Set User Set Notification Friend Mentions value must[1=true,0=false] (this param is optional)
     * 9. chkpostfriend Set User Set Notification Friend Post data to wall value must[1=true,0=false] (this param is optional)
     * 10. chklove Set User Set Notification Friend love value must[1=true,0=false] (this param is optional)
     * 11. mixcollect Set User Set Notification your Mix Collect by Friend value must[1=true,0=false] (this param is optional)
     * 12. chkcomment Set User Set Notification Friend Comment value must[1=true,0=false] (this param is optional)
     * 13. chkavaitems Set User Set Notification New Avatar Items value must[1=true,0=false] (this param is optional)
     * 14. chkcontest Set User Set Notification Contest Result value must[1=true,0=false] (this param is optional)
     * 15. chkmessage Set User Set Notification Friend Send Message value must[1=true,0=false] (this param is optional)
     * Return JSON
     */
    function setting_account()
    {
        $user_id = isset($_GET['user_id'])?$_GET['user_id']:""; 
        $chkemail = isset($_GET['chkemail'])?$_GET['chkemail']:""; 
        $chksex = isset($_GET['chksex'])?$_GET['chksex']:""; 
        $chkphonenumber = isset($_GET['chkphonenumber'])?$_GET['chkphonenumber']:""; 
        $optbirthdayshow = isset($_GET['optbirthdayshow'])?$_GET['optbirthdayshow']:""; 
        $chkeventupdate = isset($_GET['chkeventupdate'])?$_GET['chkeventupdate']:""; 
        $chkfrienreq = isset($_GET['chkfrienreq'])?$_GET['chkfrienreq']:""; 
        $chkmention = isset($_GET['chkmention'])?$_GET['chkmention']:""; 
        $chkpostfriend = isset($_GET['chkpostfriend'])?$_GET['chkpostfriend']:""; 
        $chklove = isset($_GET['chklove'])?$_GET['chklove']:""; 
        $mixcollect = isset($_GET['mixcollect'])?$_GET['mixcollect']:""; 
        $chkcomment = isset($_GET['chkcomment'])?$_GET['chkcomment']:""; 
        $chkavaitems = isset($_GET['chkavaitems'])?$_GET['chkavaitems']:""; 
        $chkcontest = isset($_GET['chkcontest'])?$_GET['chkcontest']:""; 
        $chkmessage = isset($_GET['chkmessage'])?$_GET['chkmessage']:""; 
        $output['success'] = FALSE;
        $output['message'] = "fail setting data user";
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $account_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid((string)$user_id)));
            if($account_detail)
            {
                $output['success'] = TRUE;
                $this->mongo_db->select_collection("Properties");
                $dataupdate = array();
                if($chkemail != "")
                {
                    if($chkemail === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("hide_email"=>$dataset));
                }
                if($chksex != "")
                {
                    if($chksex === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("hide_sex"=>$dataset));
                }
                if($chkphonenumber != "")
                {
                    if($chkphonenumber === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("hide_phone"=>$dataset));
                }
                if($optbirthdayshow != "")
                {
                    $dataupdate = array_merge($dataupdate,array("show_birthday"=>$optbirthdayshow));
                }
                if($chkeventupdate != "")
                {
                    if($chkeventupdate === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_event"=>$dataset));
                }
                if($chkfrienreq != "")
                {
                    if($chkfrienreq === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_friend_request"=>$dataset));
                }
                if($chkmention != "")
                {
                    if($chkmention === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_mention"=>$dataset));
                }
                if($chkpostfriend != "")
                {
                    if($chkpostfriend === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_friend_post_data"=>$dataset));
                }
                if($chklove != "")
                {
                    if($chklove === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_friend_love_data"=>$dataset));
                }
                if($mixcollect != "")
                {
                    if($mixcollect === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_mix_collected"=>$dataset));
                }
                if($chkcomment != "")
                {
                    if($chkcomment === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_friend_comment"=>$dataset));
                }
                if($chkavaitems != "")
                {
                    if($chkavaitems === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_new_avataritem"=>$dataset));
                }
                if($chkcontest != "")
                {
                    if($chkcontest === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("notification_contest_result"=>$dataset));
                }
                if($chkmessage != "")
                {
                    if($chkmessage === "1")
                    {
                        $dataset = TRUE;
                    }
                    else
                    {
                        $dataset = FALSE;
                    }
                    $dataupdate = array_merge($dataupdate,array("friend_send_message"=>$dataset));
                }
                $output['message'] = "Setting success update";
                $this->mongo_db->update(array('lilo_id' => (string)$user_id),array('$set'=>$dataupdate));
                $url = current_url();
                $this->m_user->tulis_log("Update Setting User Account",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
    /*
     * Methode : GET
     * API Get User Deactivated
     * Parameter :
     * 1. user_id (parameter _id field from database Users collection Account(required))
     * Return JSON
     */
    function deactiveuser($user_id="")
    {
        $output['success'] = FALSE;
        $ceklogin = $this->cek_session->islogin();
        $output['logged_in'] = $ceklogin;
        if($ceklogin)
        {
            $this->mongo_db->select_db("Users");
            $this->mongo_db->select_collection("Account");
            $account_detail = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid((string)$user_id)));
            if($account_detail)
            {
                $output['success'] = TRUE;
                $this->mongo_db->remove(array('_id' => $this->mongo_db->mongoid((string)$user_id)));
                $this->mongo_db->select_collection("Properties");
                $properties_detail = $this->mongo_db->findOne(array('lilo_id' => (string)$user_id));
                $this->mongo_db->remove(array('lilo_id' => (string)$user_id));
                $this->mongo_db->select_collection("DeletedUsers");
                $datatinsert=array(                
                    'account'  =>$account_detail,
                    'properties'  =>$properties_detail,
                );
                $this->mongo_db->insert($datatinsert);
                $url = current_url();
                $this->m_user->tulis_log("Deactivated User Account",$url,"API Unity");
            }
        }
        echo json_encode($output);
    }
}