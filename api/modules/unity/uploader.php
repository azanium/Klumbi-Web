<?php
include_once('config/config.php');
/*
 * API for Image Uploader
 * Methode: POST
 * Parameter :
 * 1. fileimage (type File Image)
 * 2. alamatpath : alamat path di folder server(Optional default kosong)
 * Example : [server host]/[server path]/api/unity/uploader/image
 * Return : String Message
 */
function unity_uploader_image()
{
    $output['sucsess'] = FALSE;
    $output['filename'] = "";
    $output['message'] = "";
    $pathbundles = PATH_ASSET_UPLOAD;//"./../bundles/";
    $allowedExts = array("jpg", "jpeg", "gif", "png", "unity3d", "zip");
    if(isset($_FILES['fileimage']["name"]))
    {
        if($_FILES['fileimage']['name']!="")
        {
            $extension = end(explode(".", strtolower($_FILES['fileimage']["name"])));
            $filetype=$_FILES["fileimage"]["type"];
            $filesize=$_FILES["fileimage"]["size"];
            if (in_array($extension, $allowedExts))
            {
                if ($_FILES["fileimage"]["error"] > 0)
                {
                    $output['message'] =  "Fail upload try again letter.";
                }
                else
                {
                    $path=$pathbundles.(isset($_POST['alamatpath'])?$_POST['alamatpath']:"");
                    if (file_exists($path.$_FILES["fileimage"]["name"]))
                    {
                        $output['message'] =  "Duplicate name of file, change another one";
                    }
                    else 
                    {
                        if(@move_uploaded_file($_FILES["fileimage"]["tmp_name"],$path.$_FILES["fileimage"]["name"]))
                        {
                            $output['message'] =  "Sucsess upload";
                            $output['sucsess'] = TRUE;
                            $output['filename'] = $_FILES["fileimage"]["name"];
                        }
                        else
                        {
                            $output['message'] =  "Fail upload, Error transfer file";
                        }                        
                    }
                }
            }
            else
            {
                $output['message'] =  "Extension must be png, jpeg, jpg, gif";
            }
        }
        else 
        {
            $output['message'] =  "File is Empty";
        }
    }
    else
    {
        $output['message'] = "Fail Upload data";
    }  
    return json_encode($output);
}
?>