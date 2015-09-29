<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script language="javascript">
$(document).ready(function() { 
    setInterval(function(){
        $.ajax({
            type: "GET",
            url: "<?php echo $this->template_admin->link("inbox/notification/message"); ?>",
            dataType: 'json',
            data: "",
            success: function(data, textStatus){
                 if(data['success'])
                 {
                     $("#loadingprocess").slideUp(100);
                     $("#listdatamesage").html("");
                     if(data['count']>0)
                     {
                         $("#mesagecountdb").html("<i class='icon-envelope'></i><span  class='badge'>" + data['count'] + "</span>");
                         $("#listdatamesage").prepend("<li><span>You have " + data['count'] + " new message(s)</span><span><a href='#' onclick='setread();return false;'>Mark all Read</a></span></li>");
                         $("#inboxmsg").html("<span class='badge badge-primary'>" + data['count'] + "</span>Inbox");
                     }
                     else
                     {
                         $("#mesagecountdb").html("<i class='icon-envelope'></i>");
                         $("#listdatamesage").prepend("<li><span>You have no new message</span><span><a href='<?php echo $this->template_admin->link("inbox/index"); ?>'>Open All Messages</a><span></li>");
                         $("#inboxmsg").html("Inbox");
                     }
                     $("#listdatamesage").append(data['contentpage']);
                     $("#listdatamesage").append("<li><a class='dd-viewall' href='<?php echo $this->template_admin->link("inbox/index"); ?>'>View All Messages</a></li>");
                 }
                 else
                 {
                     $("#loadingprocess").html('<div class="alert alert-dismissable alert-danger">' +
                                            '<strong>Error!</strong> ' +
                                            '<i class="process">Fail load data, Check your connection and reload page.</i>' +
                                            '</div>').slideDown(100);
                 }
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#loadingprocess").html((errorThrown ? errorThrown : xhr.status)).slideDown(100);
            }
         });
    },15000);
    setInterval(function(){
        $.ajax({
            type: "GET",
            url: "<?php echo $this->template_admin->link("inbox/notification/index"); ?>",
            dataType: 'json',
            data: "",
            success: function(data, textStatus){
                 if(data['success'])
                 {
                     $("#loadingprocess").slideUp(100);
                     $("#listdatanotification").html("");
                     if(data['count']>0)
                     {
                         $("#notificationpanel").html("<i class='icon-bell-alt'></i><span  class='badge'>" + data['count'] + "</span>");
                         $("#listdatanotification").prepend("<li><span>You have " + data['count'] + " new notification(s)</span><span><a href='#' onclick='setsee();return false;'>Mark all Seen</a></span></li>");
                     }
                     else
                     {
                         $("#notificationpanel").html("<i class='icon-bell-alt'></i>");
                         $("#listdatanotification").prepend("<li><span>You have no new notification</span><span><a href='<?php echo $this->template_admin->link("member/timeline/index"); ?>'>Open All Notifications</a><span></li>");
                     }
                     $("#listdatanotification").append(data['contentpage']);
                     $("#listdatanotification").append("<li><a class='dd-viewall' href='<?php echo $this->template_admin->link("member/timeline/index"); ?>'>View All Notifications</a></li>");
                 }
                 else
                 {
                     $("#loadingprocess").html('<div class="alert alert-dismissable alert-danger">' +
                                            '<strong>Error!</strong> ' +
                                            '<i class="process">Fail load data, Check your connection and reload page.</i>' +
                                            '</div>').slideDown(100);
                 }
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#loadingprocess").html((errorThrown ? errorThrown : xhr.status)).slideDown(100);
            }
         });
    },10000);
});
function setread()
{
    $.ajax({
        type: "GET",
        url: "<?php echo $this->template_admin->link("inbox/notification/setread"); ?>",
        data:"",
        dataType: "json",
        success: function (data, textStatus) {
            if(data['success'])
            {
                $("#loadingprocess").slideUp(100);
            }                                  
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#loadingprocess").html((errorThrown ? errorThrown : xhr.status)).slideDown(100);
        }
    });
}
function setsee()
{
    $.ajax({
        type: "GET",
        url: "<?php echo $this->template_admin->link("inbox/notification/setsee"); ?>",
        data:"",
        dataType: "json",
        success: function (data, textStatus) {
            if(data['success'])
            {
                $("#loadingprocess").slideUp(100);
            }                                  
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#loadingprocess").html((errorThrown ? errorThrown : xhr.status)).slideDown(100);
        }
    });
}
</script>