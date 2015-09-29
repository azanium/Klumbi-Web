<?php
include_once('libraries/LiloMongo.php');
include_once('libraries/baselibs.php');

define("PATH_IMAGE_UPLOAD", $_SERVER['DOCUMENT_ROOT']."/bundles/preview_images/mix/");

function mobile_mix_list($email, $start, $limit) {
    $path = PATH_IMAGE_UPLOAD;
    
    return $path;
}
/**
 * Image resize
 * @param int $width
 * @param int $height
 */
function resize($width, $height){
  /* Get original image x y*/
  list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
  /* calculate new image size with ratio */
  $ratio = max($width/$w, $height/$h);
  $h = ceil($height / $ratio);
  $x = ($w - $width / $ratio) / 2;
  $w = ceil($width / $ratio);
  /* new file name */
  $path = 'uploads/'.$width.'x'.$height.'_'.$_FILES['image']['name'];
  /* read binary data from image file */
  $imgString = file_get_contents($_FILES['image']['tmp_name']);
  /* create image from string */
  $image = imagecreatefromstring($imgString);
  $tmp = imagecreatetruecolor($width, $height);
  imagecopyresampled($tmp, $image,
    0, 0,
    $x, 0,
    $width, $height,
    $w, $h);
  /* Save image */
  switch ($_FILES['image']['type']) {
    case 'image/jpeg':
      imagejpeg($tmp, $path, 100);
      break;
    case 'image/png':
      imagepng($tmp, $path, 0);
      break;
    case 'image/gif':
      imagegif($tmp, $path);
      break;
    default:
      exit;
      break;
  }
  return $path;
  /* cleanup memory */
  imagedestroy($image);
  imagedestroy($tmp);
}

function mobile_mix_create() {
    
    $email = func_arg(0);
    $mixName = func_arg(1);
    
    $configuration = $_POST["configuration"];
    $pathimages = PATH_IMAGE_UPLOAD;
    $allowedExts = array("jpg", "jpeg", "gif", "png");
    $success = FALSE;
    $message = "";
    $filename = $_FILES['file']['name'];
    
    if(isset($_FILES['file']["name"]))
    {
        if($_FILES['file']['name']!="")
        {
            $extension = end(explode(".", strtolower($_FILES['file']["name"])));
            //$filetype=$_FILES["file"]["type"];
            //$filesize=$_FILES["file"]["size"];
            if (in_array($extension, $allowedExts))
            {
                if ($_FILES["file"]["error"] > 0)
                {
                    $message = "Invalid mix preview.";
                }
                else
                {
                    $path = $pathimages;
                    if (file_exists($path.$_FILES["file"]["name"]))
                    {
                        $message =  "Filename exists..";
                    }
                    else 
                    {
                        //echo "File : " . $_FILES['file']['tmp_name'] . " => " .$path.$_FILES["file"]["name"];
                        if(@move_uploaded_file($_FILES["file"]["tmp_name"], $path.$_FILES["file"]["name"]))
                        {
                            $message =  "File berhasil terupload";
                            $success = TRUE;
                            $filename = $_FILES["file"]["name"];
                        }
                        else
                        {
                            $message =  "File gagal terupload";
                        }                        
                    }
                }
            }
            else
            {
                $message =  "Image type does not supported";
            }
        }
        else 
        {
            $message = "Image data empty";
        }
    }
    else
    {
        $message = "Failed upload mix preview";
    }  
    
    $userDetails = __getUserDetails($email);
    
    $mongo = new LiloMongo();
    $mongo->selectDB("Users");
    $mongo->selectCollection("AvatarMix");
    /*
     * 1. userid.
        2. authorname.
        3. Nama.
        4. Deskripsi
        5. tanggal dan jam pembuatan.
        6. konfigurasi
        7. gender.
        8. body type (medium, thin, fat)
        9. picture.(screenshot)
     */
    $data = array(
        "userid" => $userDetails["id"],
        "author" => isset($userDetails["avatarname"]) ? $userDetails['avatarname'] : $userDetails['username'],
        "name" => $mixName,
        "description" => "",
        "date" => date('Y-m-d H:i:s'),
        "gender" => $userDetails["gender"],
        "bodytype" => $userDetails["bodytype"],
        "picture" => "mix/".$filename,
        "configuration" => $configuration
    );
    $mongo->insert($data);
    
    $output = array(
        'success' => $success,
        'filename' => $filename,
        'message' => $message
    );
    
    return json_encode($output);
}
    
?>
