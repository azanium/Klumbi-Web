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
                            message += "<div class='alert alert-dismissable alert-success'>" +
                                        "<strong>Success!</strong> ";
                            window.location = '<?php echo $this->template_admin->link("home/index"); ?>';
                        }
                        else
                        {                            
                            message += "<div class='alert alert-dismissable alert-danger'>" +
                                        "<strong>Wrong!</strong> ";
                        }  
                        message += data['message'] +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
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
            <?php
            if($user_valid)
            {
            ?>
            <h4 class="text-center" style="margin-bottom: 25px;">Type new password</h4>
            <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/loginbychange"); ?>">
                <div class="form-group">                    
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="hidden" value="<?php echo $user_email; ?>" name="txtemail" />
                            <input type="hidden" value="<?php echo $user_id; ?>" name="txtuserid" />
                            <input type="hidden" value="<?php echo $user_activation; ?>" name="txtactivationkey" />
                            <input type="hidden" value="<?php echo $user_name; ?>" name="txtusername" />
                            <b><?php echo $user_name; ?></b>
                        </div>
                    </div> 
                    <div class="col-sm-12">
                        <label for="txtpassword" class="control-label col-sm-4" style="text-align: left;">Password</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control {required:true,minlength:6}" id="txtpassword" name="txtpassword" placeholder="Password" />
                        </div>
                    </div>                    
                    <div class="col-sm-12">
                        <label for="txtpassword2" class="control-label col-sm-4" style="text-align: left;">Retype</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control {required:true, equalTo:txtpassword}" id="txtpassword2" name="txtpassword2" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Change</button>
            </form>
            <?php
            }
            else
            {
            ?>
            <div class='alert alert-dismissable alert-danger'>
                <p><i class="error">Your activation key is wrong,</i> try anotner activation <a href='<?php echo $this->template_admin->link("home/forgotpass"); ?>'>here</a></p>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
 </div>	