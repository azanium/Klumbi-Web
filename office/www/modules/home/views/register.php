<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() { 
    $("#txtbirthday").datepicker({
        showOtherMonths: true,
        selectOtherMonths: true,
        showButtonPanel: true,
        changeMonth: true,
        changeYear: true,
        defaultDate: "+1w",
        dateFormat:"yy-mm-dd",
        yearRange: '1900:2010'
    });    
    $('#chkagrement').click(function () {
        var nilai = $("#chkagrement").is(':checked');
        if(nilai)
        {
            $('#tblsignup').attr("disabled", false);
        }
        else
        {
            $('#tblsignup').attr("disabled", true);
        }
    });
    $('#datepickerbtn').click(function () {
        $('#txtbirthday').datepicker("show");
    });
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
                            window.location = '<?php echo $this->template_admin->link("home/setting"); ?>';
                        }
                        else
                        {
                            message = "<div class='alert alert-dismissable alert-danger'>" +
                                        "<strong>Wrong!</strong> " + data['message'] +
					"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>" +
					"</div>";
                        }  
                        Recaptcha.reload();
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
    <div class="panel panel-primary" id="notifyform">&nbsp;</div>
    <div class="panel panel-primary">
        <div class="panel-body">
            <h4 class="text-center" style="margin-bottom: 25px;">Register new user</h4>
            <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/register/cekregis"); ?>">
                <div class="form-group">
                    <label for="txtemail" class="control-label col-sm-4" style="text-align: left;">Email</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-user"></i></span>
                            <input type="text" class="form-control {required:true, email:true}" id="txtemail" name="txtemail" placeholder="Email" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtusername" class="control-label col-sm-4" style="text-align: left;">Username</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" class="form-control {required:true}" id="txtusername" name="txtusername" placeholder="Username" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtname" class="control-label col-sm-4" style="text-align: left;">Fullname</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-hand-right"></i></span>
                            <input type="text" class="form-control {required:true}" id="txtname" name="txtname" placeholder="Your real name" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtbirthday" class="control-label col-sm-4" style="text-align: left;">Birthday</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="text" class="form-control {required:true}" readonly="true" id="txtbirthday" name="txtbirthday" placeholder="Birthday" />
                            <span class="input-group-addon" id="datepickerbtn"><i class="icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-4" style="text-align: left;">Gender</label>
                    <div class="col-sm-12">
                        <label for="male" class="radio-inline"><input type="radio" id="male" name="gender" value="male" checked="checked" /> Male</label>
                        <label for="female" class="radio-inline"><input type="radio" id="female" name="gender" value="female" /> Female</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtpassword" class="control-label col-sm-4" style="text-align: left;">Password</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control {required:true,minlength: 6}" id="txtpassword" name="txtpassword" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtpassword2" class="control-label col-sm-4" style="text-align: left;">Retype Password</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lock"></i></span>
                            <input type="password" class="form-control {required:true, equalTo:txtpassword}" id="txtpassword2" name="txtpassword2" placeholder="Password" />
                        </div>
                    </div>
                </div>                
                <div class="form-group">
                    <div class="col-sm-12">
                    <?php
                    $this->load->view("plugins/captcha");
                    ?>
                    </div>
                </div>
                <div class="block" style="margin-bottom: 25px;">
                    <label for="chkagrement"><input type="checkbox" name="chkagrement" id="chkagrement" /> I accept the</label> <span><a href='<?php echo $this->template_admin->link("home/term/index/faq"); ?>'>Term</a> &amp; <a href='<?php echo $this->template_admin->link("home/content/index/useragreement"); ?>'>User Agreement</a></span>
                </div>
                <button type="submit" disabled="true" id="tblsignup" class="btn btn-green btn-block"> Sign up</button>
            </form>
        </div>
        <div class="panel-footer">
            <a href="<?php echo $this->template_admin->link("home/login"); ?>" class="pull-left btn btn-link" style="padding-left:0">Login?</a>
            <div class="pull-right">
                <a href="#" class="btn btn-default resetform">Reset</a>
            </div>
        </div>
    </div>
 </div>	