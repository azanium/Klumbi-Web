<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$this->mongo_db->select_db("Users");
$this->mongo_db->select_collection("Account");
$user_owner = TRUE;
if($userid!="")
{
    $cek_user_data = $this->mongo_db->findOne(array('username' => (string)$userid));
    if($cek_user_data)
    {
        $this->mongo_db->select_collection("Properties");
        $cek_user_detail = $this->mongo_db->findOne(array('lilo_id' => (string)$cek_user_data['_id']));
        $user_owner = FALSE;
    }
    else
    {
        $cek_user_data = $this->cek_session->get_default_datauser();
        $cek_user_detail = $this->cek_session->get_detail_datauser($cek_user_data['_id']);
    }
}
else
{
    $cek_user_data = $this->cek_session->get_default_datauser();
    $cek_user_detail = $this->cek_session->get_detail_datauser($cek_user_data['_id']);
}
$picture = base_url()."resources/image/index.jpg";
if($cek_user_detail['picture']=="")
{
    if($cek_user_data['fb_id']!="")
    {
        $picture = "https://graph.facebook.com/".$cek_user_data['fb_id']."/picture?type=large";//large,smaller,square
    }
}
else
{
    $picture = $cek_user_detail['picture'];
}
?>
<script type="text/javascript">
$(document).ready(function() { 
    $(".following").click(function(){
        var id = $(this).attr("id")
        $.ajax({
            type: "GET",
            url: "<?php echo base_url()."api/social/user/button_follower/"; ?>" + "<?php echo (string)$this->session->userdata('user_id'); ?>" + "/" + $(this).attr("id"),
            data:{user_id:"<?php echo (string)$this->session->userdata('user_id'); ?>",friend_id:$(this).attr("id")},
            dataType: "json",
            error: function (xhr, textStatus, errorThrown) {
                   $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
            },
            success: function (data, textStatus) {
                if(data['success']==true)
                {                        
                    if(data['follower']==true)
                    {
                        $("#" + id).addClass('btn-magenta-alt');
                        $("#" + id).removeClass('btn-magenta');
                        $("#" + id).text("Unfollow");
                        $.pnotify({
                            title: "Success",
                            text: data['message'],
                            type: 'info'
                        });
                    }
                    else
                    {
                        $("#" + id).addClass('btn-magenta');
                        $("#" + id).removeClass('btn-magenta-alt');
                        $("#" + id).text("Follow");
                        $.pnotify({
                            title: "Success",
                            text: data['message'],
                            type: 'success'
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
            }
        });
        return false;
    });
});
</script>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>User Profile</li>
    </ol>
    <h1>User Profile</h1>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?php echo $picture; ?>" alt="" class="pull-left" style="margin: 0 20px 20px 0;max-width:120px; max-height:120px;" />
                            <div class="table-responsive">
                                <h3>
                                    <strong><?php echo $cek_user_detail['fullname']; ?></strong>
                                    <?php
                                    $this->mongo_db->select_db("Social");
                                    $this->mongo_db->select_collection("Social"); 
                                    if((string)$cek_user_data['_id'] != (string)$this->session->userdata('user_id'))
                                    {
                                        $filtering = array(
                                            'type'=>'Follower',
                                            'friend_id'=>(string)$cek_user_data['_id'],
                                            "user_id"=>(string)$this->session->userdata('user_id'),
                                        );
                                        $cekisfollow = $this->mongo_db->findOne($filtering);
                                        if($cekisfollow)
                                        {
                                            echo "<a href='#' id='".(string)$cek_user_data['_id']."' class='btn btn-magenta-alt btn-lg following'>Unfollow</a>";
                                        }
                                        else
                                        {
                                            echo "<a href='#' id='".(string)$cek_user_data['_id']."' class='btn btn-magenta btn-lg following' >Follow</a>";
                                        }
                                    }
                                    ?>
                                </h3>
                                <table class="table table-condensed">
                                    <tbody>
                                        <tr>
                                            <td>Avatar Name</td>
                                            <td><?php echo $cek_user_detail['avatarname']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Web</td>
                                            <td><a href="<?php echo $cek_user_detail['website']; ?>"><?php echo $cek_user_detail['website']; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td>Link</td>
                                            <td><a href="<?php echo $cek_user_detail['link']; ?>"><?php echo $cek_user_detail['link']; ?></a></td>
                                        </tr>
                                        <?php
                                        if($cek_user_detail['hide_email']==FALSE)
                                        {
                                            echo "<tr>";
                                            echo "<td>Email</td>";
                                            echo "<td>".$cek_user_data['email']."</td>";
                                            echo "</tr>";
                                        }
                                        if($cek_user_detail['hide_sex']==FALSE)
                                        {
                                            echo "<tr>";
                                            echo "<td>Gender</td>";
                                            echo "<td>".$cek_user_detail['sex']."</td>";
                                            echo "</tr>";
                                        }
                                        if($cek_user_detail['hide_phone']==FALSE)
                                        {
                                            echo "<tr>";
                                            echo "<td>Phone</td>";
                                            echo "<td>".$cek_user_detail['handphone']."</td>";
                                            echo "</tr>";
                                        }
                                        if($cek_user_detail['show_birthday']=="showall")
                                        {
                                            echo "<tr>";
                                            echo "<td>Birthday</td>";
                                            echo "<td>".date("d M, Y",strtotime($cek_user_detail['birthday_yy']."-".$cek_user_detail['birthday_mm']."-".$cek_user_detail['birthday_dd']))."</td>";
                                            echo "</tr>";
                                        }
                                        if($cek_user_detail['show_birthday']=="hideyear")
                                        {
                                            echo "<tr>";
                                            echo "<td>Birthday</td>";
                                            echo "<td>".date("d M",strtotime($cek_user_detail['birthday_yy']."-".$cek_user_detail['birthday_mm']."-".$cek_user_detail['birthday_dd']))."</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                        <tr>
                                            <td>Location</td>
                                            <td><?php echo $cek_user_detail['location']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>State Mind</td>
                                            <td><?php echo $cek_user_detail['state_of_mind']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Follower</td>
                                            <td><?php 
                                            $this->mongo_db->select_db("Social");
                                            $this->mongo_db->select_collection("Social");   
                                            $filtering = array(
                                                'type'=>'Follower',
                                                "user_id"=>(string)$cek_user_data['_id'],
                                            );
                                            echo "<a href='".$this->template_admin->link("home/follower/index/".(string)$cek_user_data['_id'])."'>".$this->mongo_db->count2($filtering)."</a>";
                                            ?></td>
                                        </tr>
                                        <tr>
                                            <td>Following</td>
                                            <td><?php 
                                            $this->mongo_db->select_db("Social");
                                            $this->mongo_db->select_collection("Social");   
                                            $filtering = array(
                                                'type'=>'Follower',
                                                "friend_id"=>(string)$cek_user_data['_id'],
                                            );
                                            echo "<a href='".$this->template_admin->link("home/following/index/".(string)$cek_user_data['_id'])."'>".$this->mongo_db->count2($filtering)."</a>";
                                            ?></td>
                                        </tr>
                                        <tr>
                                            <td>Joid Date</td>
                                            <td><?php echo date('Y-m-d H:i:s',$cek_user_data['join_date']->sec); ?></td>
                                        </tr>
                                        <?php
                                        if($user_owner)
                                        {
                                        ?>
                                        <tr>
                                            <td>Link Social</td>
                                            <td>
                                                <?php
                                                $link_fb = $this->template_admin->link("home/sociallog/linkto_facebook");
                                                if($cek_user_data['fb_id']!="")
                                                {
                                                    echo "<a href='".$this->template_admin->link("home/sociallog/unlinksocial/facebook")."' class='btn btn-xs btn-danger'>Unlink-><i class='icon-facebook'></i></a>&nbsp;&nbsp;";
                                                }
                                                else
                                                {
                                                    //$link_fb = "http://www.facebook.com/profile.php?id=".$cek_user_data['fb_id'];
                                                    echo "<a href='".$link_fb."' class='btn btn-xs btn-info'><i class='icon-facebook'></i></a>&nbsp;&nbsp;";
                                                }
                                                $link_tw = $this->template_admin->link("home/sociallog/linkto_twitter");
                                                if($cek_user_data['twitter_id']!="")
                                                {
                                                    echo "<a href='".$this->template_admin->link("home/sociallog/unlinksocial/twitter")."' class='btn btn-xs btn-danger'>Unlink-><i class='icon-twitter'></i></a>";
                                                }
                                                else
                                                {
                                                    //$link_tw = "https://twitter.com/account/redirect_by_id/".$cek_user_data['twitter_id'];
                                                    echo "<a href='".$link_tw."' class='btn btn-xs btn-sky'><i class='icon-twitter'></i></a>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        else
                                        {
                                        ?>
                                        <tr>
                                            <td>Link Social</td>
                                            <td>
                                                <?php
                                                if($cek_user_data['fb_id']!="")
                                                {
                                                    echo "<a href='http://www.facebook.com/profile.php?id=".$cek_user_data['fb_id']."' target='_blank' class='btn btn-xs btn-info'><i class='icon-facebook'></i></a>&nbsp;&nbsp;";
                                                }
                                                if($cek_user_data['twitter_id']!="")
                                                {
                                                    echo "<a href='https://twitter.com/account/redirect_by_id/".$cek_user_data['twitter_id']."' target='_blank' class='btn btn-xs btn-sky'><i class='icon-twitter'></i></a>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3>About</h3>
                            <p><?php echo $cek_user_detail['about']; ?></p>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <?php
                        $this->mongo_db->select_db("Social");
                        $this->mongo_db->select_collection("Social");
                        $iduserdet = (string)$cek_user_data['_id'];
                        $pencarian = array(
                            "user_id" => $iduserdet,
                            'type'=>array('$in'=>array("NewsLove","NewsComment","BrandLove","BrandComment","StateOfMind","Register","Follower","AvatarMixlove","AvatarMixComment","AvatarCollection","BannerLove","BannerComment","ChangeStateOfMind","ChangePicture")),
                        );
                        $list_data = $this->mongo_db->find($pencarian,0,0,array('datetime'=>-1));
                        $indexbgcolor = array('timeline-orange','timeline-success','timeline-midnightblue','timeline-warning','timeline-indigo','timeline-primary');
                        if($this->mongo_db->count2($pencarian) > 0)
                        {
                            echo "<h3>Timeline</h3>";
                            $index = 0;
                            $logdata = 0;
                            $datatempbulan = "";
                            foreach($list_data as $dt)
                            {
                                if($index>6)
                                {
                                    $index =0;
                                }
                                $datacurrentbulan = date('M Y',$dt['datetime']->sec);
                                if($datatempbulan != $datacurrentbulan)
                                {
                                    if($logdata>0)
                                    {
                                        echo "</ul>";
                                        echo "</div>";
                                    }
                                    echo "<div class='col-md-12'>";
                                    echo "<h4 class='timeline-month'><span>".date('M Y',$dt['datetime']->sec)."</span></h4>";
                                    echo "<ul class='timeline'>";
                                }
                                $picture = base_url()."resources/image/index.jpg";                                
                                echo "<li class='".$indexbgcolor[$index]."'>";
                                $icontimeline = "icon-pencil";
                                $headertext = "";
                                $contenttext = "";
                                $readmoretext = "";
                                $colortext = "style='color:red;'";
                                if($dt['type'] === "NewsLove")
                                {
                                    $this->mongo_db->select_db("Articles");
                                    $this->mongo_db->select_collection("ContentNews");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));            
                                    $icontimeline = "icon-heart";
                                    $headertext = "Love a news";
                                    $contenttext = "This user love ".$tempdata["title"];
                                    $readmoretext = $this->template_admin->link("home/news/detail/".$tempdata['alias']);
                                    $colortext = "style='color:red;'";
                                }
                                else if($dt['type'] === "NewsComment")
                                {
                                    $this->mongo_db->select_db("Articles");
                                    $this->mongo_db->select_collection("ContentNews");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));            
                                    $icontimeline = "icon-share";
                                    $headertext = "Comment on ".$tempdata["title"];
                                    $contenttext = $dt["comment"];
                                    $readmoretext = $this->template_admin->link("home/news/detail/".$tempdata['alias']);
                                    $colortext = "style='color:red;'";
                                }
                                else if($dt['type'] === "BrandLove")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Brand");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['brand_id'])));            
                                    $icontimeline = "icon-heart-empty";
                                    $headertext = "Love a Brand ";
                                    $contenttext = "This user love ".$tempdata["name"];
                                    $readmoretext = $tempdata['website'];
                                    $colortext = "style='color:blue;'";
                                }
                                else if($dt['type'] === "BrandComment")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Brand");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['brand_id'])));            
                                    $icontimeline = "icon-star";
                                    $headertext = "Comment on ".$tempdata["name"];
                                    $contenttext = $dt["comment"];
                                    $readmoretext = $tempdata['website'];
                                    $colortext = "style='color:blue;'";
                                }
                                else if($dt['type'] === "StateOfMind")
                                {           
                                    $icontimeline = "icon-pencil";
                                    $headertext = "Change Status";
                                    $contenttext = $dt["StateMind"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#0078ae;'";
                                }
                                else if($dt['type'] === "Register")
                                {           
                                    $icontimeline = "icon-android";
                                    $headertext = "Register on ".$this->config->item("aplicationname");
                                    $contenttext = "First Register on our App";
                                    $readmoretext = "";
                                    $colortext = "style='color:#009900;'";
                                }
                                else if($dt['type'] === "Follower")
                                {           
                                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                                    $tempdtaccount = $this->m_userdata->user_account_byid($dt["friend_id"]);
                                    $icontimeline = "icon-group";
                                    $headertext = "Follow a friend";
                                    $contenttext = "Follower of ".$tempdtuser["fullname"];
                                    $readmoretext = $this->template_admin->link("home/account/index/".$tempdtaccount['username']);
                                    $colortext = "style='color:#d2322d;'";
                                }
                                else if($dt['type'] === "AvatarMixlove")
                                {
                                    $this->mongo_db->select_db("Users");
                                    $this->mongo_db->select_collection("AvatarMix");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id'])));            
                                    $icontimeline = "icon-heart";
                                    $headertext = "Love a Mix";
                                    $contenttext = "This user love MixUser ".$tempdata["name"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#e044ab;'";
                                }
                                else if($dt['type'] === "AvatarMixComment")
                                {
                                    $this->mongo_db->select_db("Users");
                                    $this->mongo_db->select_collection("AvatarMix");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id'])));            
                                    $icontimeline = "icon-edit-sign";
                                    $headertext = "Comment a Mix ".$tempdata["name"];
                                    $contenttext = $dt["comment"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#e044ab;'";
                                }
                                else if($dt['type'] === "AvatarCollection")
                                {
                                    $this->mongo_db->select_db("Users");
                                    $this->mongo_db->select_collection("AvatarMix");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));            
                                    $icontimeline = "icon-beaker";
                                    $headertext = "Collect A mixuser";
                                    $contenttext = "You Collect a mix ".$tempdata["name"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#b91f84;'";
                                }
                                else if($dt['type'] === "BannerLove")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Banner");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['banner_id'])));            
                                    $icontimeline = "glyphicon glyphicon-thumbs-up";
                                    $headertext = "Love a Banner ";
                                    $contenttext = "This user love ".$tempdata["name"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#1bc2a1;'";
                                }
                                else if($dt['type'] === "BannerComment")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Banner");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['banner_id'])));            
                                    $icontimeline = "glyphicon glyphicon-pencil";
                                    $headertext = "Comment on Banner ".$tempdata["name"];
                                    $contenttext = $dt["comment"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#1bc2a1;'";
                                }
                                else if($dt['type'] === "AvatarItemLove")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Avatar");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['avatar_id'])));            
                                    $icontimeline = "icon-heart-empty";
                                    $headertext = "Love a Avatar Item ";
                                    $contenttext = "This user love ".$tempdata["name"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#1bc2a1;'";
                                }
                                else if($dt['type'] === "AvatarItemComment")
                                {
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("Avatar");        
                                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['avatar_id'])));            
                                    $icontimeline = "icon-edit";
                                    $headertext = "Comment on Avatar Item ".$tempdata["name"];
                                    $contenttext = $dt["comment"];
                                    $readmoretext = "";
                                    $colortext = "style='color:#1bc2a1;'";
                                }
                                else if($dt['type'] === "ChangeStateOfMind")
                                {
                                    $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                                    $icontimeline = "icon-bullhorn";
                                    $headertext = $tempdtuser["fullname"]." change state of mind";
                                    $contenttext = "<i class='icon-quote-left'></i> ".$tempdtuser["state_of_mind"]." <i class='icon-quote-right'></i>";
                                    $readmoretext = "";
                                    $colortext = "style='color:#1b55a1;'";
                                }
                                else if($dt['type'] === "ChangePicture")
                                {
                                    $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                                    $icontimeline = "icon-camera-retro";
                                    $headertext = $tempdtuser["fullname"]." change foto profile";
                                    $contenttext = "change foto profile";
                                    $readmoretext = "";
                                    $colortext = "style='color:#1b55a1;'";
                                }
                                echo "<div class='timeline-icon'><i class='".$icontimeline."' ".$colortext."></i></div>";
                                echo "<div class='timeline-body'>";
                                echo "<div class='timeline-header'>";
                                echo "<span class='author'>Posted by <a href='#'>You</a></span>";
                                echo "<span class='date'>".date('d M Y H:i:s',$dt['datetime']->sec)."</span>";
                                echo "</div>";
                                echo "<div class='timeline-content'>";
                                echo "<h3>".$headertext."</h3>";
                                echo "<p><img src='".$picture."' alt='' class='pull-left'>".$contenttext."</p>";
                                echo "</div>";
                                echo "<div class='timeline-footer'>";
                                if($readmoretext!="")
                                {
                                    echo "<a href='".$readmoretext."' class='btn btn-default btn-sm pull-left'>Read more</a>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "</li>";                               
                                $datatempbulan = $datacurrentbulan;
                                $index++;
                                $logdata++;
                            }
                            if($logdata>0)
                            {
                                echo "</ul>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   