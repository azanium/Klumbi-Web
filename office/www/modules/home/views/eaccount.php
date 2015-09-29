<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
$cek_user_data = $this->cek_session->get_default_datauser();
$cek_user_detail = $this->cek_session->get_detail_datauser($cek_user_data['_id']);
$chkemail = $cek_user_detail['hide_email']; 
$chksex = $cek_user_detail['hide_sex']; 
$chkphonenumber = $cek_user_detail['hide_phone']; 
$optbirthdayshow = $cek_user_detail['show_birthday']; 
$chkeventupdate = $cek_user_detail['notification_event']; 
$chkfrienreq = $cek_user_detail['notification_friend_request']; 
$chkmention = $cek_user_detail['notification_mention']; 
$chkpostfriend = $cek_user_detail['notification_friend_post_data']; 
$chklove = $cek_user_detail['notification_friend_love_data']; 
$mixcollect = $cek_user_detail['notification_mix_collected'];  
$chkcomment = $cek_user_detail['notification_friend_comment'];  
$chkavaitems = $cek_user_detail['notification_new_avataritem']; 
$chkcontest = $cek_user_detail['notification_contest_result']; 
$chkmessage = $cek_user_detail['friend_send_message']; 
?>
<script type="text/javascript">
$(document).ready(function() {
    $("#linkopenaccount").click(function(){
        $.ajax({
            type: "GET",
            url: $(this).attr("href"),
            data:"",
            dataType: "html",
            error: function (xhr, textStatus, errorThrown) {
                   $.pnotify({
                        title: textStatus + " " + xhr.status,
                        text: (errorThrown ? errorThrown : xhr.status),
                        type: 'error'
                    });
            },
            success: function (data, textStatus) {
                $("#iframeeditaccount").html(data);
            }
        });
        return false;
    });
    $("#brandfrmsettingdata").validate({
        submitHandler: function(form) {
            var url=$("#brandfrmsettingdata").attr('action');
            var datapost=$("#brandfrmsettingdata").serialize();
            $.ajax({
                type: "GET",
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
                    if(data['success']==true)
                    {                        
                        $.pnotify({
                            title: "Success",
                            text: data['message'],
                            type: 'success'
                        });
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
           return false;
        }
    });
});
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="panel panel-green">
        <div class="panel-heading">
            <h4>Security Account Setting</h4>
        </div>
        <div class="panel-body">
            <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>resources/css/according.css" />
            <div id="accordioninpanel" class="accordion-group">            
                <div class="accordion-item">
                    <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein1"><h4>Account</h4></a>
                    <div id="collapsein1" class="in">
                        <div id="iframeeditaccount">
                            <div class="panel">
                                <ul class="list-group">
                                    <li class="list-group-item"><i class="icon-envelope"></i> <?php echo $cek_user_data['email']; ?></li>
                                    <li class="list-group-item"><i class="icon-user"></i> <?php echo $cek_user_data['username']; ?></li>
                                    <li class="list-group-item"><a id="linkopenaccount" href="<?php echo $this->template_admin->link("home/eaccount/openmyaccount"); ?>" class="btn btn-magenta btn-lg">Edit Account</a></li>
                                </ul>
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="accordion-item">
                    <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein2"><h4>Setting</h4></a>
                    <div id="collapsein2" class="collapse">
                        <form method="GET" id="brandfrmsettingdata" action="<?php echo $this->template_admin->link("api/user/setting_account"); ?>">
                            <input type="hidden" name="user_id" id="user_id" value="<?php echo (string)$this->session->userdata('user_id'); ?>" />
                            <div class="panel">
                                <ul class="list-group">
                                    <li class="list-group-item"><label class="radio-inline"><input type="radio" name="chkemail" value="1" <?php echo (($chkemail==TRUE)?"checked='checked'":""); ?> /> Hide Email</label> <label class="radio-inline"><input type="radio" name="chkemail" value="0" <?php echo (($chkemail==FALSE)?"checked='checked'":""); ?> /> Show Email</label></li>
                                    <li class="list-group-item"><label class="radio-inline"><input type="radio" name="chksex" value="1" <?php echo (($chksex==TRUE)?"checked='checked'":""); ?> /> Hide Gender</label> <label class="radio-inline"><input type="radio" name="chksex" value="0" <?php echo (($chksex==FALSE)?"checked='checked'":""); ?> /> Show Gender</label></li>
                                    <li class="list-group-item"><label class="radio-inline"><input type="radio" name="chkphonenumber" value="1" <?php echo (($chkphonenumber==TRUE)?"checked='checked'":""); ?> /> Hide Phone Number</label><label class="radio-inline"><input type="radio" name="chkphonenumber" value="0" <?php echo (($chkphonenumber==FALSE)?"checked='checked'":""); ?> /> Show Phone Number</label></li>
                                    <li class="list-group-item"><label class="radio-inline"><input type="radio" name="optbirthdayshow" value="showall" <?php echo (($optbirthdayshow==="showall")?"checked='checked'":""); ?> /> Show Birthday</label><label class="radio-inline"><input type="radio" name="optbirthdayshow" value="hideyear" <?php echo (($optbirthdayshow==="hideyear")?"checked='checked'":""); ?> /> Hide Year</label> <label class="radio-inline"><input type="radio" name="optbirthdayshow" value="hideall" <?php echo (($optbirthdayshow==="hideall")?"checked='checked'":""); ?> /> Hide All</label></li>                                   
                                    <li class="list-group-item">Send Notification Event Update <label class="radio-inline"><input type="radio" name="chkeventupdate" value="1" <?php echo (($chkeventupdate==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkeventupdate" value="0" <?php echo (($chkeventupdate==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Friend Request <label class="radio-inline"><input type="radio" name="chkfrienreq" value="1" <?php echo (($chkfrienreq==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkfrienreq" value="0" <?php echo (($chkfrienreq==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Mention of me <label class="radio-inline"><input type="radio" name="chkmention" value="1" <?php echo (($chkmention==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkmention" value="0" <?php echo (($chkmention==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Post from Friends <label class="radio-inline"><input type="radio" name="chkpostfriend" value="1" <?php echo (($chkpostfriend==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkpostfriend" value="0" <?php echo (($chkpostfriend==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Love <label class="radio-inline"><input type="radio" name="chklove" value="1" <?php echo (($chklove==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chklove" value="0" <?php echo (($chklove==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Mix Collected <label class="radio-inline"><input type="radio" name="mixcollect" value="1" <?php echo (($mixcollect==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="mixcollect" value="0" <?php echo (($mixcollect==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Comment <label class="radio-inline"><input type="radio" name="chkcomment" value="1" <?php echo (($chkcomment==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkcomment" value="0" <?php echo (($chkcomment==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification New Avatar Items <label class="radio-inline"><input type="radio" name="chkavaitems" value="1" <?php echo (($chkavaitems==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkavaitems" value="0" <?php echo (($chkavaitems==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Contest Result <label class="radio-inline"><input type="radio" name="chkcontest" value="1" <?php echo (($chkcontest==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkcontest" value="0" <?php echo (($chkcontest==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item">Send Notification Message <label class="radio-inline"><input type="radio" name="chkmessage" value="1" <?php echo (($chkmessage==TRUE)?"checked='checked'":""); ?> /> Yes</label><label class="radio-inline"><input type="radio" name="chkmessage" value="0" <?php echo (($chkmessage==FALSE)?"checked='checked'":""); ?> /> No</label></li>
                                    <li class="list-group-item"><button type="submit" class="btn-green btn btn-lg"> <i class="icon-save"></i> <span>Save</span></button></li>
                                </ul>
                            </div>             
                        </form>
                    </div>
                </div>
                <div class="accordion-item">
                    <a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein3"><h4>Deactivated Account</h4></a>
                    <div id="collapsein3" class="collapse">
                        <div class="accordion-body">
                            <h4>How do I deactivate my account?</h4>
                            <p>Deactivate to turn off your account for as long as you want:</p>
                            <ol>
                                <li>Click the account menu <i class="icon-caret-down"></i> at the top right of any <?php echo $this->config->item('aplicationname'); ?> page</li>
                                <li>Select Accounts</li>
                                <li>Click Security in the left column</li>
                                <li>Deactivated Account</li>
                                <li>Click Deactivate your account</li>
                            </ol>
                            <p class="text-center"><a href="<?php echo $this->template_admin->link("home/deactivated/index"); ?>" class="btn btn-midnightblue btn-lg">Deactivated Account</a></p>
                            <p>When you deactivate your account, your Timeline and all information associated with it disappears from <?php echo $this->config->item('aplicationname'); ?> immediately. People on <?php echo $this->config->item('aplicationname'); ?> will not be able to search for you or view any of your information.</p>
                            <p>If you’d like to come back to <?php echo $this->config->item('aplicationname'); ?> anytime after you’ve deactivated your account, you can reactivate your account by logging in with your email and password. Your Timeline will be restored in its entirety (ex: friends, photos and interests). Remember that you will need to have access to the login email address for your account in order to reactivate it.</p>
                            <p>To permanently delete your account:</p>
                            <p>Permanently deleting your account means you will <strong>not ever</strong> be able to reactivate or retrieve any of the content or information you've added. If you would like your account permanently deleted with no option for recovery, please <a href="<?php echo $this->template_admin->link("home/contact"); ?>">contact us</a>.</p>
                            <p><code>Note:</code> Facebook does not use content associated with accounts that have been deactivated or deleted.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>