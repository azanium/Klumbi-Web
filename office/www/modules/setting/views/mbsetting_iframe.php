<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Template</h4>
            </div>
            <div class="modal-body">
                <?php echo "<p>".$descriptions."</p>"; ?>
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("setting/mbsetting/cruid_setting"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>" />
                                <input type="hidden" name="txttype" id="txttype" value="<?php echo $type; ?>" />
                                <label for="name"><?php echo $name; ?></label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name" value="<?php echo $name; ?>" maxlength="255" placeholder="Setting Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <?php 
                        if($type=="number")
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td>";
                            echo "<input type='number' name='txtvalue' id='txtvalue' step='1' min='5' max='1000' value='".$value."' maxlength='255' placeholder='Setting Value' class='form-control {required:true, number:true, ".$format."}' />";
                            echo "</td>";
                            echo "</tr>";
                        }
                        else if($type=="html")
                        {
                            echo "<tr>";
                            echo "<td colspan='2'><textarea name='txtvalue' id='".$classword."' class='".$format."' cols='45' rows='10'>".$value."</textarea></td>";
                            echo "</tr>";
                        }
                        else if($type=="textarea")
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td>";
                            echo "<textarea name='txtvalue' id='txtvalue' class='form-control {required:true, ".$format."}' cols='45' rows='10'>".$value."</textarea>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        else if($type=="color")
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td><input type='text' name='txtvalue' id='txtvalue' value='".$value."' maxlength='255' placeholder='Color' class='form-control cpicker {required:true}' /></td>";
                            echo "</tr>";
                        }
                        else if($type=="url")
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td><input type='url' name='txtvalue' id='txtvalue' value='".$value."' maxlength='255' placeholder='http://' class='form-control {required:true,url:true}' /></td>";
                            echo "</tr>";
                        }
                        else if($type=="email")
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td><input type='email' name='txtvalue' id='txtvalue' value='".$value."' maxlength='255' placeholder='Email' class='form-control {required:true, email:true}' /></td>";
                            echo "</tr>";
                        }
                        else
                        {
                            echo "<tr>";
                            echo "<td><label for='txtvalue'>Value</label></td>";
                            echo "<td>";
                            echo "<input type='text' name='txtvalue' id='txtvalue' value='".$value."' maxlength='255' placeholder='Setting Value' class='form-control {required:true, ".$format."}' />";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($type!="html")
                                {
                                    echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Update</span></button>&nbsp;&nbsp;'; 
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>resources/plugin/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('.cpicker').colorpicker();
    $('#editdata').modal('show');
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
                    if(data['success']==true)
                    {                        
                        $.pnotify({
                            title: "Success",
                            text: data['message'],
                            type: 'success'
                        });
                        $('#editdata').modal('hide');
                        $("input, textarea").val("");
                    }
                    else
                    {
                        $.pnotify({
                            title: "Fail",
                            text: data['message'],
                            type: 'info'
                        });
                    }
                    dttable.fnClearTable(0);
                    dttable.fnDraw();                       
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
    <?php
    if($type=="html")
    {
    ?>
    CKEDITOR.replace('<?php echo $classword; ?>',{
        fullPage : true
    });    
    <?php
    }
    ?>    
});
</script>