<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {    
    $("#formdata").validate({
        submitHandler: function(form) {
           var url=$("#formdata").attr('action');
           var datapost=$("#formdata").serialize();
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
                        $("#notifyform").html("");
                    },
                    error: function (xhr, textStatus, errorThrown) {
                           $("#loadingprocess").slideUp(100);
                           $("#notifyform").html(
                                "<div class='alert alert-dismissable alert-danger'>" +
                                "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
                                textStatus + " " + xhr.status + ": " + (errorThrown ? errorThrown : xhr.status) +
                                "</div>"
                            );
                    },
                    success: function (data, textStatus) {
                        $("#loadingprocess").slideUp(100);
                        var message = "";
                        if(data['login']==true)
                        {                            
                            message = "<div class='alert alert-dismissable alert-success'>" +
                                        "<strong>Success!</strong> You successfully Login." +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                            window.location = '<?php echo $this->session->userdata('urlsebelumnya'); ?>';
                        }
                        else
                        {
                            $("input, textarea").val("");
                            message = "<div class='alert alert-dismissable alert-danger'>" +
                                        "<strong>Wrong!</strong> " + data['message'] +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                        }  
                        $("#notifyform").html(message);
                    }
                });
            return false;
        }
    });
});
</script>
<div class="verticalcenter">
    <img src="<?php echo base_url(); ?>resources/image/logo/page-login.png" alt="Logo" class="brand" />
    <div id="notifyform">&nbsp;</div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <h4 class="text-center" style="margin-bottom: 25px;">Log in to get started</h4>
            <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/cek_login"); ?>">
                <div class="form-group">
                    <label for="txtemail" class="control-label col-sm-4" style="text-align: left;">Email</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" class="form-control {required:true}" id="txtemail" name="txtemail" placeholder="Email" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtpassword" class="control-label col-sm-4" style="text-align: left;">Password</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control {required:true}" id="txtpassword" name="txtpassword" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="pull-right"><label><input type="checkbox" checked="checked" /> Remember Me</label></div>
                </div>
                <button type="submit" class="btn btn-green btn-block"> Login</button>
                <a href="<?php echo $this->template_admin->link("home/sociallog/loginbytwitter"); ?>" class="btn btn-info btn-block"><i class="icon-twitter-sign"></i> Login by Twitter</a>
                <a href="<?php echo $this->template_admin->link("home/sociallog"); ?>" class="btn btn-primary btn-block"><i class="icon-facebook-sign"></i> Login by Facebook</a>
            </form>
        </div>
        <div class="panel-footer">
            <a href="<?php echo $this->template_admin->link("home/welcome"); ?>" class="pull-left btn btn-link" style="padding-left:0">Home?</a>
            <a href="<?php echo $this->template_admin->link("home/forgotpass"); ?>" class="pull-left btn btn-link" style="padding-left:0">Forgot password?</a>
            <div class="pull-right">
                <a href="<?php echo $this->template_admin->link("home/register"); ?>" class="btn btn-magenta">Register</a>
                <a href="#" class="btn btn-default resetform">Reset</a>
            </div>
        </div>
    </div>
 </div>	