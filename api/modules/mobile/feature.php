<?php 
include_once('libraries/LiloMongo.php');
include_once('baselibs.php');


function __features_root()
{
    $output = array(
            'count' => 7
        );
    $output['data'][] = array(
        'tipe' => 'face_part_eyes',
        'title' => 'Eyes',
        'picture' => 'feature_eyes.png',
        'action' => '/ItemFeature?f=face_part_eyes',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'face_part_lip',
        'title' => 'Mouth',
        'picture' => 'feature_mouth.png',
        'action' => '/ItemFeature?f=face_part_lip',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'hair',
        'title' => 'Hair',
        'picture' => 'feature_hair.png',
        'action' => '/ItemFeature?f=hair',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'face_part_eye_brows',
        'title' => 'Eyebrows',
        'picture' => 'feature_eyebrows.png',
        'action' => '/ItemFeature?f=face_part_eye_brows',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'gender',
        'title' => 'Gender',
        'picture' => 'feature_gender.png',
        'action' => '/Gender?f=male',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'bodytype',
        'title' => 'Body Type',
        'picture' => 'feature_bodytype.png',
        'action' => '/BodyType?f=average',
        'value' => ''
    );
    $output['data'][] = array(
        'tipe' => 'color',
        'title' => 'Colors',
        'picture' => 'feature_colors.png',
        'action' => '/ItemFeature?f=allcolors',
        'value' => ''
    );
    return json_encode($output);
}

function __features_part($email, $type, $start, $limit)
{
    $userData = __getUserDetails($email);
    $bodytype = $userData['bodytype'];
    $gender = $userData['gender'];
    
    $output = array();
    $lilo_mongo = new LiloMongo();
    $lilo_mongo->selectDB('Assets');
    $lilo_mongo->selectCollection('Avatar'); 
    
    $query1 = $lilo_mongo->find_pagging(array('tipe' => $type, 'gender' => $gender, 'size' => $bodytype), $start, $limit, array('last_update'=>-1));    
    $count1 = $lilo_mongo->count();
    
    $newType = str_replace("face_part_", "", $type);
    //echo "........... ". $newType . ".......";
    
    foreach ($query1 as $item) {
        if ($newType != $type) {
            $element = array(
                'tipe' => $newType,
                'element' => str_replace(".unity3d", "", $item['material'])
            );
        }
        else {
            $element = array(
                'gender' => $gender,
                'tipe' => $newType,
                'element' => str_replace(".unity3d", "", $item['element']),
                'material' => str_replace(".unity3d", "", $item['material'])
            );
        }
        $json = str_replace("\"", "'", json_encode($element));
    
        $output['data'][] = array(
        'tipe' => $type,
        'title' => $item['name'],
        'picture' => $item['preview_image'],
        'action' => '/AvatarPreview?f='. $type . "&id=". (string)$item['_id'] . "&v=".$json,
        'value' => ''
        );
    }
    
    $query2 = $lilo_mongo->find_pagging(array('tipe' => $type, 'gender' => 'unisex'), $start, $limit, array('last_update'=>-1));    
    $count2 = $lilo_mongo->count();
    
    foreach ($query2 as $item) {
        if ($newType != $type) {
            $element = array(
                'tipe' => $newType,
                'element' => str_replace(".unity3d", "", $item['material'])
            );
        }
        else { 
            $element = array(
                'gender' => $gender,
                'tipe' => $newType,
                'element' => str_replace(".unity3d", "", $item['element']),
                'material' => str_replace(".unity3d", "", $item['material'])
            );
        }
        $json = str_replace("\"", "'", json_encode($element));
        
        $output['data'][] = array(
        'tipe' => $type,
        'title' => $item['name'],
        'picture' => $item['preview_image'],
        'action' => '/AvatarPreview?f='. $type . "&id=". (string)$item['_id'] . "&v=".$json,
        'value' => (string)$item['_id']
        );
    }
    
    $output['count'] = $count1 + $count2;
    
    return json_encode($output);
}

function mobile_feature_list()
{
    $output = array();
    $email = func_arg(0);
    $func = func_arg(1);
    $start = func_arg(2);
    $limit = func_arg(3);

    if ($func == "root") {
        $output = __features_root();
    }
    else if ($func == "allcolors") {
        $output['data'][] = array(
            'tipe' => 'skincolor',
            'title' => 'Skin Color',
            'picture' => 'skincolor.png',
            'action' => '/ItemFeature?f=skincolors',
            'value' => ''
            );
        $output = json_encode($output);
    }
    else if ($func == "skincolors") {
        $output['count'] = 19;
    
        for ($i = 1; $i <= 19; $i++) {
            $output['data'][] = array(
                'tipe' => 'color'.$i,
                'title' => 'Color #'.$i,
                'picture' => $i.'.png',
                'action' => '/AvatarPreview?f=color&v='.$i,
                'value' => ''
            );
        }
        $output = json_encode($output);
    }
    else {
        $output = __features_part($email, $func, $start, $limit);
    }
    
    return $output;
}


?>
