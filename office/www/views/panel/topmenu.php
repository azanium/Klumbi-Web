<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <?php
    if($leftmenu)
    {
    ?>
    <a id="leftmenu-trigger" class="pull-left" data-toggle="tooltip" data-placement="bottom" title="Toggle Left Sidebar"></a>
    <?php
    }
    if($this->session->userdata('login'))
    {
    ?>
    <a id="rightmenu-trigger" class="pull-right" data-toggle="tooltip" data-placement="bottom" title="Toggle Right Sidebar"></a>
    <?php
    }
    ?>
    <div class="navbar-header pull-left">
        <a class="navbar-brand" href="<?php echo $this->template_admin->link("home/welcome"); ?>"><img src="<?php echo base_url(); ?>resources/image/logo/logoadminbar.png" alt="Logo" height="23" /></a>
    </div>
    <ul class="nav navbar-nav pull-right toolbar">
    <?php
    if($this->session->userdata('login'))
    {
        $this->mongo_db->select_db("Users");
        $this->mongo_db->select_collection("Properties");
        $data=$this->mongo_db->findOne(array('lilo_id' => $this->session->userdata('user_id')));
        $picture = base_url()."resources/image/index.jpg";
        $name = "";
        if($data)
        {                                       
            if(isset($data['picture']))
            {
                if($data['picture']!="")
                {
                    $picture = $data['picture'];
                }
            }
            else
            {
                $this->mongo_db->select_collection("Account");
                $dataaccount = $this->mongo_db->findOne(array('_id' => $this->mongo_db->mongoid($this->session->userdata('user_id'))));
                if($dataaccount)
                {
                    if(isset($dataaccount['fb_id']))
                    {
                        if($dataaccount['fb_id']!="")
                        {
                            $picture = "https://graph.facebook.com/".$dataaccount['fb_id']."/picture";
                        }
                    }
                    if(isset($dataaccount['twitter_id']))
                    {
                        if($dataaccount['twitter_id']!="")
                        {
                            $picture = "http://api.twitter.com/1/users/profile_image/".$dataaccount['twitter_id']."/image1327396628_normal.png";
                        }
                    }
                }
            }
            $name = $data["fullname"];
            $defaultname = isset($data["avatarname"])?$data["avatarname"]:"";
        }
    ?>
    <li class="dropdown">
        <a href="#" class="dropdown-toggle username" data-toggle="dropdown"><span class="hidden-xs"><?php echo $defaultname; ?> <i class="icon-caret-down icon-scale"></i></span><img src="<?php echo $picture; ?>" alt="<?php echo $name; ?>" /></a>
        <ul class="dropdown-menu userinfo arrow">
            <?php            
            echo "<li class='username'>";
            echo "<a href='#'>";            
            echo "<div class='pull-left'><img class='userimg' src='".$picture."' alt='picture' /></div>";
            echo "<div class='pull-right'><h5>".$this->tambahan_fungsi->ucapkan_salam()."!</h5><small>Logged in as <span>".character_limiter($name,5)."</span></small></div>";
            echo "</a>";
            echo "</li>";
            ?>
            <li class="userlinks">
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("home/account") ?>">Profile <i class="pull-right icon-home"></i></a></li>
                    <li><a href="<?php echo $this->template_admin->link("home/setting") ?>">Edit Profile <i class="pull-right icon-pencil"></i></a></li>
                    <!--li><a href="<?php echo $this->template_admin->link("member/timeline/index") ?>">Status <i class="pull-right icon-external-link"></i></a></li-->
                    <li><a href="<?php echo $this->template_admin->link("home/eaccount") ?>">Account <i class="pull-right icon-cog"></i></a></li>
                    <li><a href="<?php echo $this->template_admin->link("home/content/index/help") ?>">Help <i class="pull-right icon-question-sign"></i></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $this->template_admin->link("home/logout") ?>" class="text-right">Sign Out</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <?php    
    $this->load->view("panel/message");
    $this->load->view("panel/notification");
    echo "<li><a href='#' id='headerbardropdown'><span><i class='icon-level-down'></i></span></a></li>";
    //$this->load->view("panel/changethema");
    }
    else
    {
    ?>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle='dropdown'><i class="icon-search"></i></a>
            <ul class="dropdown-menu search arrow">
                <li>
                    <form action="<?php echo $this->template_admin->link("home/search"); ?>" role="search" />
                        <input name="q" type="text" placeholder="Search..." class="form-control" />
                    </form>
                </li>
            </ul>
        </li>
    <?php
    }
    ?>
    </ul>
</header>
<?php
if($this->session->userdata('login'))
{
    $this->load->view('panel/panel_bar_kanan');
} 
?>