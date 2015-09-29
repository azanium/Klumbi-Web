<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <?php
    $parameitem['loadgender'] = FALSE;
    $this->load->view("unity_object/item",$parameitem);
    ?>
    <hr />
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-midnightblue">
                <div class="panel-heading">
                    <h4>Preview Avatar</h4>
                </div>
                <div class="panel-body">
                    <div align="center" class="text-center">
                        <?php
                        $property['unity_object']='_DressRoom';
                        $property['unity_error_image']='getunity.png';
                        $property['width']=300;;
                        $property['height']=456;        
                        $property['maps']='';
                        $property['url_unity']=$this->config->item('path_asset_img').'webplayer/AvatarEditor.unity3d?'.time();
                        $this->load->view("unity_object/loadunity",$property);
                        ?>
                    </div>
                </div>
            </div>            
        </div>
        <div class="col-md-8">
            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("avatar/defaultavatar/cruid_avatar"); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="txtid" id="txtid" value="" />
                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                    <input type="hidden" name="txtgender" id="txtgender" value="<?php echo $genderdefault; ?>" />
                    <input type="hidden" name="txtskin" id="txtskin" value="<?php echo $skindefault['name']; ?>" />
                    <input type="hidden" name="txtsize" id="txtsize" value="<?php echo $sizedefault; ?>" />
                    <input type="hidden" name="hand" id="hand" value="<?php echo (string)$handdefault['_id']; ?>" />
                    <input type="hidden" name="leg" id="leg" value="<?php echo (string)$legdefault['_id']; ?>" />
                    <input type="hidden" name="eyes" id="eyes" value="<?php echo (string)$eyesdefault['_id']; ?>" />
                    <input type="hidden" name="eyeBrows" id="eyeBrows" value="<?php echo (string)$eyebrowsdefault['_id']; ?>" />
                    <input type="hidden" name="lip" id="lip" value="<?php echo (string)$lipdefault['_id']; ?>" />
                    <input type="hidden" name="hair" id="hair" value="<?php echo (string)$hairdefault['_id']; ?>" />
                    <input type="hidden" name="body" id="body" value="<?php echo (string)$bodydefault['_id']; ?>" />                  
                    <input type="hidden" name="hat" id="hat" value="<?php echo (string)$hatdefault['_id']; ?>" />
                    <input type="hidden" name="shoes" id="shoes" value="<?php echo (string)$shoesdefault['_id']; ?>" />      
                    <input type="hidden" name="pants" id="pants" value="<?php echo (string)$pantsdefault['_id']; ?>" /> 
                    <input type="hidden" name="gender" id="gender" value="<?php echo (string)$anigenderdefault['_id']; ?>" /> 
                    <input type="hidden" name="face" id="face" value="<?php echo (string)$facedefault['_id']; ?>" /> 
                    <label for="title" class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-5">
                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-5">
                      <textarea name="description" id="description" class="form-control" cols="55" rows="3"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">&nbsp;</div>
                    <div class="col-sm-5">
                        <?php 
                        if($this->m_checking->actions("Default Avatar","module7","Edit",TRUE,FALSE,"home"))
                        {
                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                        }
                        ?>
                    </div>
                </div>
            </form>            
        </div>
    </div>
    <hr />    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Default Configurations</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Title</th>
                                <th width="15%">Gender</th>
                                <th width="40%">Descriptions</th>
                                <th width="5%">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">No Data</td>
                            </tr>
                        </tbody>
                    </table>                                      
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("avatar/defaultavatar/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $("#brandfrm").validate({
        submitHandler: function(form) {
            var url=$("#brandfrm").attr('action');
            var datapost=$("#brandfrm").serialize();
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
});
function ubahdata(id)
{
    var url='<?php echo base_url(); ?>avatar/defaultavatar/detail_data/'+id;
    $.ajax({
        type: "POST",
        url: url,
        data:"",
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
            $("#title").val(data['data']['name']);
            $("#txtgender").val(data['data']['gender']); 
            $("#description").val(data['data']['description']);
            $("#txtsize").val(data['data']['size']);
            $("#txtskin").val(data['data']['dataconf']['skin']);            
            $("#hand").val(data['data']['dataconf']['Hand']);            
            $("#leg").val(data['data']['dataconf']['Leg']);
            $("#eyes").val(data['data']['dataconf']['Eyes']);
            $("#eyeBrows").val(data['data']['dataconf']['EyeBrows']);
            $("#lip").val(data['data']['dataconf']['Lip']);  
            $("#hair").val(data['data']['dataconf']['Hair']); 
            $("#body").val(data['data']['dataconf']['Body']);  
            $("#hat").val(data['data']['dataconf']['Hat']);
            $("#shoes").val(data['data']['dataconf']['Shoes']);  
            $("#pants").val(data['data']['dataconf']['Pants']);  
            $("#gender").val(data['data']['dataconf']['Gender']);
            $("#face").val(data['data']['dataconf']['Face']);
            try
            {
                var data_message = data['data']['configurations'];
                GetUnity().SendMessage("_DressRoom", "ChangeCharacterEvent", data_message);
            }
            catch(error){
                $.pnotify({
                    title: "Error Setting Avatar",
                    text: "Fail to set avatar Configurations",
                    type: 'error'
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
}
</script>