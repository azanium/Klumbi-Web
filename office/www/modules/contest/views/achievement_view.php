<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve'});
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $( "#getrewardid_1 , #getrewardid_12" ).click(function() { 
        $("#innerframedialogreward").modal('show');
        return false; 
    });
    $( "#getrewardid_2, #getrewardid_22" ).click(function() { 
        $("#innerframedialogreward2").modal('show');
        return false; 
    });
    $( "#getrewardadd" ).click(function() { 
        var jml=$("#countreward").val();
        var kode=$("#cmbtiperewardcode").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"reward";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='rewardpilih[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removeoption(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrewardadd").append(content);
            $("#countreward").val('1');
        }
        return false; 
    });
    $( "#getrewardadd2" ).click(function() { 
        var jml=$("#countreward2").val();
        var kode="x:"+$("input[name='avatarid']:checked").val();
        if(jml!="" && kode!=null)
        {
            var id="rnd_"+Math.floor((Math.random()*10000)+1)+"reward2";
            var content="<p id='"+id+"'>";
            content +="<input type='hidden' name='rewardpilih2[]' value='"+kode+","+jml+"' />";
            content +="<a href='#' onclick='removeoption(\""+id+"\");return false;' ><i class='icon-remove-circle'></i> Remove</a>";
            content +="&nbsp;&nbsp;&nbsp;"+kode+","+jml;
            content +="</p>";
            $("#listrewardadd2").append(content);
            $("#countreward2").val('1');
        }
        return false; 
    });
    $("#tblsetreward").click(function(){
        var valuetext="";
        $('input[name="rewardpilih[]"]').each(function(){                                
            valuetext=valuetext+$(this).val()+"|";
          });
        valuetext=valuetext.substring(0,valuetext.length-1);
        var texttamp=$.trim($("#rewards").val());
        if(texttamp!='')
        {
            $("#rewards").val(texttamp+"|"+valuetext);
            $("#rewards2").val(texttamp+"|"+valuetext);
        }
        else
        {
            $("#rewards").val(valuetext);
            $("#rewards2").val(valuetext);
        }
        $("#listrewardadd").html('');
        $("#countreward").val('1')
        $("#innerframedialogreward").modal('hide');
    });
    $("#tblsetreward_2").click(function(){
        var valuetext="";
        $('input[name="rewardpilih2[]"]').each(function(){                                
            valuetext=valuetext+$(this).val()+"|";
          });
        valuetext=valuetext.substring(0,valuetext.length-1);
        var texttamp=$.trim($("#rewards").val());
        if(texttamp!='')
        {
            $("#rewards").val(texttamp+"|"+valuetext);
            $("#rewards2").val(texttamp+"|"+valuetext);
        }
        else
        {
            $("#rewards").val(valuetext);
            $("#rewards2").val(valuetext);
        }                            
        $("#listrewardadd2").html('');
        $("#countreward2").val('1')
        $("#innerframedialogreward2").modal('hide');
    });
    $('#fileimagedefault').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagedefault"); ?>',
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
                $("#txticondefault").val(data.result.name);
                $("#filepreviewdefault").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimagedefault2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimagedefault"); ?>',
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
                $("#txticondefault2").val(data.result.name);
                $("#filepreviewdefault2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimageactive').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimageactive"); ?>',
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
                $("#txticonactive").val(data.result.name);
                $("#filepreviewactive").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    $('#fileimageactive2').fileupload({
        url: '<?php echo $this->template_admin->link("services/api/uploaderwithpath/fileimageactive"); ?>',
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
                $("#txticonactive2").val(data.result.name);
                $("#filepreviewactive2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("contest/achievement/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
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
    $(".resetform").click(function(){
        $("#brandfrm input, #brandfrm textarea").val("");
        $("#filepreviewdefault").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreviewactive").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
    });
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
                        $("#brandfrm input, #brandfrm textarea").val("");
                        $("#filepreviewdefault").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                        $("#filepreviewactive").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
                        $("#editbrandfrm input, #editbrandfrm textarea").val("");
                        $("#brandfrm input, #brandfrm textarea").val("");
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
    $("#filtersearch").click(function(){
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->template_admin->link("services/api/get_list_avatar_bysearch"); ?>',
            data: {'brand':$("#brand").val(), 'tipe':$("#tipe").val(), 'gender':$("#gender").val(), 'size':$("#size").val()},
            dataType: 'json',
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
            },
            success: function(data, textStatus) {
                $("#loadingprocess").slideUp(100);
                if(data['success']==true)
                {                        
                    if(data['data'].length>0)
                    {
                        var texthtml="";
                        var pathdownload="<?php echo $this->config->item('path_asset_img'); ?>preview_images/";
                        for(var i=0; i<data['data'].length;i++)
                        {
                            texthtml += "<input type='radio' name='avatarid' value='" + data['data'][i]._id + "' id='radio" + i + "' />";
                            texthtml += "<label for='radio" + i + "' >";
                            texthtml += data['data'][i].name + "<br />";
                            texthtml += "<img src='" + pathdownload + data['data'][i].preview_image + "' alt='" + data['data'][i].name + "' width='70' height='90'/>";
                            texthtml += "</label>";
                        }
                        $("#listdataavatar").html(texthtml);
                    }
                    else
                    {
                        $("#listdataavatar").html(data['message']);
                    }
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
    });
});
function ubahdata(id)
{
    $.ajax({
        type: "POST",
        url: "<?php echo $this->template_admin->link("contest/achievement/detail"); ?>",
        data:{txtid:id},
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
                $('#txtid2').val(data['id']);
                $('#txticondefault2').val(data['icon_default']);
                $('#txticonactive2').val(data['icon_active']);
                $('#name2').val(data['name']);
                $('#rewards2').val(data['rewards']);                              
                $('#txtpoint2').val(data['point']);
                $('#descriptions2').val(data['description']);
                $("#filepreviewdefault2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data['icon_default']);
                $("#filepreviewactive2").attr("src", '<?php echo $this->config->item('path_asset_img')."preview_images/"; ?>' + data['icon_active']);
                $('#cmbstate2').val(data['state']); 
                $('#brand2').val(data['brand_id']);
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>contest/achievement/delete/'+id;
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
                            $("#brandfrm input, #brandfrm textarea").val("");
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
function reload_codereward(id)
{
    var url='<?php echo base_url(); ?>services/listreward/get_required_id/'+id;
    $.ajax({
        type: "POST",
        url: url,
        data:"",
        dataType: "html",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>').slideDown(100);
            $("#cmbtiperewardcode").html("");
        },
        success: function (data, textStatus) {
            $("#loadingprocess").slideUp(100); 
            $("#cmbtiperewardcode").html(data);
            $("#countreward").val('1');
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
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Achievement</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Achievements</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Achievement</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">
                            <p align="right">
                                <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                            </p>
                            <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Name</th>
                                        <th width="30%">Descriptions</th>
                                        <th width="10%">Point</th>
                                        <th width="10%">Store</th>
                                        <th width="10%">State</th>
                                        <th width="10%">Picture</th>
                                        <th width="10%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="8">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("contest/achievement/cruid_achievement"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txticondefault" id="txticondefault" value="" />
                                    <input type="hidden" name="txticonactive" id="txticonactive" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="descriptions" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-6">
                                        <textarea name="descriptions" id="descriptions" class="form-control autosize" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtpoint" class="col-sm-3 control-label">Point</label>
                                    <div class="col-sm-6">                                      
                                      <input type="number" name="txtpoint" id="txtpoint" value="10" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Point of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="rewards" class="col-sm-3 control-label">Rewards</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="rewards" id="rewards" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="getrewardid_1" type="button" class="btn-sky btn"><i class="icon-unlock"></i> <span>Val</span></button>
                                        <button id="getrewardid_2" type="button" class="btn-inverse btn"><i class="icon-legal"></i> <span>Item</span></button>
                                    </div>
                                </div>
                                <?php
                                if($this->session->userdata('brand')=="")
                                {
                                ?>
                                <div class="form-group">
                                    <label for="brand" class="col-sm-3 control-label">Store</label>
                                    <div class="col-sm-6">
                                        <select id="brand" name="brand" class="select" style="width:100%">
                                            <option value="">&nbsp;</option>
                                            <?php 
                                            foreach($brand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                       </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Store of Achievement!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="fileimagedefault" class="col-sm-3 control-label">Icon Default</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-midnightblue fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimagedefault" id="fileimagedefault" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewdefault" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image Default of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimageactive" class="col-sm-3 control-label">Icon Active</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-magenta fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimageactive" id="fileimageactive" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreviewactive" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image Active of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cmbstate" class="col-sm-3 control-label">State</label>
                                    <div class="col-sm-6">
                                        <select id='cmbstate' name='cmbstate' class='form-control'>
                                            <?php
                                            foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">State of Achievement!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Achievement","module5","Add",TRUE,FALSE,"home"))
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
                </div>
            </div>
        </div>
    </div>
</div>
<div id="editdata" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("contest/achievement/cruid_achievement"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txticondefault" id="txticondefault2" value="" />
                                <input type="hidden" name="txticonactive" id="txticonactive2" value="" />
                                <label for="name2">Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="descriptions2">Descriptions</label>
                            </td>
                            <td>
                                <textarea name="descriptions" id="descriptions2" class="form-control autosize" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="txtpoint2">Point</label>
                            </td>
                            <td>
                                <input type="number" name="txtpoint" id="txtpoint2" value="10" step="1" min="0" max="1000000" maxlength="255" class="form-control {required:true, number:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="rewards2">Rewards</label></td>
                            <td>
                                <div class="col-sm-5">
                                    <input type="text" name="rewards" id="rewards2" value="" maxlength="255" placeholder="Rewards" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-7">
                                    <div class="btn-group">
                                        <button id="getrewardid_12" type="button" class="btn-sky btn"><i class="icon-unlock"></i> <span>Val</span></button>
                                        <button id="getrewardid_22" type="button" class="btn-inverse btn"><i class="icon-legal"></i> <span>Item</span></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php
                        if($this->session->userdata('brand')=="")
                        {
                        ?>
                        <tr>
                            <td><label for="brand2">Store</label></td>
                            <td>
                                <select id="brand2" name="brand" class="form-control">
                                    <option value="">&nbsp;</option>
                                    <?php 
                                    foreach($brand as $dt)
                                    {
                                        echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                    }
                                    ?>
                               </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>
                                <label for="fileimagedefault2">Icon Default</label>
                            </td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimagedefault" id="fileimagedefault2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <img id="filepreviewdefault2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="fileimageactive2">Icon Active</label>
                            </td>
                            <td>
                                <span class="btn btn-success fileinput-button">
                                    <i class="icon-plus"></i>
                                    <span>Choose file...</span>
                                    <input type="file" name="fileimageactive" id="fileimageactive2" />
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <img id="filepreviewactive2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="cmbstate2">State</label>
                            </td>
                            <td>
                                <select id="cmbstate2" name="cmbstate" class="form-control">
                                    <?php
                                    foreach($this->tambahan_fungsi->document_state() as $dt=>$value)
                                    {
                                        echo "<option value='".$dt."'>".$value."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Achievement","module5","Edit",TRUE,FALSE,"home"))
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
<div id="innerframedialogreward" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Reward</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-4">
                        <select id='cmbtiperewardview' name='cmbtiperewardview' onchange='reload_codereward(this.value);' class='form-control'>
                            <option value="">Pilih Tipe</option>
                            <?php
                            foreach($datareward as $dt)
                            { 
                                echo "<option value='".$dt['_id']."'>".$dt['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select id='cmbtiperewardcode' name='cmbtiperewardcode' class='form-control'></select>
                    </div>
                    <div class="col-sm-2">
                        <input type="number" name="countreward" id="countreward" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                    </div>
                    <div class="col-sm-2">
                        <button type="button" id="getrewardadd" class="btn btn-default"><i class="icon-plus"></i> Add</button>
                    </div>
                </div>
                <div id="listrewardadd"></div>
            </div>
            <div class="modal-footer">          
                <button id="tblsetreward" type="button" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="innerframedialogreward2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Avatar Item</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="gender" class="control-label">Gender</label>
                            <select id='gender' name='gender' class='form-control'>
                                <option value="">All Gender</option>
                                <?php
                                foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                {
                                    echo "<option value='".$dt."'>".$value."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="tipe" class="control-label">Type</label>
                            <select id='tipe' name='tipe' class='form-control'>
                                <option value="">All Avatar Type</option>
                                <?php 
                                foreach($tipe as $dt)
                                { 
                                    $this->mongo_db->select_db("Assets");
                                    $this->mongo_db->select_collection("AvatarBodyPart");
                                    $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                                    if($listtipe->count()>0)
                                    {
                                        echo "<optgroup label='".$dt['name']."'>";
                                        foreach($listtipe as $dt2)
                                        {
                                            echo "<option value='".$dt2['name']."'>".$dt2['name']."</option>";
                                        }
                                        echo "</optgroup>";
                                    }
                                    else 
                                    {
                                        echo "<option value='".$dt['name']."'>".$dt['name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="brand" class="control-label">Store</label>
                            <select id='brand' name='brand' class='form-control'>
                                <option value="">All Store</option>
                                <?php
                                foreach($brand as $dt)
                                {
                                    echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <button id="filtersearch" type="button" class="btn-midnightblue btn col-sm-12 text-center"> <i class="icon-filter"></i> <span>Filter and Show</span></button>
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div id="listdataavatar" class="col-sm-12 text-center" style="padding: 20px;">&nbsp;</div>
                    </div>
                    <div class="row" style="margin-top: 30px;">                        
                        <div class="col-sm-8">
                            <input type="number" name="countreward2" id="countreward2" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                        </div>
                        <div class="col-sm-4">
                            <button type="button" id="getrewardadd2" class="btn btn-default"><i class="icon-plus"></i> Add</button>
                        </div>
                    </div>                    
                </div>
                <div id="listrewardadd2"></div>
            </div>
            <div class="modal-footer">          
                <button id="tblsetreward_2" type="button" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>