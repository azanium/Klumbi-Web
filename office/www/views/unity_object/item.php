<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
.margin_right_5{margin-right: 5px;}
.margin_top_5{margin-top: 5px;}
.pointer{cursor: pointer;}
.margin_button_5{margin-top: 5px;}
.color{float: left;width: 30px;height: 30px;background-color: #eee;}
.items, .fiture , .datafrmurl{float: left;width: 110px;height: 110px;background-color: #eee;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4>Item Configurations</h4>
                <div class="options">
                    <ul class="nav nav-tabs">
                      <?php
                      if($loadgender)
                      {
                      ?>
                      <li class="active"><a href="#frmgender" data-toggle="tab"><i class="icon-female"></i> Gender</a></li>
                      <li><a href="#frmbody" data-toggle="tab"><i class="icon-lemon"></i> Body</a></li>
                      <?php
                      }
                      ?>
                      <li <?php echo (($loadgender)?"":"class='active'"); ?>><a href="#frmskin" data-toggle="tab"><i class="icon-lemon"></i> Skin</a></li>
                      <li><a href="#frmhair" data-toggle="tab"><i class="glyphicon glyphicon-certificate"></i> Hair</a></li>
                      <li><a href="#frmeyes" data-toggle="tab"><i class="icon-eye-open"></i> Eyes</a></li>
                      <li><a href="#frmbrows" data-toggle="tab"><i class="glyphicon glyphicon-compressed"></i> Brows</a></li>
                      <li><a href="#frmmouth" data-toggle="tab"><i class="glyphicon glyphicon-heart-empty"></i> Mouth</a></li>
                      <li><a href="#frmtop" data-toggle="tab"><i class="glyphicon glyphicon-tower"></i> Top</a></li>
                      <li><a href="#frmbutton" data-toggle="tab"><i class="icon-leaf"></i> Botton</a></li>
                      <li><a href="#frmfoot" data-toggle="tab"><i class="icon-gift"></i> Foot</a></li>
                      <li><a href="#frmprops" data-toggle="tab"><i class="icon-trophy"></i> Props</a></li>
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <?php
                    if($loadgender)
                    {
                    ?>
                    <div class="tab-pane active" id="frmgender">
                        <div class="items margin_right_5 margin_top_5 pointer" id="Feature-Gender-Male" name="txtgender-male">&nbsp;</div>
                        <div class="items margin_right_5 margin_top_5 pointer" id="Feature-Gender-Female" name="txtgender-female">&nbsp;</div>
                    </div>
                    <div class="tab-pane" id="frmbody">
                        <div class="items margin_right_5 margin_top_5 pointer" id="Feature-Body-Small" name="txtsize-thin">&nbsp;</div>
                        <div class="items margin_right_5 margin_top_5 pointer" id="Feature-Body-Medium" name="txtsize-medium">&nbsp;</div>
                        <div class="items margin_right_5 margin_top_5 pointer" id="Feature-Body-Large" name="txtsize-fat">&nbsp;</div>
                    </div>
                    <?php
                    }
                    ?>
                    <div class="tab-pane <?php echo (($loadgender)?"":"active"); ?>" id="frmskin">
                        <?php
                        $this->mongo_db->select_db("Assets");        
                        $this->mongo_db->select_collection("SkinColor");
                        $dataskin=$this->mongo_db->find(array(),0,0,array("name"=>1));
                        foreach($dataskin as $dtskin)
                        {
                            echo "<div name='txtskin-".$dtskin['name']."' class='color margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$this->config->item('path_asset_img')."preview_images/".$dtskin['file']."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmhair">
                        <?php
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Category");
                        $list_coll_hair = $this->mongo_db->find(array('tipe'=>"hair"),0,0,array('name'=>1));
                        $index=0;
                        foreach($list_coll_hair as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                            }
                            echo "<a href='#hair".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                            $index++;
                        }
                        $this->mongo_db->select_collection("Avatar");
                        $list_avatar_null = $this->mongo_db->find(array("tipe"=>"hair","category"=>""),0,0,array('name'=>1));
                        foreach($list_avatar_null as $dtitemdetail)
                        {
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                            }
                            echo "<div id='hair-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                        }
                        $index=0;
                        foreach($list_coll_hair as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            echo "<div id='hair".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                            echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            $this->mongo_db->select_collection("Avatar");
                            $filteringitem = array(
                                            "tipe"=>"hair",
                                            "category"=>$nilai['name'],
                                        );
                            $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                            echo "<div class='row'>";
                            foreach($list_avatar_temp as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='hair-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmeyes">
                        <?php
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Category");
                        $list_coll_eyes = $this->mongo_db->find(array('tipe'=>"face_part_eyes"),0,0,array('name'=>1));
                        $index=0;
                        foreach($list_coll_eyes as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                            }
                            echo "<a href='#eyes".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                            $index++;
                        }
                        $this->mongo_db->select_collection("Avatar");
                        $list_avatar_null = $this->mongo_db->find(array("tipe"=>"face_part_eyes","category"=>""),0,0,array('name'=>1));
                        foreach($list_avatar_null as $dtitemdetail)
                        {
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                            }
                            echo "<div id='eyes-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                        }
                        $index=0;
                        foreach($list_coll_eyes as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            echo "<div id='eyes".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                            echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            $this->mongo_db->select_collection("Avatar");
                            $filteringitem = array(
                                            "tipe"=>"face_part_eyes",
                                            "category"=>$nilai['name'],
                                        );
                            $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                            echo "<div class='row'>";
                            foreach($list_avatar_temp as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='eyes-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmbrows">
                        <?php
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Category");
                        $list_coll_eyesbrows = $this->mongo_db->find(array('tipe'=>"face_part_eye_brows"),0,0,array('name'=>1));
                        $index=0;
                        foreach($list_coll_eyesbrows as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                            }
                            echo "<a href='#eyesbrows".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                            $index++;
                        }
                        $this->mongo_db->select_collection("Avatar");
                        $list_avatar_null = $this->mongo_db->find(array("tipe"=>"face_part_eye_brows","category"=>""),0,0,array('name'=>1));
                        foreach($list_avatar_null as $dtitemdetail)
                        {
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                            }
                            echo "<div id='eyeBrows-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                        }
                        $index=0;
                        foreach($list_coll_eyesbrows as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            echo "<div id='eyesbrows".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                            echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            $this->mongo_db->select_collection("Avatar");
                            $filteringitem = array(
                                            "tipe"=>"face_part_eye_brows",
                                            "category"=>$nilai['name'],
                                        );
                            $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                            echo "<div class='row'>";
                            foreach($list_avatar_temp as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='eyeBrows-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmmouth">
                        <?php
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Category");
                        $list_coll_lips = $this->mongo_db->find(array('tipe'=>"face_part_lip"),0,0,array('name'=>1));
                        $index=0;
                        foreach($list_coll_lips as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                            }
                            echo "<a href='#lips".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                            $index++;
                        }
                        $this->mongo_db->select_collection("Avatar");
                        $list_avatar_null = $this->mongo_db->find(array("tipe"=>"face_part_lip","category"=>""),0,0,array('name'=>1));
                        foreach($list_avatar_null as $dtitemdetail)
                        {
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                            }
                            echo "<div id='lip-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                        }
                        $index=0;
                        foreach($list_coll_lips as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            echo "<div id='lips".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                            echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            $this->mongo_db->select_collection("Avatar");
                            $filteringitem = array(
                                            "tipe"=>"face_part_lip",
                                            "category"=>$nilai['name'],
                                        );
                            $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                            echo "<div class='row'>";
                            foreach($list_avatar_temp as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='lip-ChangeFacePartEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmtop">
                        <div class="row">
                            <h3>Male</h3><hr />
                            <?php
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Category");
                            $list_coll_body = $this->mongo_db->find(array('tipe'=>"body"),0,0,array('name'=>1));
                            $index=0;
                            foreach($list_coll_body as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                                }
                                echo "<a href='#body".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                                $index++;
                            }
                            $this->mongo_db->select_collection("Avatar");
                            $list_avatar_null = $this->mongo_db->find(array("tipe"=>"body","gender"=>"male","category"=>""),0,0,array('name'=>1));
                            foreach($list_avatar_null as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='body-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                            }
                            $index=0;
                            foreach($list_coll_body as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                echo "<div id='body".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                                echo "<div class='modal-dialog'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                $this->mongo_db->select_collection("Avatar");
                                $filteringitem = array(
                                                "tipe"=>"body",
                                                "gender"=>"male",
                                                "category"=>$nilai['name'],
                                            );
                                $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                                echo "<div class='row'>";
                                foreach($list_avatar_temp as $dtitemdetail)
                                {
                                    $gambar = base_url()."resources/image/no-avatar.png";
                                    $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                    if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                    {
                                        $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                    }
                                    echo "<div id='body-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $index++;
                            }
                            ?>
                        </div>
                        <div class="row">
                            <h3>Female</h3><hr />
                            <?php
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Category");
                            $list_coll_body = $this->mongo_db->find(array('tipe'=>"body"),0,0,array('name'=>1));
                            $index=0;
                            foreach($list_coll_body as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                                }
                                echo "<a href='#fbody".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                                $index++;
                            }
                            $this->mongo_db->select_collection("Avatar");
                            $list_avatar_null = $this->mongo_db->find(array("tipe"=>"body","gender"=>"female","category"=>""),0,0,array('name'=>1));
                            foreach($list_avatar_null as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='body-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                            }
                            $index=0;
                            foreach($list_coll_body as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                echo "<div id='fbody".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                                echo "<div class='modal-dialog'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                $this->mongo_db->select_collection("Avatar");
                                $filteringitem = array(
                                                "tipe"=>"body",
                                                "gender"=>"female",
                                                "category"=>$nilai['name'],
                                            );
                                $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                                echo "<div class='row'>";
                                foreach($list_avatar_temp as $dtitemdetail)
                                {
                                    $gambar = base_url()."resources/image/no-avatar.png";
                                    $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                    if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                    {
                                        $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                    }
                                    echo "<div id='body-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $index++;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="frmbutton">
                        <div class="row">
                            <h3>Male</h3><hr />
                            <?php
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Category");
                            $list_coll_pants = $this->mongo_db->find(array('tipe'=>"pants"),0,0,array('name'=>1));
                            $index=0;
                            foreach($list_coll_pants as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                                }
                                echo "<a href='#pants".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                                $index++;
                            }
                            $this->mongo_db->select_collection("Avatar");
                            $list_avatar_null = $this->mongo_db->find(array("tipe"=>"pants","gender"=>"male","category"=>""),0,0,array('name'=>1));
                            foreach($list_avatar_null as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='pants-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                            }
                            $index=0;
                            foreach($list_coll_pants as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                echo "<div id='pants".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                                echo "<div class='modal-dialog'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                $this->mongo_db->select_collection("Avatar");
                                $filteringitem = array(
                                                "tipe"=>"pants",
                                                "gender"=>"male",
                                                "category"=>$nilai['name'],
                                            );
                                $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                                echo "<div class='row'>";
                                foreach($list_avatar_temp as $dtitemdetail)
                                {
                                    $gambar = base_url()."resources/image/no-avatar.png";
                                    $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                    if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                    {
                                        $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                    }
                                    echo "<div id='pants-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $index++;
                            }
                            ?>
                        </div>
                        <div class="row">
                            <h3>Female</h3><hr />
                            <?php
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Category");
                            $list_coll_pants = $this->mongo_db->find(array('tipe'=>"pants"),0,0,array('name'=>1));
                            $index=0;
                            foreach($list_coll_pants as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                                }
                                echo "<a href='#fpants".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                                $index++;
                            }
                            $this->mongo_db->select_collection("Avatar");
                            $list_avatar_null = $this->mongo_db->find(array("tipe"=>"pants","gender"=>"female","category"=>""),0,0,array('name'=>1));
                            foreach($list_avatar_null as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='pants-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                            }
                            $index=0;
                            foreach($list_coll_pants as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                echo "<div id='fpants".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                                echo "<div class='modal-dialog'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                $this->mongo_db->select_collection("Avatar");
                                $filteringitem = array(
                                                "tipe"=>"pants",
                                                "gender"=>"male",
                                                "category"=>$nilai['name'],
                                            );
                                $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                                echo "<div class='row'>";
                                foreach($list_avatar_temp as $dtitemdetail)
                                {
                                    $gambar = base_url()."resources/image/no-avatar.png";
                                    $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                    if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                    {
                                        $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                    }
                                    echo "<div id='pants-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $index++;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="frmfoot">
                        <?php
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Category");
                        $list_coll_shoes = $this->mongo_db->find(array('tipe'=>"shoes"),0,0,array('name'=>1));
                        $index=0;
                        foreach($list_coll_shoes as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                            }
                            echo "<a href='#shoes".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                            $index++;
                        }
                        $this->mongo_db->select_collection("Avatar");
                        $list_avatar_null = $this->mongo_db->find(array("tipe"=>"shoes","category"=>""),0,0,array('name'=>1));
                        foreach($list_avatar_null as $dtitemdetail)
                        {
                            $gambar = base_url()."resources/image/no-avatar.png";
                            $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                            if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                            {
                                $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                            }
                            echo "<div id='shoes-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                        }
                        $index=0;
                        foreach($list_coll_shoes as $dthair=>$nilai)
                        {
                            $idhair = (string)$nilai['_id'];
                            echo "<div id='shoes".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                            echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            $this->mongo_db->select_collection("Avatar");
                            $filteringitem = array(
                                            "tipe"=>"shoes",
                                            "category"=>$nilai['name'],
                                        );
                            $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                            echo "<div class='row'>";
                            foreach($list_avatar_temp as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='shoes-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                            }
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            $index++;
                        }
                        ?>
                    </div>
                    <div class="tab-pane" id="frmprops">
                        <div class="row">
                            <h3>Hat</h3><hr />
                            <?php
                            echo "<div name='hat-hat' class='none_prop margin_right_5 margin_top_5 pointer' style='background-image: url(\"".base_url()."resources/image/none.jpg\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;float: left;background-color: #eee;'>&nbsp;</div>";
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Category");
                            $list_coll_hat = $this->mongo_db->find(array('tipe'=>"hat"),0,0,array('name'=>1));
                            $index=0;
                            foreach($list_coll_hat as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($nilai['image'])?$nilai['image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nilai['image'];
                                }
                                echo "<a href='#hat".$index."-".$idhair."' data-toggle='modal' data-trigger='hover' data-original-title='".$nilai['name']."' class='fiture tooltips margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'> </a>";
                                $index++;
                            }
                            $this->mongo_db->select_collection("Avatar");
                            $list_avatar_null = $this->mongo_db->find(array("tipe"=>"hat","category"=>""),0,0,array('name'=>1));
                            foreach($list_avatar_null as $dtitemdetail)
                            {
                                $gambar = base_url()."resources/image/no-avatar.png";
                                $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                {
                                    $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                }
                                echo "<div id='hat-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;width: 100px;height: 100px;'>&nbsp;</div>";
                            }
                            $index=0;
                            foreach($list_coll_hat as $dthair=>$nilai)
                            {
                                $idhair = (string)$nilai['_id'];
                                echo "<div id='hat".$index."-".$idhair."' class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true' style='display: none;'>";
                                echo "<div class='modal-dialog'>";
                                echo "<div class='modal-content'>";
                                echo "<div class='modal-header'>";
                                echo "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
                                echo "<h4 class='modal-title'>".$nilai['name']."</h4>";
                                echo "</div>";
                                echo "<div class='modal-body'>";
                                $this->mongo_db->select_collection("Avatar");
                                $filteringitem = array(
                                                "tipe"=>"hat",
                                                "category"=>$nilai['name'],
                                            );
                                $list_avatar_temp = $this->mongo_db->find($filteringitem,0,0,array('name'=>1));
                                echo "<div class='row'>";
                                foreach($list_avatar_temp as $dtitemdetail)
                                {
                                    $gambar = base_url()."resources/image/no-avatar.png";
                                    $nameimage_temp=isset($dtitemdetail['preview_image'])?$dtitemdetail['preview_image']:"";
                                    if ((file_exists($this->config->item('path_upload').'preview_images/'.$nameimage_temp)) && ($nameimage_temp!="")) 
                                    {
                                        $gambar = $this->config->item('path_asset_img')."preview_images/".$nameimage_temp;
                                    }
                                    echo "<div id='hat-ChangeElementEvent-".(string)$dtitemdetail["_id"]."' name='".(string)$dtitemdetail["_id"]."' class='datafrmurl margin_right_5 margin_top_5 pointer' style='background-image: url(\"".$gambar."\");background-repeat: no-repeat;background-position: center;'>&nbsp;</div>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                echo "</div>";
                                $index++;
                            }
                            ?>
                        </div>
                        <div class="row">
                            <h3>Glass</h3><hr />
                        </div>
                        <div class="row">
                            <h3>Helmet</h3><hr />                            
                        </div>
                        <div class="row">
                            <h3>Watch</h3><hr />                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $(".color").click(function(){
        var _id = $(this).attr('name');
        var _id_split = _id.split('-');
        var code_skin = parseInt(_id_split[1]);
        $('#'+_id_split[0]).val(code_skin);
        try
        {
            GetUnity().SendMessage("_DressRoom", "ChangeSkinColor", code_skin); 
        }
        catch(error){
            $.pnotify({
                    title: "Error Setting Avatar",
                    text: "Fail to set color skin of avatar",
                    type: 'error'
                });
        }
    });
    $(".datafrmurl").click(function(){
        var _id = $(this).attr('id');
        var _name = $(this).attr('name');
        var _id_split = _id.split('-');
        $('#'+_id_split[0]).val(_name);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->template_admin->link("services/avatar/loaddtavatar"); ?>",
            data:{id:_name,size:$("#txtsize").val(),gender:$("#txtgender").val()},
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                        '<strong>Warning!</strong> ' +
                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                        '</div>').slideDown(100);
            },
            success: function (data, textStatus) {                      
                if(data['success'])
                {
                        try{
                            GetUnity().SendMessage("_DressRoom", _id_split[1], data['configurations']);
                        }
                        catch(error){
                            $.pnotify({
                                title: "Error Setting Avatar",
                                text: "Fail to set avatar Configurations",
                                type: 'error'
                            });
                        } 
                }    
                else
                {
                    $.pnotify({
                        title: "Error Setting Avatar",
                        text: data['message'],
                        type: 'error'
                    });
                }
                $("#loadingprocess").slideUp(100);
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#loadingprocess").slideUp(100);
                $.pnotify({
                    title: textStatus + " " + xhr.status,
                    text: (errorThrown ? errorThrown : xhr.status),
                    type: 'error'
                });
            }
        });
    });
    $(".none_prop").click(function(){
        var _id = $(this).attr('name');
        var _id_split = _id.split('-');
        $("#" + _id_split[0]).val("");
        try{
            var message = "{'gender':'" + $("#txtgender").val() + "','tipe':'" + _id_split[1] + "','element':'','material':''}";
            GetUnity().SendMessage("_DressRoom", "ChangeElementEvent", message);
        }
        catch(error){
            $.pnotify({
                title: "Error Setting Properti Avatar",
                text: "Fail to set Properti avatar Configurations",
                type: 'error'
            });
        }     
    });
    $(".items").click(function(){
        var _id = $(this).attr('name');
        var _id_split = _id.split('-');
        $("#" + _id_split[0]).val(_id_split[1]);
        $.ajax({
            type: "POST",
            url: "<?php echo $this->template_admin->link("services/avatar/cekconf"); ?>",
            data:$("#brandfrm").serialize(),
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                        '<strong>Warning!</strong> ' +
                                        '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                        '<i class="process">Wait a minute, Your request being processed</i>' +
                                        '</div>').slideDown(100);
            },
            success: function (data, textStatus) {
                $("#loadingprocess").slideUp(100);
                if(data['success']==true)
                {                        
                    try
                    {
                        var data_message = data['configurations'];
                        GetUnity().SendMessage("_DressRoom", "ChangeCharacterEvent", data_message);
                    }
                    catch(error){
                        $.pnotify({
                            title: "Error Setting Avatar",
                            text: "Fail to set avatar Configurations",
                            type: 'error'
                        });
                    }
                }
                else
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'info'
                    });
                }                      
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#loadingprocess").slideUp(100);
                $.pnotify({
                    title: textStatus + " " + xhr.status,
                    text: (errorThrown ? errorThrown : xhr.status),
                    type: 'error'
                });
            }
        });                
    });
});
</script>