<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
$cek_user_data = $this->cek_session->get_default_datauser();
$cek_user_detail = $this->cek_session->get_detail_datauser($cek_user_data['_id']);
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
    $('#fileimageavatar').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimageavatar"); ?>',
        dataType: 'json',
        formData: {
            folder: 'preview_images/'
        },
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#datapicture").val(data.result.name);
                $("#filepreviewimage").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/' + data.result.name);
            }
            else
            {
                $.pnotify({
                    title: "Error Upload file",
                    text: data.result.message,
                    type: 'error'
                });
            }
        }
    });
    $("#txtbirthday").datepicker({
        dateFormat:"yy-mm-dd",
        defaultDate: +7,
        selectOtherMonths: true,
        autoSize: true,
        appendText: '(yyyy-mm-dd)'
    });
    $('#datepickerendbtn').click(function () {
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
    $("#cmbgender").val('<?php echo $cek_user_detail['sex']; ?>');
    $("#cmbbodysize").val('<?php echo $cek_user_detail['bodytype']; ?>');
});
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="panel panel-green">
        <div class="panel-heading">
            <h4>Edit User Profile</h4>
        </div>
        <form class="form-horizontal" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/setting/send"); ?>">
            <div class="panel-body">            
                <div class="form-group">
                    <label for="txtname" class="control-label col-sm-4" style="text-align: left;">Full Name</label>
                    <input type="hidden" name="datapicture" id="datapicture" value="" />
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-hand-right"></i></span>
                            <input type="text" class="form-control {required:true}" id="txtname" value="<?php echo $cek_user_detail['fullname']; ?>" name="txtname" placeholder="Full Name" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtavatarname" class="control-label col-sm-4" style="text-align: left;">Avatar Name</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon">@</span>
                            <input type="text" class="form-control {required:true}" id="txtavatarname" value="<?php echo $cek_user_detail['avatarname']; ?>" name="txtavatarname" placeholder="Avatar Name" />
                        </div>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label col-sm-4" style="text-align: left;">Status</label>
                    <div class="col-sm-12">
                          <label class="radio-inline" for="inlineradio1"><input type="radio" name="status" id="inlineradio1" value="online" <?php echo (($cek_user_detail['status']=="online")?"checked='checked'":""); ?> /> Online</label>
                          <label class="radio-inline" for="inlineradio2"><input type="radio" name="status" id="inlineradio2" value="busy" <?php echo (($cek_user_detail['status']=="busy")?"checked='checked'":""); ?> /> Busy</label>
                          <label class="radio-inline" for="inlineradio3"><input type="radio" name="status" id="inlineradio3" value="away" <?php echo (($cek_user_detail['status']=="away")?"checked='checked'":""); ?> /> Away</label>
                          <label class="radio-inline" for="inlineradio4"><input type="radio" name="status" id="inlineradio4" value="offline" <?php echo (($cek_user_detail['status']=="offline")?"checked='checked'":""); ?> /> Offline</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fileimageavatar" class="col-sm-4 control-label" style="text-align: left;">Picture</label>
                    <div class="col-sm-4">
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus"></i>
                            <span>Choose file...</span>
                            <input type="file" name="fileimageavatar" id="fileimageavatar" />
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <img id="filepreviewimage" src="<?php echo $picture; ?>" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                        <p class="help-block">Image Profile Avatar!</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtbirthday" class="control-label col-sm-4" style="text-align: left;">Birthday</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <input type="text" name="txtbirthday" id="txtbirthday" readonly="true" value="<?php echo $cek_user_detail['birthday']; ?>" maxlength="255" placeholder="Birthday" data-type="dateIso" class="form-control {required:true}" />
                            <span class="input-group-addon" id="datepickerendbtn"><i class="icon-calendar"></i></span>
                        </div>                                      
                    </div>
                </div>
                <div class="form-group">
                    <label for="cmbgender" class="control-label col-sm-4" style="text-align: left;">Gender</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-female"></i></span>
                            <select id="cmbgender" name="cmbgender" class="form-control">
                                <?php 
                                foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                {
                                    echo "<option value='".$dt."'>".$value."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cmbbodysize" class="control-label col-sm-4" style="text-align: left;">Body Size</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-lemon"></i></span>
                            <select id="cmbbodysize" name="cmbbodysize" class="form-control">
                                <option value="thin">Thin</option>
                                <option value="medium">Medium</option>
                                <option value="fat">Fat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtphone" class="control-label col-sm-4" style="text-align: left;">Phone</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-phone"></i></span>
                            <input type="text" class="form-control" id="txtphone" name="txtphone" placeholder="Phone Number" value="<?php echo $cek_user_detail['handphone']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtlocation" class="control-label col-sm-4" style="text-align: left;">Location</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-edit"></i></span>
                            <input type="text" class="form-control" id="txtlocation" name="txtlocation" placeholder="Location" value="<?php echo $cek_user_detail['location']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtsite" class="control-label col-sm-4" style="text-align: left;">Site</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-globe"></i></span>
                            <input type="text" class="form-control {url:true}" id="txtsite" name="txtsite" placeholder="http://" value="<?php echo $cek_user_detail['website']; ?>" />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtmessage" class="control-label col-sm-4" style="text-align: left;">State of mind</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <textarea name="txtmessage" id="txtmessage" cols="50" rows="4" class="form-control {required:true}"><?php echo $cek_user_detail['state_of_mind']; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="txtabout" class="control-label col-sm-4" style="text-align: left;">About</label>
                    <div class="col-sm-12">
                        <div class="input-group">
                            <textarea name="txtabout" id="txtabout" cols="50" rows="4" class="form-control {required:true}"><?php echo $cek_user_detail['about']; ?></textarea>
                        </div>
                    </div>
                </div>            
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-toolbar">
                            <button type="submit" class="btn-primary btn">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>