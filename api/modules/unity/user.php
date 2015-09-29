<?php
include_once('libraries/LiloMongo.php');
/*
 * Hidden API for User Write Session
 */
function write_session($_id="",$_activation="",$_token="")
{
    $_SESSION['a_id']=(string)$_id;
    $_SESSION['a_activation']=$_activation;
    $_SESSION['a_token']=$_token;
}
/*
 * API for New User
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. Password
 * 3. Username
 * 4. Gender is Options : [male] or [female]
 * 5. State Mind
 * 6. Tanggal Lahir format(yyyy-mm-dd)
 * 7. FB ID user
 * Example : [server host]/[server path]/api/unity/user/newuser/[email]/[password]/[username]/[gender]/[statemind]/[birtday]
 * Return : JSON
 */
function unity_user_newuser()
{
    
    $email = func_arg(0);  
    $password = urldecode(func_arg(1)); 
    $username = urldecode(func_arg(2)); 
    $gender = (func_arg(3)!="")?func_arg(3):"male"; 
    $statemind = urldecode(func_arg(4)); 
    $tgl_lahir= (func_arg(5)!="") ? urldecode(func_arg(5)) : date('Y-m-d');
    $fb_id= (func_arg(6)!="")?func_arg(6):"";

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $cekada = $lilo_mongo->findOne(array("email" => $email));
    
    $retValid = FALSE;
    $retMessage = "Duplicate email";
        
    if(!$cekada)
    {

        $key=get_random(25);
        $id=$key;
        $activkey=date('Y-m-d H:i:s');
        
        $datatinsert=array(
            'email'  =>$email,
            'password'  =>md5($password),
            'username'=>$email,
            'join_date'=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s"))),
            'activation_key' =>md5($activkey), 
            'token_key' =>md5($key), 
            'fb_id'  =>$fb_id,
//            '_id'=>$id,
            'access'=>''
         );
        
        $lilo_mongo->insert($datatinsert);
        // Sync between _id and lilo_id, this is a pain in the ass, why do need lilo_id in the first fucking place????????????????//
        $findQuery = $lilo_mongo->findOne(array("email" => $email));
        if ($findQuery) {
            $lilo_id = $findQuery["_id"];
            $lilo_mongo->update_set(array("email" => $email), array("lilo_id" => (string)$lilo_id));
            
            $lilo_mongo->selectCollection('Properties');
            $pecahtgllahir = explode("-", $tgl_lahir);
            $datatinsert=array( 
                'lilo_id'=>(string)$lilo_id,
                'avatarname'  =>$username,
                'fullname' =>$username,
                'sex'=>$gender,                        
                'website'=>'',
                'link'=>'',
                'birthday'  =>$tgl_lahir,
                'birthday_dd'=>$pecahtgllahir[2],
                'birthday_mm'=>$pecahtgllahir[1],
                'birthday_yy' =>$pecahtgllahir[0],    
                'state_mind'  =>$statemind,
                'about'  =>'',
                'picture'  =>'',
                'location'=>'',                        
                'handphone'  =>'',
                'twitter'=>'',
            );
            $lilo_mongo->insert($datatinsert);
            $lilo_mongo->selectCollection('UserProfile');
            $generatetime=date("Y-m-d H:i:s");
            $time_start=  strtotime($generatetime);
            $datatinsert=array(
                'lilo_id'=>(string)$lilo_id,
                'StateMind'  =>$statemind,
                'date'=>$lilo_mongo->time($time_start),
            );
            $lilo_mongo->insert($datatinsert);
            $retValid = TRUE;
            $retMessage = "Succesfully created user: " . $email;
            write_session($id,md5($activkey),md5($key));
        }
       
    }
    else
    {
        write_session($cekada['_id'],$cekada['activation_key'],$cekada['token_key']);
    }
    
    
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    
    return json_encode($ret);
}
/*
 * API for Set Data Properties User
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. Avatar Name
 * 3. Full User Name
 * 4. Gender is Options : [male] or [female]
 * 5. State Mind
 * 6. Tanggal Lahir format(yyyy-mm-dd)
 * 7. About User
 * 8. Location User
 * 9. Handphone number user
 * 10. Twiter User Account
 * 11. website
 * 12. link
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/properties/[email]/[avatarname]/[fullname]/[gender]/[statemind]/[birtday]/[about]/[location]/[hanphone]/[twitter]?website=&link=
 * Return : JSON
 */
function unity_user_properties()
{
    $email = func_arg(0);  
    $avatarname = func_arg(1);
    $fullname = func_arg(2);
    $sex = func_arg(3);    
    $state_mind = func_arg(4);
    $birthday = func_arg(5);    
    $about = func_arg(6);
    $location = func_arg(7);
    $handphone = func_arg(8);
    $twitter = func_arg(9);
    $website = isset($_GET['website'])?$_GET['website']:"";
    $link = isset($_GET['link'])?$_GET['link']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $retValid = FALSE;
    $retMessage = "Gagal mengubah properties user";
    
    $cekada = $lilo_mongo->findOne(array("email" => $email,'_id'=>$lilo_mongo->mongoid((string)$_SESSION['a_id'])));
    if($cekada)
    {
        $lilo_mongo->selectCollection('Properties');
        $datatinsert=array();
        $filter=array('lilo_id'=>(string)$cekada['_id']);
        if($avatarname!="" )
        {
            $datatinsert=  array_merge($datatinsert,array('avatarname'  =>$avatarname));
        }
        if($fullname!="")
        {
            $datatinsert=  array_merge($datatinsert,array('fullname' =>$fullname));
        } 
        if($sex!="")
        {
            $datatinsert=  array_merge($datatinsert,array('sex' =>$sex));
        }
        if($website!="")
        {
            $datatinsert=  array_merge($datatinsert,array('website' =>$website));
        }
        if($link!="")
        {
            $datatinsert=  array_merge($datatinsert,array('link' =>$link));
        }
        if($birthday!="")
        {
            $pecahtgllahir=  explode("-", $tgl_lahir);
            $datatinsert=  array_merge($datatinsert,array('birthday' =>$birthday,'birthday_dd' =>$pecahtgllahir[2],'birthday_mm' =>$pecahtgllahir[1],'birthday_yy' =>$pecahtgllahir[0]));
        }
        if($state_mind!="")
        {
            $datatinsert=  array_merge($datatinsert,array('state_mind' =>$state_mind));
        }
        if($about!="")
        {
            $datatinsert=  array_merge($datatinsert,array('about' =>$about));
        }
        if($location!="")
        {
            $datatinsert=  array_merge($datatinsert,array('location' =>$location));
        }
        if($handphone!="")
        {
            $datatinsert=  array_merge($datatinsert,array('handphone' =>$handphone));
        }
        if($twitter!="")
        {
            $datatinsert=  array_merge($datatinsert,array('twitter' =>$twitter));
        }           
        $lilo_mongo->update_set($filter,$datatinsert);
       
        $retValid = TRUE;
        $retMessage = "Properties user berhasil ter-ubah!";
    }
    else
    {
        $retMessage = "Session login telah habis!";
    }   
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    return json_encode($ret);
}
/*
 * API for Set Data Status Baru User
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. Status text
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/status/userid/[status text]
 * Return : JSON
 */
function unity_user_setstatus()
{
    $userid = func_arg(0);  
    $status = func_arg(1);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');

    $lilo_mongo->selectCollection('Properties');
    $lilo_mongo->update_set(array('lilo_id' => $userid), array('state_of_mind' => $status));
    
    $ret = array(
        'status' => $status
    );
    
    return json_encode($ret);
}
/*
 * API for Cek Login user
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. password
 * Example : [server host]/[server path]/api/unity/user/login/[email]/[password]
 * Return : JSON
 */
function unity_user_login()
{
    $email = func_arg(0);  
    $password = func_arg(1);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $retValid = FALSE;
    $retMessage = "Gagal login!";
    
    $cekada = $lilo_mongo->findOne(array("email" => $email,'password'=>md5($password)));
    if($cekada)
    {
        write_session($cekada['_id'],$cekada['activation_key'],$cekada['token_key']);
        $retValid = TRUE;
        $retMessage = "Login success!";
    }
    else
    {
        $cekada2 = $lilo_mongo->findOne(array("username" => $email,'password'=>md5($password)));
        if($cekada2)
        {
            write_session($cekada2['_id'],$cekada2['activation_key'],$cekada2['token_key']);//            
            $retValid = TRUE;
            $retMessage = "Login success!";
        }
    }
    
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    
    return json_encode($ret);
}
/*
 * API for Change Password User
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. Old password
 * 3. New Password
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/cpassword/[email]/[oldpassword]/[newpassword]
 * Return : JSON
 */
function unity_user_cpassword()
{
    $email = func_arg(0);  
    $old_password = func_arg(1);
    $new_password = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $retValid = FALSE;
    $retMessage = "Gagal mengubah password!";
    
    $cekada = $lilo_mongo->findOne(array("email" => $email, "password"=>md5($old_password),'_id'=>$lilo_mongo->mongoid((string)$_SESSION['a_id'])));
    if($cekada)
    {
        $datatinsert=array(
            'password'  =>md5($new_password),
        );
        $lilo_mongo->update_set(array('_id'=>$lilo_mongo->mongoid((string)$_SESSION['a_id'])),$datatinsert);
        $retValid = TRUE;
        $retMessage = "Password berhasil di ubah!";
    }
    else
    {
        $retMessage = "Session login telah habis!";
    }        
    
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    return json_encode($ret);
}
/*
 * API for cek user is register
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * Example : [server host]/[server path]/api/unity/user/check/[email]
 * Return : JSON
 */
function unity_user_check()
{
    $email = func_arg(0);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $cekada = $lilo_mongo->findOne(array("email" => $email));
    
    $retValid = FALSE;
    $retMessage = "User tidak teregister";
    
    if($cekada)
    {
        $retValid = TRUE;
        $retMessage = "User teregister";
    }
    
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    
    return json_encode($ret);
}
/*
 * API for Change User ID
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * 2. User ID
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/userid/[email]/[newuserid]
 * Return : JSON
 */
function unity_user_userid()
{
    $email = func_arg(0);  
    $userid = func_arg(1);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $retValid = FALSE;
    $retMessage = "Gagal mengubah userid!";
    
    $cekada = $lilo_mongo->findOne(array("email" => $email, '_id'=>$lilo_mongo->mongoid((string)$_SESSION['a_id'])));
    if($cekada)
    {
        $cekada2 = $lilo_mongo->findOne(array("username" => $userid));
        if(!$cekada)
        {
            $datatinsert=array(
                'username'  =>$userid,
            );
            $lilo_mongo->update_set(array('_id'=>$lilo_mongo->mongoid($cekada['_id'])),$datatinsert);
            $retValid = TRUE;
            $retMessage = "UserId berhasil di ubah!";
        }
        else
        {
            $retMessage = "UserId telah digunakan, user id gagal di ubah!";
        }
    }
    else
    {
        $retMessage = "Session login telah habis!";
    }        
    
    $ret = array(
        'valid' => $retValid,
        'message' => $retMessage
    );
    return json_encode($ret);
}
/*
 * API for Get User Properties
 * Methode: GET
 * Urutan Parameter
 * 1. Email
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/profile/[email]
 * Return : JSON
 */
function unity_user_profile()
{
    $email = func_arg(0);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    
    $cekada = $lilo_mongo->findOne(array("email" => $email));
    
    if (!$cekada) {
        $cekada = $lilo_mongo->findOne(array("username" => $email));
    }

    $retData = array(
        'valid' => false,
        'email'  =>"",
        'username' =>"",            
        'avatarname' => "",
        'fullname' =>"",
        'sex'=>"",                       
        'website'=>"",
        'link'=>"",
        'birthday'  =>"",
        'birthday_dd'=>"",
        'birthday_mm'=>"",
        'birthday_yy' =>"",    
        'state_of_mind'  =>"",
        'about'  =>"",
        'picture'  =>"",
        'location'=>"",                        
        'handphone'  =>"",
        'twitter'=>"",
        'status'=>"",
    );
    
    if($cekada)
    {
        $lilo_mongo->selectCollection('UserProfile');
        $datastatus = $lilo_mongo->find(array('lilo_id'=>(string)$cekada['_id']),1000,array('date'=>-1));
        $lilo_id = $cekada["_id"];
        $tempstatus=array();
        if($datastatus)
        {
            foreach ($datastatus as $dt) 
            {
                $tempstatus[] = $dt['StateMind'];
            }
        }
        $lilo_mongo->selectCollection('Properties');
        $datadetail = $lilo_mongo->findOne(array("lilo_id" => (string)$cekada['_id']));        
        $retData=array(
            'valid' => TRUE,
            'lilo_id' => isset($lilo_id) ? (string)$lilo_id : "",
            'email'  =>isset($cekada['email'])?$cekada['email']:"",
            'username'=>isset($cekada['username'])?$cekada['username']:"",            
            'avatarname'  =>isset($datadetail['avatarname'])?$datadetail['avatarname']:'',
            'fullname' =>isset($datadetail['fullname'])?$datadetail['fullname']:'',
            'sex'=>isset($datadetail['sex'])?$datadetail['sex']:'',                       
            'website'=>isset($datadetail['website'])?$datadetail['website']:'',
            'link'=>isset($datadetail['link'])?$datadetail['link']:'',
            'birthday'  =>isset($datadetail['birthday'])?$datadetail['birthday']:'',
            'birthday_dd'=>isset($datadetail['birthday_dd'])?$datadetail['birthday_dd']:'',
            'birthday_mm'=>isset($datadetail['birthday_mm'])?$datadetail['birthday_mm']:'',
            'birthday_yy' =>isset($datadetail['birthday_yy'])?$datadetail['birthday_yy']:'',    
            'state_of_mind'  =>isset($datadetail['state_of_mind'])?$datadetail['state_of_mind']:'',
            'about'  =>isset($datadetail['about'])?$datadetail['about']:'',
            'picture'  =>isset($datadetail['picture'])?$datadetail['picture']:'',
            'location'=>isset($datadetail['location'])?$datadetail['location']:'',                        
            'handphone'  =>isset($datadetail['handphone'])?$datadetail['handphone']:'',
            'twitter'=>isset($datadetail['twitter'])?$datadetail['twitter']:'',
            'status'=>$tempstatus,
         );
    }
    return json_encode($retData);
}
/*
 * API for Get User Status
 * Methode: GET
 * Urutan Parameter
 * 1. userid
 * Example : [server host]/[server path]/api/unity/user/status/4f44ddc309bf10d80d000001
 * Return : JSON
 */
function unity_user_status()
{
    $user_id = func_arg(0);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Properties');
        
    $properties = $lilo_mongo->findOne(array('lilo_id' => $user_id));

    if ($properties)
    {
        $status = $properties['state_of_mind'];
        if ($status == NULL) {
            $status = "";
        }
    }       
    
    $retData = array(
        'status' => $status
    );
    
    return json_encode($retData);
}
/*
 * API for Delete User Status
 * Methode: GET
 * Urutan Parameter
 * 1. status_id
 * Note : 
 * session user harus telah teregister di server terlebih dahulu, jika habis request session baru.
 * Example : [server host]/[server path]/api/unity/user/delstatus/[status id]
 * Return : JSON
 */
function unity_user_delstatus()
{
    $status_id = func_arg(0);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('UserProfile');    
    $cekada = $lilo_mongo->delete(array('_id'=>$lilo_mongo->mongoid($status_id),'lilo_id'=>(string)$_SESSION['a_id']));
    $retData = array(
        'success' => false,
        'message'=>"Status gagal dihapus",
    );    
    if($cekada)
    {    
        $retData=array(
            'valid' => TRUE,
            'status'=>"Status berhasil dihapus",
         );
    }
    return json_encode($retData);
}
?>