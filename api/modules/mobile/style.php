<?php 
include_once('libraries/LiloMongo.php');
include_once('baselibs.php');

function __style_root() {
    $output = array(
        'count' => 5
    );
    
    $output['data'][] = array(
        'tipe' => 'top',
        'title' => 'Top',
        'picture' => 'style_top.png',
        'action' => '/ItemStyle?f=body',
        'value' => ''
    );
    
    $output['data'][] = array(
        'tipe' => 'bottom',
        'title' => 'Bottom',
        'picture' => 'style_bottom.png',
        'action' => '/ItemStyle?f=pants',
        'value' => ''
    );
    
    $output['data'][] = array(
        'tipe' => 'footwear',
        'title' => 'Footwear',
        'picture' => 'style_footwear.png',
        'action' => '/ItemStyle?f=shoes',
        'value' => ''
    );
    
    $output['data'][] = array(
        'tipe' => 'accessories',
        'title' => 'Accessories',
        'picture' => 'style_accessories.png',
        'action' => '/SubStyle?f=accessories',
        'value' => ''
    );
    
    $output['data'][] = array(
        'tipe' => 'mix',
        'title' => 'Mix',
        'picture' => 'style_mix.png',
        'action' => '/ItemStyle?f=mix',
        'value' => ''
    );

    $output['data'][] = array(
        'tipe' => 'gallery',
        'title' => 'Gallery',
        'picture' => 'style_gallery.png',
        'action' => '/ItemStyle?f=gallery',
        'value' => ''
    );
    
    return $output;
}

function __style_prop() {
    $output = array(
        'count' => 5
    );
    
    $output['data'][] = array(
        'tipe' => 'hat',
        'title' => 'Headwear',
        'picture' => 'style_accessories_headwear.png',
        'action' => '/ItemStyle?f=hat',
        'value' => ''
    );

    $output['data'][] = array(
        'tipe' => 'eyewear',
        'title' => 'Eyewear',
        'picture' => 'style_accessories_eyewear.png',
        'action' => '/ItemStyle?f=eyewear',
        'value' => ''
    );

    $output['data'][] = array(
        'tipe' => 'other',
        'title' => 'Other',
        'picture' => 'style_accessories_other.png',
        'action' => '/ItemStyle?f=other',
        'value' => ''
    );

    return $output;
}

function __explode_avatar_items($query, $gender, $count) {
    $output = array();
    $output['count'] = $count;
    
    foreach ($query as $item) {
        $element = array(
            'gender' => $gender,
            'tipe' => $item['tipe'],
            'element' => str_replace(".unity3d", "", $item['element']),
            'material' => str_replace(".unity3d", "", $item['material'])
        );
        $json = str_replace("\"", "'", json_encode($element));

        $output['data'][] = array(
            'tipe' => $item['tipe'],
            'title' => $item['name'],
            'picture' => $item['preview_image'],
            'action' => '/AvatarPreview?f='.$item['tipe']. "&id=". (string)$item['_id'] . "&v=".$json,
            'value' => ''
        );
    }
    
    return $output;
}

function __style_bodypart($email, $tipe, $start, $limit) {
    $userDetails = __getUserDetails($email);
    
    $output = array();
    
    if ($userDetails) {
        $bodytype = !isset($userDetails['bodytype']) || $userDetails['bodytype'] == "" ? "medium" : $userDetails['bodytype'];
        $gender = $userDetails['gender'];
        //echo $tipe ." " . $bodytype . " " . $gender . "<br/>";
        
        $mongo = new LiloMongo();
        $mongo->selectDB("Assets");
        $mongo->selectCollection("Avatar");
        
        $avatar = null;
        $count = 0;
        
        if ($tipe == "hat") {
            $avatar = $mongo->find(array('tipe' => $tipe));
            $count = $mongo->count(array('tipe' => $tipe));
        } else if ($tipe == "shoes") {
            $avatar = $mongo->find(array('tipe' => $tipe));
            $count = $mongo->count(array('tipe' => $tipe));
        } else {
            $avatar = $mongo->find(array('tipe' => $tipe, 'size' => $bodytype, 'gender' => $gender));
            $count = $mongo->count(array('tipe' => $tipe, 'size' => $bodytype, 'gender' => $gender));
        }
        
        if ($avatar) {
            $output = __explode_avatar_items($avatar, $gender, $count);
        }
        
    }
    
    return $output;
}

function __style_mix($email) {
    $mongo = new LiloMongo();
    
    $userDetails = __getUserDetails($email);
    $id = $userDetails["id"];
    $gender = $userDetails['gender'];
    $bodytype = $userDetails['bodytype'];
    //echo $gender. " " . $bodytype;
    $mongo->selectDB("Users");
    $mongo->selectCollection("AvatarMix");
    $query = $mongo->find_pagging(array('gender' => $gender, 'bodytype' => $bodytype));
    $count = $mongo->count(array('gender' => $gender, 'bodytype' => $bodytype));
    
    $output = array();
    $output['count'] = $count;
    
    if ($query) {
        
        foreach ($query as $item) {
            $json = $item['configuration'];

            $output['data'][] = array(
                'tipe' => 'Mix',
                'title' => $item['name'],
                'picture' => $item['picture'],
                'action' => '/AvatarPreview?f=character'. '&id=' . (string)$item['_id'] ."&v=".$json,
                'value' => ''
            );
        }   
    }
    
    return $output;
}

/*
 * API to get Style Root List
 * 
 * Parameters:
 * 1. userid
 * 2. function: root, prop, top, bottom, etc
 * 
 * 
 */
function mobile_style_list() {
   $email = func_arg(0);
   $func = func_arg(1);
   
   $output = array();
   
   if ($func == "root") {
       $output = __style_root();
   }
   else if ($func == "accessories") {
       $output = __style_prop();
   }
   else if ($func == "mix") {
       $output = __style_mix($email, 0, 0);
   }
   else {
       $output = __style_bodypart($email, $func, 0, 0);
   }
  
   return json_encode($output);
}

?>
