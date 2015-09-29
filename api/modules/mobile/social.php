<?php include_once('libraries/LiloMongo.php');
/*
 * Hidden API for Get Detail User
 * Methode: GET
 * Urutan Parameter
 * 1. User ID
 * Return : ARRAY
 */
function _detail_user($iduser="")
{
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Properties');
    $cekada = $lilo_mongo->findOne(array('lilo_id'=>(string)$iduser));
    $username="";
    $picture="";
    $sex="male";
    if($cekada)
    {
        $username=$cekada['fullname'];
        $picture=$cekada['picture'];
        $sex=$cekada['sex'];
    }
    return array(
        'userName' => $username,
        'picture' => $picture,
        'sex' => $sex,
        'userId' => $iduser,
    );
}
/*
 * Hidden API for Get _id User
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * Return : String
 */
function _get_id_user($emailuser="")
{
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Users');
    $lilo_mongo->selectCollection('Account');
    $cekada = $lilo_mongo->findOne(array('email'=>(string)$emailuser));
    $userid="";
    if($cekada)
    {
        $userid = (string)$cekada['_id'];
    }
    return $userid;
}
/*
 * API for Cek Item Avatar Like
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * 2. Avatar Item _id
 * Example : [server host]/[server path]/api/mobile/social/checklikeavatar/[user email]/[Avatar item _id]
 * Return : JSON
 */
function mobile_social_checklikeavataritem() 
{
    $user_email = func_arg(0);
    $items_id = func_arg(1);
    $user_id = _get_id_user($user_email);
    $lilo_mongo = new LiloMongo();    
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsLike');
    $data = $lilo_mongo->findOne(array("user_id"=>$user_id,"avatar_id"=>$items_id));
    $output['message']="Like This";
    $output['like']=FALSE;
    $output['count']=$lilo_mongo->count(array("avatar_id"=>$items_id));
    if($data) 
    {
        $output['message']="Unlike This";
        $output['like']=TRUE;  
    }
    return json_encode($output);
}
/*
 * API for Like Button Click Avatar Item
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Avatar _id
 * Example : [server host]/[server path]/api/mobile/social/likeavataritem/[User Email]/[Avatar Item _id]
 * Return : JSON
 */
function mobile_social_likeavataritem() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $items_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsLike');
    $parameter=array("user_id"=>$user_id,"avatar_id"=>$items_id);
    $data = $lilo_mongo->findOne($parameter);
    $output['message']="You Like This";
    $output['like']=TRUE;
    $output['count']=$lilo_mongo->count(array("avatar_id"=>$items_id));
    if($data) 
    {
        $output['message']="You Unlike This";
        $output['like']=FALSE;
        $lilo_mongo->remove($parameter);
        $output['count'] -=1;
    }
    else         
    {
        $lilo_mongo->insert($parameter);
        $output['count'] +=1;
    }
    return json_encode($output);
}
/*
 * API for Get List Like Avatar
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar _id
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listlikeavataritem/[avatar _id]/0/10/
 * Return : JSON
 */
function mobile_social_listlikeavataritem() 
{
    $items_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsLike');
    $temp_data = $lilo_mongo->find_pagging(array("avatar_id"=>$items_id), $start_from, $limit,array());
    $output['count']=$lilo_mongo->count(array("avatar_id"=>$items_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $temp_datauser = _detail_user($dt['user_id']);
            $output['data'][] = array(
                'userName' => $temp_datauser['userName'],
                'picture' => $temp_datauser['picture'],
                'sex' => $temp_datauser['sex'],
                'userId' => $temp_datauser['userId'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Comment Item Avatar Add
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Avatar _id
 * 3. comment
 * Example : [server host]/[server path]/api/mobile/social/addcommentavataritem/[user Email]/[avatar item _id]
 * Return : JSON
 */
function mobile_social_addcommentavataritem() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $items_id = func_arg(1);
    $comment = isset($_POST['comment'])?$_POST['comment']:"";
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsComments');
    $lilo_mongo->insert(array("user_id"=>$user_id,"avatar_id"=>$items_id,"comment"=>$comment,"datetime"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s")))));
    $output['count']=$lilo_mongo->count(array("avatar_id"=>$items_id));
    return json_encode($output);
}
/*
 * API for Delete Comment Avatar Item
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Avatar _id
 * 3. _id comment
 * Example : [server host]/[server path]/api/mobile/social/delcommentavataritem/[user email]/[Avatar item _id]/[_id comments]
 * Return : JSON
 */
function mobile_social_delcommentavataritem() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $avatar_id = func_arg(1);
    $_id = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsComments');
    $lilo_mongo->delete(array("user_id"=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['count']=$lilo_mongo->count(array("avatar_id"=>$avatar_id));
    return json_encode($output);
}
/*
 * API for Get List Comment Avatar Item
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar _id
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentavataritem/[avatar item _id]/0/10/
 * Return : JSON
 */
function mobile_social_listcommentavataritem() 
{
    $items_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsComments');
    $temp_data = $lilo_mongo->find_pagging(array("avatar_id"=>$items_id), $start_from, $limit,array("datetime"=>-1));
    $count = $lilo_mongo->count(array("avatar_id"=>$items_id));

    $comments = array();
    
    if($temp_data)
    {    
        foreach ($temp_data as $dt) 
        {
            $temp_datauser = _detail_user($dt['user_id']);
            $tglcreate="";
            if($dt['datetime']!="")
            {
                $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $comments[] = array(
                'id' => (string)$dt['_id'],
                'username' => $temp_datauser['userName'],
                'picture' => $temp_datauser['picture'],
                'sex' => $temp_datauser['sex'],
                'userid' => $dt['user_id'],
                'datetime' => $tglcreate,
                'comment' => $dt['comment'],
            );
        }
    }
    $output = array(
        'count' => $count,
        'data' => $comments
    );
    return json_encode($output);
}
/*
 * API for Get Count of Comment Avatar Item
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar _id
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/countcommentavataritem/[avatar item _id]/
 * Return : JSON
 */
function mobile_social_countcommentavataritem() 
{
    $items_id = func_arg(0);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarItemsComments');
    $output['count'] = $lilo_mongo->count(array("avatar_id"=>$items_id));
    return json_encode($output);
}
/*
 * API for Comment Avatar Configurations Add
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Configurations _id field
 * 3. comment
 * Example : [server host]/[server path]/api/mobile/social/addcommentavatarmix/[user email]/[avatar configurations _id]?comment=
 * Return : JSON
 */
function mobile_social_addcommentavatarmix() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $config_id = func_arg(1);
    $comment = isset($_REQUEST['comment'])?$_REQUEST['comment']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixComments');
    $lilo_mongo->insert(array("user_id"=>$user_id,"mix_id"=>$config_id,"comment"=>$comment,"datetime"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s")))));
    $output['count']=$lilo_mongo->count(array("mix_id"=>$config_id));
    return json_encode($output);
}
/*
 * API for Delete Comment Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Comment Configurations _id field
 * 3. _id comments
 * Example : [server host]/[server path]/api/mobile/social/delcommentavatarmix/[user id]/[Comment Configurations _id]/[_id Comments]
 * Return : JSON
 */
function mobile_social_delcommentavatarmix() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $config_id = func_arg(1);
    $_id = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixComments');
    $lilo_mongo->delete(array("user_id"=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['count']=$lilo_mongo->count(array("mix_id"=>$config_id));
    return json_encode($output);
}
/*
 * API for Get List Comment Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar Configurations _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentavatarmix/[avatar configurations _id]/0/10/
 * Return : JSON
 */
function mobile_social_listcommentavatarmix() 
{
    $config_id = func_arg(0);    
    $start_from = func_arg(1);
    $limit = func_arg(2);
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixComments');
    $temp_data = $lilo_mongo->find_pagging(array("mix_id"=>$config_id), $start_from, $limit,array("datetime"=>-1));
    $count = $lilo_mongo->count(array("mix_id"=>$config_id));
    $data = array();
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $temp_datauser = _detail_user($dt['user_id']);
            $tglcreate="";
            if($dt['datetime']!="")
            {
                $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $data[] = array(
                'id' => (string)$dt['_id'],
                'username' => $temp_datauser['userName'],
                'picture' => $temp_datauser['picture'],
                'sex' => $temp_datauser['sex'],
                'userid' => $dt['user_id'],
                'datetime' => $tglcreate,
                'comment' => $dt['comment'],
            );
        }
    }
    $output = array(
        'count' => $count,
        'data' => $data
    );
    return json_encode($output);
}
/*
 * API for Get List Comment Avatar Configurations
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar Configurations _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentavatarmix/[avatar configurations _id]/
 * Return : JSON
 */
function mobile_social_countcommentavatarmix() 
{
    $config_id = func_arg(0);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixComments');
    $count = $lilo_mongo->count(array("mix_id"=>$config_id));
    $output = array(
        'count' => $count
    );
    return json_encode($output);
}
/*
 * API for Cek Avatar Collections Like
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Configurations _id field
 * Example : [server host]/[server path]/api/mobile/social/checklikeavatarmix/[user email]/[avatar Collections _id]
 * Return : JSON
 */
function mobile_social_checklikeavatarmix() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $config_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixLike');
    $data = $lilo_mongo->findOne(array("user_id"=>$user_id,"mix_id"=>$config_id));
    $output['message']="Like This";
    $output['like']=FALSE;
    $output['count']=$lilo_mongo->count(array("mix_id"=>$config_id));;
    if($data) 
    {
        $output['message']="Unlike This";
        $output['like']=TRUE;
    }
    return json_encode($output);
}
/*
 * API for Like Button Click Avatar Collections
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Collections _id field
 * Example : [server host]/[server path]/api/mobile/social/likeavatarmix/[user email]/[Collections _id]
 * Return : JSON
 */
function mobile_social_likeavatarmix() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $config_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixLike');
    $parameter=array("user_id"=>$user_id,"mix_id"=>$config_id);
    $data = $lilo_mongo->findOne($parameter);
    $output['message']="You Like This";
    $output['like']=TRUE;
    $output['count']=$lilo_mongo->count(array("mix_id"=>$config_id));
    if($data) 
    {
        $output['message']="You Unlike This";
        $output['like']=FALSE;
        $lilo_mongo->remove($parameter);
        $output['count'] -=1;
    }
    else         
    {
        $lilo_mongo->insert($parameter);
        $output['count'] +=1;
    }
    return json_encode($output);
}
/*
 * API for Get List Like Avatar Collections
 * Methode: GET
 * Urutan Parameter
 * 1. Avatar Collections _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listlikeavatarmix/[avatar collections _id]/0/10/
 * Return : JSON
 */
function mobile_social_listlikeavatarmix() 
{
    $config_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('AvatarMixLike');
    $temp_data = $lilo_mongo->find_pagging(array("mix_id"=>$config_id), $start_from, $limit,array());
    $output['count']=$lilo_mongo->count(array("mix_id"=>$config_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $temp_datauser = _detail_user($dt['user_id']);
            $output['data'][] = array(
                'userName' => $temp_datauser['userName'],
                'picture' => $temp_datauser['picture'],
                'sex' => $temp_datauser['sex'],
                'userId' => $dt['user_id'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Cek Banner Like
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Banner _id field
 * Example : [server host]/[server path]/api/mobile/social/checklikebanner/[user email]/[Banner _id]
 * Return : JSON
 */
function mobile_social_checklikebanner() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $banner_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerLike');
    $data = $lilo_mongo->findOne(array("user_id"=>$user_id,"banner_id"=>$banner_id));
    $output['message']="Like This";
    $output['like']=FALSE;
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));;
    if($data) 
    {
        $output['message']="Unlike This";
        $output['like']=TRUE;
    }
    return json_encode($output);
}
/*
 * API for Like Button Click Banner
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Banner _id field
 * Example : [server host]/[server path]/api/mobile/social/likebannerbutton/[user email]/[Banner _id]
 * Return : JSON
 */
function mobile_social_likebannerbutton() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $banner_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerLike');
    $parameter=array("user_id"=>$user_id,"banner_id"=>$banner_id);
    $data = $lilo_mongo->findOne($parameter);
    $output['message']="You Like This";
    $output['like']=TRUE;
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));
    if($data) 
    {
        $output['message']="You Unlike This";
        $output['like']=FALSE;
        $lilo_mongo->remove($parameter);
        $output['count'] -=1;
    }
    else         
    {
        $lilo_mongo->insert($parameter);
        $output['count'] +=1;
    }
    return json_encode($output);
}
/*
 * API for Get List Like Banner
 * Methode: GET
 * Urutan Parameter
 * 1. Banner _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listlikebanner/[banner _id]/0/10/
 * Return : JSON
 */
function mobile_social_listlikebanner() 
{
    $banner_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerLike');
    $temp_data = $lilo_mongo->find_pagging(array("banner_id"=>$banner_id), $start_from, $limit,array());
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $output['data'][] = array(
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $datatemp_user['userId'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Comment Banner Add
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Banner _id field
 * 3. comment
 * Example : [server host]/[server path]/api/mobile/social/addcommentbanner/[user Email]/[banner _id]?comment=
 * Return : JSON
 */
function mobile_social_addcommentbanner() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $banner_id = func_arg(1);
    $comment = isset($_GET['comment'])?$_GET['comment']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerComments');
    $data = $lilo_mongo->insert(array("user_id"=>$user_id,"banner_id"=>$banner_id,"comment"=>$comment,"datetime"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s")))));
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));
    return json_encode($output);
}
/*
 * API for Delete Comment Banner
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Banner _id field
 * 3. _id Comments
 * Example : [server host]/[server path]/api/mobile/social/delcommentbanner/[user email]/[Banner _id]/[_id Comments]
 * Return : JSON
 */
function mobile_social_delcommentbanner() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $banner_id = func_arg(1);
    $_id = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerComments');
    $data = $lilo_mongo->delete(array("user_id"=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));
    return json_encode($output);
}
/*
 * API for Get List Comment Banner
 * Methode: GET
 * Urutan Parameter
 * 1. Banner _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentbanner/[banner _id]/0/10/
 * Return : JSON
 */
function mobile_social_listcommentbanner() 
{
    $banner_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BannerComments');
    $temp_data = $lilo_mongo->find_pagging(array("banner_id"=>$banner_id), $start_from, $limit,array("datetime"=>-1));
    $output['count']=$lilo_mongo->count(array("banner_id"=>$banner_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $tglcreate="";
            if($dt['datetime']!="")
            {
                $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $output['data'][] = array(
                '_id' => (string)$dt['_id'],
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $dt['user_id'],
                'datetime' => $tglcreate,
                'comment' => $dt['comment'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Cek Brand Like
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Brand _id field
 * Example : [server host]/[server path]/api/mobile/social/checklikebrand/[user email]/[Brand _id]
 * Return : JSON
 */
function mobile_social_checklikebrand() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $brand_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandLike');
    $data = $lilo_mongo->findOne(array("user_id"=>$user_id,"brand_id"=>$brand_id));
    $output['message']="Like This";
    $output['like']=FALSE;
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));;
    if($data) 
    {
        $output['message']="Unlike This";
        $output['like']=TRUE;
    }
    return json_encode($output);
}
/*
 * API for Like Button Click Brand
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Brand _id field
 * Example : [server host]/[server path]/api/mobile/social/likebrandbutton/[user email]/[Brand _id]
 * Return : JSON
 */
function mobile_social_likebrandbutton() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $brand_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandLike');
    $parameter=array("user_id"=>$user_id,"brand_id"=>$brand_id);
    $data = $lilo_mongo->findOne($parameter);
    $output['message']="You Like This";
    $output['like']=TRUE;
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));
    if($data) 
    {
        $output['message']="You Unlike This";
        $output['like']=FALSE;
        $lilo_mongo->remove($parameter);
        $output['count'] -=1;
    }
    else         
    {
        $lilo_mongo->insert($parameter);
        $output['count'] +=1;
    }
    return json_encode($output);
}
/*
 * API for Get List Like Brand
 * Methode: GET
 * Urutan Parameter
 * 1. Brand _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listlikebrand/[Brand _id]/0/10/
 * Return : JSON
 */
function mobile_social_listlikebrand() 
{
    $brand_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandLike');
    $temp_data = $lilo_mongo->find_pagging(array("brand_id"=>$brand_id), $start_from, $limit,array());
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $output['data'][] = array(
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $datatemp_user['userId'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Comment Brand Add
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. Brand _id field
 * 3. comment
 * Example : [server host]/[server path]/api/mobile/social/addcommentbrand/[user email]/[Brand _id]?comment=
 * Return : JSON
 */
function mobile_social_addcommentbrand() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $brand_id = func_arg(1);
    $comment = isset($_GET['comment'])?$_GET['comment']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandComments');
    $data = $lilo_mongo->insert(array("user_id"=>$user_id,"brand_id"=>$brand_id,"comment"=>$comment,"datetime"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s")))));
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));
    return json_encode($output);
}
/*
 * API for Delete Comment Brand
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * 2. Brand _id field
 * 3. _id Comments
 * Example : [server host]/[server path]/api/mobile/social/delcommentbrand/[user email]/[Brand _id]/[_id Comments]
 * Return : JSON
 */
function mobile_social_delcommentbrand() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $brand_id = func_arg(1);
    $_id = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandComments');
    $data = $lilo_mongo->delete(array("user_id"=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));
    return json_encode($output);
}
/*
 * API for Get List Comment Brand
 * Methode: GET
 * Urutan Parameter
 * 1. Brand _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentbrand/[Brand _id]/0/10/
 * Return : JSON
 */
function mobile_social_listcommentbrand() 
{
    $brand_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('BrandComments');
    $temp_data = $lilo_mongo->find_pagging(array("brand_id"=>$brand_id), $start_from, $limit,array("datetime"=>-1));
    $output['count']=$lilo_mongo->count(array("brand_id"=>$brand_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $tglcreate="";
            if($dt['datetime']!="")
            {
                $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $output['data'][] = array(
                '_id' => (string)$dt['_id'],
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $dt['user_id'],
                'datetime' => $tglcreate,
                'comment' => $dt['comment'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Cek News Stream Like
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. News _id field
 * Example : [server host]/[server path]/api/mobile/social/checklikenews/[user email]/[News _id]
 * Return : JSON
 */
function mobile_social_checklikenews() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $news_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsLike');
    $data = $lilo_mongo->findOne(array("user_id"=>$user_id,"news_id"=>$news_id));
    $output['message']="Like This";
    $output['like']=FALSE;
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));;
    if($data) 
    {
        $output['message']="Unlike This";
        $output['like']=TRUE;
    }
    return json_encode($output);
}
/*
 * API for Like Button Click News
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. News _id field
 * Example : [server host]/[server path]/api/mobile/social/likenewsbutton/[user email]/[News _id]
 * Return : JSON
 */
function mobile_social_likenewsbutton() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $news_id = func_arg(1);
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsLike');
    $parameter=array("user_id"=>$user_id,"news_id"=>$news_id);
    $data = $lilo_mongo->findOne($parameter);
    $output['message']="You Like This";
    $output['like']=TRUE;
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));
    if($data) 
    {
        $output['message']="You Unlike This";
        $output['like']=FALSE;
        $lilo_mongo->remove($parameter);
        $output['count'] -=1;
    }
    else         
    {
        $lilo_mongo->insert($parameter);
        $output['count'] +=1;
    }
    return json_encode($output);
}
/*
 * API for Get List Like News
 * Methode: GET
 * Urutan Parameter
 * 1. News _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listlikenews/[News _id]/0/10/
 * Return : JSON
 */
function mobile_social_listlikenews() 
{
    $news_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsLike');
    $temp_data = $lilo_mongo->find_pagging(array("news_id"=>$news_id), $start_from, $limit,array());
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $output['data'][] = array(
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $datatemp_user['userId'],
            );
        }
    }
    return json_encode($output);
}
/*
 * API for Comment News Add
 * Methode: GET
 * Urutan Parameter
 * 1. User Email
 * 2. News _id field
 * 3. comment
 * Example : [server host]/[server path]/api/mobile/social/addcommentnews/[user email]/[News _id]?comment=
 * Return : JSON
 */
function mobile_social_addcommentnews() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $news_id = func_arg(1);
    $comment = isset($_GET['comment'])?$_GET['comment']:"";
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsComments');
    $data = $lilo_mongo->insert(array("user_id"=>$user_id,"news_id"=>$news_id,"comment"=>$comment,"datetime"=>$lilo_mongo->time(strtotime(date("Y-m-d H:i:s")))));
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));
    return json_encode($output);
}
/*
 * API for Delete Comment News
 * Methode: GET
 * Urutan Parameter
 * 1. User email
 * 2. News _id field
 * 3. _id Comments
 * Example : [server host]/[server path]/api/mobile/social/delcommentnews/[user email]/[News _id]/[_id Comments]
 * Return : JSON
 */
function mobile_social_delcommentnews() 
{
    $user_email = func_arg(0);
    $user_id = _get_id_user($user_email);
    $news_id = func_arg(1);
    $_id = func_arg(2);

    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsComments');
    $data = $lilo_mongo->delete(array("user_id"=>$user_id,"_id"=>$lilo_mongo->mongoid($_id)));
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));
    return json_encode($output);
}
/*
 * API for Get List Comment News
 * Methode: GET
 * Urutan Parameter
 * 1. News _id field
 * 2. Start Page from
 * 3. Limit Data
 * Example : [server host]/[server path]/api/mobile/social/listcommentnews/[News _id]/0/10/
 * Return : JSON
 */
function mobile_social_listcommentnews() 
{
    $news_id = func_arg(0);
    $start_from = func_arg(1);
    $limit = func_arg(2);    
    
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Social');
    $lilo_mongo->selectCollection('NewsComments');
    $temp_data = $lilo_mongo->find_pagging(array("news_id"=>$news_id), $start_from, $limit,array("datetime"=>-1));
    $output['count']=$lilo_mongo->count(array("news_id"=>$news_id));
    if($temp_data)
    {
        foreach ($temp_data as $dt) 
        {
            $datatemp_user=_detail_user($dt['user_id']);
            $tglcreate="";
            if($dt['datetime']!="")
            {
                $tglcreate= date('Y-m-d H:i:s', $dt['datetime']->sec);
            }
            $output['data'][] = array(
                '_id' => (string)$dt['_id'],
                'userName' => $datatemp_user['userName'],
                'picture' => $datatemp_user['picture'],
                'sex' => $datatemp_user['sex'],
                'userId' => $dt['user_id'],
                'datetime' => $tglcreate,
                'comment' => $dt['comment'],
            );
        }
    }
    return json_encode($output);
}
?>