<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Timeline</li>
    </ol>
    <h1>Timeline</h1>
</div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <form method="POST" class="form-inline" id="brandfrm" action="<?php echo $this->template_admin->link("member/timeline/save"); ?>">
            <div class="input-group">
                <div class="col-sm-12">
                    <input name="txtstatus" id="txtstatus" placeholder="Enter your status here.." class="form-control {required:true}" value="" maxlength="255" type="text">
                </div>                                    
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-primary"><i class="icon-external-link"></i> Send</button>
                </span>
            </div>
        </form>
    </div>
    <div class="row">
        <?php
        $this->mongo_db->select_db("Social");
        $this->mongo_db->select_collection("Social");
        $this->mongo_db->update(array(
            'type'=>array('$in'=>array("UserLoveMixNotification","UserCommentMixNotification","UserCollectNotification")),
            "user_id" => (string)$this->session->userdata('user_id'),
        ),array('$set'=>array('read'=>TRUE)));
        $pencarian = array(
            'type'=>array('$in'=>array("StateOfMind","UserLoveMixNotification","UserCommentMixNotification","UserCollectNotification")),
            "user_id" => (string)$this->session->userdata('user_id'),
        );
        $list_data = $this->mongo_db->find($pencarian,0,0,array('datetime'=>-1));
        $indexbgcolor = array('timeline-orange','timeline-success','timeline-midnightblue','timeline-warning','timeline-indigo','timeline-primary');
        if($this->mongo_db->count2($pencarian) > 0)
        {
            $index = 0;
            $logdata = 0;
            $datatempbulan = "";
            foreach($list_data as $dt)
            {
                if($index>6)
                {
                    $index =0;
                }
                $datacurrentbulan = date('M y',$dt['datetime']->sec);
                if($datatempbulan != $datacurrentbulan)
                {
                    if($logdata>0)
                    {
                        echo "</ul>";
                        echo "</div>";
                    }
                    echo "<div class='col-md-12'>";
                    echo "<h4 class='timeline-month'><span>".date('M y',$dt['datetime']->sec)."</span></h4>";
                    echo "<ul class='timeline'>";
                }
                echo "<li class='".$indexbgcolor[$index]."'>";
                $icontimeline = "icon-pencil";
                $headertext = "";
                $contenttext = "";
                $readmoretext = "";
                $colortext = "style='color:red;'";
                if($dt['type'] === "StateOfMind")
                {           
                    $icontimeline = "icon-pencil";
                    $headertext = "Change Status";
                    $contenttext = $dt["StateMind"];
                    $colortext = "style='color:#0078ae;'";
                }                
                else if($dt['type'] === "UserLoveMixNotification")
                {
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("AvatarMix");        
                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id'])));
                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                    $tempdtaccountuser = $this->m_userdata->user_account_byid($dt["friend_id"]);
                    $icontimeline = "icon-heart";
                    $headertext = "Love a Mix";
                    $contenttext = $tempdtuser['fullname']." Love MixUser ".$tempdata["name"];
                    $colortext = "style='color:#e044ab;'";
                    $readmoretext = $this->template_admin->link("home/account/index/".$tempdtaccountuser['username']);
                }
                else if($dt['type'] === "UserCommentMixNotification")
                {
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("AvatarMix");        
                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['mix_id'])));
                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                    $tempdtaccountuser = $this->m_userdata->user_account_byid($dt["friend_id"]);
                    $icontimeline = "icon-edit-sign";
                    $headertext = $tempdtuser['fullname']." Comment a Mix ".$tempdata["name"];
                    $contenttext = $dt["comment"];
                    $colortext = "style='color:#e044ab;'";
                    $readmoretext = $this->template_admin->link("home/account/index/".$tempdtaccountuser['username']);
                } 
                else if($dt['type'] === "UserCollectNotification")
                {
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("AvatarMix");        
                    $tempdata = $this->mongo_db->findOne(array("_id"=> $this->mongo_db->mongoid($dt['id'])));
                    $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                    $icontimeline = "icon-beaker";
                    $headertext = "Friend Collect your Mix ";
                    $contenttext = $tempdtuser['fullname']." Collect your Mix ".$tempdata["name"];
                    $colortext = "style='color:#b91f84;'";
                    $readmoretext = "";
                }
                echo "<div class='timeline-icon'><i class='".$icontimeline."' ".$colortext."></i></div>";
                echo "<div class='timeline-body'>";
                echo "<div class='timeline-header'>";
                echo "<span class='author'>Posted by <a href='#'>You</a></span>";
                echo "<span class='date'>".date('d M Y H:i:s',$dt['datetime']->sec)."</span>";
                echo "</div>";
                echo "<div class='timeline-content'>";
                echo "<h3>".$headertext."</h3>";
                echo "<p>".$contenttext."</p>";
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