<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $("#chat").niceScroll({horizrailenabled:false, railoffset: {left:0}});
    $("#chat-listdatauser").niceScroll({horizrailenabled:false});
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });    
    $("#editbrandfrm").validate({
        submitHandler: function(form) {
            var url=$("#editbrandfrm").attr('action');
            var datapost=$("#editbrandfrm").serialize();
            $.ajax({
                type: "POST",
                url: url,
                data:datapost,
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
                    if(data['success'])
                    {                        
                        $("#txtsubject").val("");
                        $("#txtmessage").val("");
                        var idfriend = $("#txtidfrien").val();
                        reloadpagef(idfriend);                        
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
           return false;
        }
    });
});
function reloadpagef(idfriend)
{
    $("#txtidfrien").val(idfriend);
    $.ajax({
        type: "POST",
        url: "<?php echo $this->template_admin->link("inbox/message/listmesage"); ?>",
        data:{idfriend:idfriend},
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#chat").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>');
        },
        success: function (data, textStatus) {
            $("#txtmessage").val("");
            if(data['success']==true)
            {                        
                $("#chat").html(data['message']);
            }
            else
            {
                $("#chat").html('<div class="alert alert-dismissable alert-danger">' +
                                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' +
                                '<h3>Error found!</h3>' +
                                '<p>Something error happen while load the page, please reload this page.</p><hr />' +
                                '<p><a class="btn btn-danger" href="<?php echo current_url(); ?>">Okay</a></p>' +
                                '</div>');
            }               
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#chat").html(textStatus + (errorThrown ? errorThrown : xhr.status));
        }
    });
}
</script>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li><a href="<?php echo $this->template_admin->link("inbox/index"); ?>">Inbox</a></li>
        <li class="active">Message</li>
    </ol>
</div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4>Message Replay</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="row">
                        <div class="col-md-8">
                            <div tabindex="5000" style="overflow-y: hidden;max-height: 650px;" class="panel-chat well" id="chat"></div>
                            <form class="form-inline" method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("inbox/message/send"); ?>">
                                <div class="input-group">
                                    <div class="col-sm-4">
                                        <input type="hidden" name="txtidfrien" id="txtidfrien" value="<?php echo $iduser; ?>" />
                                        <input name="txtsubject" id="txtsubject" placeholder="Subject.." class="form-control" value="" maxlength="255" type="text">
                                    </div>
                                    <div class="col-sm-8">
                                        <input name="txtmessage" id="txtmessage" placeholder="Enter your message here.." class="form-control {required:true}" value="" maxlength="255" type="text">
                                    </div>                                    
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"><i class="icon-share-alt"></i> Replay</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="panel">
                                <div class="panel-body">
                                    <ul class="chat-users" style="max-height: 650px;" id="chat-listdatauser">
                                        <h4>Follower<small> (<?php echo $countfollower; ?>)</small></h4>
                                        <?php
                                        foreach($listfollower as $dt)
                                        {
                                            $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                                            $picture = base_url()."resources/image/index.jpg";
                                            if($tempdtuser['picture']=="")
                                            {
                                                if($tempdtuser['fb_id']!="")
                                                {
                                                    $picture = "https://graph.facebook.com/".$tempdtuser['fb_id']."/picture?type=large";//large,smaller,square
                                                }
                                            }
                                            else
                                            {
                                                $picture = $tempdtuser['picture'];
                                            }
                                            echo "<li data-stats='".$tempdtuser["status"]."'>";
                                            echo "<a href='#editbrandfrm' onclick='reloadpagef(\"".$dt["user_id"]."\");return false;'>";
                                            echo "<img src='".$picture."' alt='Avatar' />";
                                            echo "<span>".$tempdtuser["fullname"]."</span>";
                                            echo "</a>";
                                            echo "</li>";
                                        }
                                        ?>
                                        <hr />
                                        <h4>Following<small> (<?php echo $countfollowing; ?>)</small></h4>
                                        <?php
                                        foreach($listfollowing as $dt)
                                        {
                                            $tempdtuser = $this->m_userdata->user_properties($dt["friend_id"]);
                                            $picture = base_url()."resources/image/index.jpg";
                                            if($tempdtuser['picture']=="")
                                            {
                                                if($tempdtuser['fb_id']!="")
                                                {
                                                    $picture = "https://graph.facebook.com/".$tempdtuser['fb_id']."/picture?type=large";//large,smaller,square
                                                }
                                            }
                                            else
                                            {
                                                $picture = $tempdtuser['picture'];
                                            }
                                            echo "<li data-stats='".$tempdtuser["status"]."'>";
                                            echo "<a href='#editbrandfrm' onclick='reloadpagef(\"".$dt["friend_id"]."\");return false;'>";
                                            echo "<img src='".$picture."' alt='Avatar' />";
                                            echo "<span>".$tempdtuser["fullname"]."</span>";
                                            echo "</a>";
                                            echo "</li>";
                                        }
                                        ?>                                            
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
<!--                    <p align="right">
                        <a href="<?php echo $this->template_admin->link("inbox/index"); ?>" class="btn btn-sm btn-info"><i class="icon-arrow-left"></i> Back</a>
                    </p>-->
                </div>
            </div>
        </div>
    </div>
</div>