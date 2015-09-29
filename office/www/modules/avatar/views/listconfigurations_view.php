<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <?php
    $parameitem['loadgender'] = TRUE;
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
            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("avatar/configurations/cruid_avatar"); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="txtid" id="txtid" value="" />
                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                    <input type="hidden" name="txtgender" id="txtgender" value="<?php echo $genderdefault; ?>" />
                    <input type="hidden" name="txtskin" id="txtskin" value="<?php echo $skindefault['name']; ?>" />
                    <input type="hidden" name="txtsize" id="txtsize" value="<?php echo $sizedefault; ?>" />
                    <input type="hidden" name="hand" id="hand" value="<?php echo $handdefault['_id']; ?>" />
                    <input type="hidden" name="leg" id="leg" value="<?php echo $legdefault['_id']; ?>" />
                    <input type="hidden" name="eyes" id="eyes" value="<?php echo $eyesdefault['_id']; ?>" />
                    <input type="hidden" name="eyeBrows" id="eyeBrows" value="<?php echo $eyebrowsdefault['_id']; ?>" />
                    <input type="hidden" name="lip" id="lip" value="<?php echo $lipdefault['_id']; ?>" />
                    <input type="hidden" name="hair" id="hair" value="<?php echo $hairdefault['_id']; ?>" />
                    <input type="hidden" name="body" id="body" value="<?php echo $bodydefault['_id']; ?>" />                  
                    <input type="hidden" name="hat" id="hat" value="<?php echo $hatdefault['_id']; ?>" />
                    <input type="hidden" name="shoes" id="shoes" value="<?php echo $shoesdefault['_id']; ?>" />      
                    <input type="hidden" name="pants" id="pants" value="<?php echo $pantsdefault['_id']; ?>" />
                    <input type="hidden" name="gender" id="gender" value="<?php echo $anigenderdefault['_id']; ?>" /> 
                    <input type="hidden" name="face" id="face" value="<?php echo $facedefault['_id']; ?>" /> 
                    <label for="title" class="col-sm-3 control-label">Name</label>
                    <div class="col-sm-5">
                      <input type="text" name="title" id="title" value="" maxlength="255" placeholder="User Mix Name" class="form-control {required:true}" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-3 control-label">Description</label>
                    <div class="col-sm-5">
                      <textarea name="description" id="description" class="form-control" cols="55" rows="3"></textarea>
                    </div>
                </div>
                <?php
                if($this->session->userdata('brand')=="")
                {
                ?>
                <div class="form-group">
                    <label for="brand" class="col-sm-3 control-label">Brand</label>
                    <div class="col-sm-5">
                        <select id="brand" name="brand" class="form-control">
                            <option value="">&nbsp;</option>
                            <?php 
                            foreach($brand as $dt)
                            {
                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                            }
                            ?>
                       </select>
                    </div>
                </div>
                <?php
                }
                ?>
                <div class="form-group">
                    <label for="fileimage" class="col-sm-3 control-label">Preview Picture</label>
                    <div class="col-sm-3">
                        <span class="btn btn-success fileinput-button">
                            <i class="icon-plus"></i>
                            <span>Choose file...</span>
                            <input type="file" name="fileimage" id="fileimage" />
                        </span>
                    </div>
                    <div class="col-sm-2">
                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                        <p class="help-block">Image file of Avatar Mix!</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-3">&nbsp;</div>
                    <div class="col-sm-5">
                        <?php 
                        if($this->m_checking->actions("Avatar Configurations","module7","Add",TRUE,FALSE,"home") || $this->m_checking->actions("Avatar Configurations","module7","Edit",TRUE,FALSE,"home"))
                        {
                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                        }
                        ?>
                        <button type="reset" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
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
                    <h4>User Mix</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <div class="table-responsive table-flipscroll">
                        <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Title</th>
                                    <th width="10%">Gender</th>
                                    <th width="20%">Descriptions</th>
                                    <th width="10%">Brand</th>
                                    <th width="10%">Last Update</th>
                                    <th width="10%">Owner</th>
                                    <th width="10%">Picture</th>
                                    <th width="15%">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9">No Data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>                                    
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $('.select').select2({width: 'resolve'});
    $('#fileimage').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploader"); ?>',
        dataType: 'json',
        done: function (e, data) {
            if(data.result.success==true)
            {
                $.pnotify({
                    title: "Success",
                    text: "File success uploaded",
                    type: 'success'
                });
                $("#txtfileimgname").val(data.result.name);
                $("#filepreview").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $(".resetform").click(function(){
        document.forms["brandfrm"].reset();
        $('#txtid').val('');
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
    });
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
        "sAjaxSource": "<?php echo $this->template_admin->link("avatar/configurations/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, {"bSortable": false },{"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
                        $("input, textarea").val("");
                        document.forms["brandfrm"].reset();
                        $('#txtid').val('');
                        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
    var url='<?php echo base_url(); ?>avatar/configurations/detail_data/'+id;
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
            $("#txtid").val(data['data']['_id']);
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
            <?php
            if($this->session->userdata('brand')=="")
            {
            ?>
            $("#brand").val(data['data']['brand_id']);                
            <?php
            }
            ?>             
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
function overwritedefault(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>avatar/configurations/overwrite/'+id;
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
        }
        return false;
    });
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>avatar/configurations/delete/'+id;
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
        }
        return false;
    });
}
</script>