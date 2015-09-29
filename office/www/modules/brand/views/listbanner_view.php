<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable,dttablemix,dttableavaitem;
$(document).ready(function() {
    $( "#generate" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $( "#generate2" ).click(function() { 
        generatecodernd();
        return false; 
    });
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve'});
    $('#txtvaluetag').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_hastag()); ?>});
    $('#txttag , #txttag2').select2({width: "resolve", tags:<?php echo json_encode($this->tambahan_fungsi->list_tag()); ?>});
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
    $('#fileimage2').fileupload({
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
                $("#txtfileimgname2").val(data.result.name);
                $("#filepreview2").attr("src", '<?php echo $this->config->item('path_asset_img'); ?>preview_images/'+data.result.name);
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
        $("input, textarea").val("");
        $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
    $("#reloadavataritem").click(function(){
        dttableavaitem.fnClearTable(0);
        dttableavaitem.fnDraw();
    });
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $( "#browsebutton , #browsebutton2" ).click(function() { 
        openKCFinder();
        return false; 
    });
    $( "#urlbutton , #urlbutton2" ).click(function() { 
        $("#txturldestination").val('');
        $('#collectionsdatavalueurl').modal('show');
        return false; 
    });
    $( "#tblseturl" ).click(function() {
        $("#tipefrmurlset").validate({
            submitHandler: function(form) {
                var textvalue = $("#txturldestination").val();
                $("#txtvalue").val(textvalue);
                $("#txtvalue2").val(textvalue);
                $("#type").val("url");
                $("#type2").val("url");
                $('#collectionsdatavalueurl').modal('hide');
            }  
        });
    });
    $( "#mixbutton , #mixbutton2" ).click(function() { 
        $("#collectionsdatavaluemix").modal('show');
        return false; 
    });
    dttablemix=$('#datatablemix').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/browse/list_data_mix"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });  
    dttableavaitem = $('#datatableavaitem').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("services/avatar/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "bAutoWidth": true,
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, null, null, null, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $( "#collectionbutton , #collectionbutton2" ).click(function() { 
        $("#txtvaluetag").val('');
        $("#collectionsdatavaluehastag").modal('show');
        return false; 
    });
    $( "#tblsethastag" ).click(function() {
        var textvalue = $("#txtvaluetag").val();
        $("#type").val("hastag");
        $("#type2").val("hastag");                            
        $("#txtvalue").val(textvalue);
        $("#txtvalue2").val(textvalue);
        $('#collectionsdatavaluehastag').modal('hide');
    });
    $( "#itembutton , #itembutton2" ).click(function() { 
        $("#collectionsdatavalueavataritem").modal('show');
        return false; 
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
                        $("input, textarea").val("");
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
                        $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("brand/banner/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, null, null, null, null, { "sClass" : "text-center","bSortable": false}, { "sClass" : "text-center","bSortable": false }]
    });
});
function generatecodernd()
{
    $.ajax({
        type: "GET",
        url: '<?php echo $this->template_admin->link("brand/banner/generatecode"); ?>',
        data:'',
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
        },
        success: function (data, textStatus) {             
            if(data['success']==true)
            {                        
                $("#IDCode").val(data['data']); 
                $("#IDCode2").val(data['data']);
            }
            else
            {
                $.pnotify({
                    title: "Fail",
                    text: data['message'],
                    type: 'info'
                });
            }
            $("#loadingprocess").slideUp(100);
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
function openKCFinder() 
{
    window.KCFinder = {
        callBack: function(url) {
            var newurl = location.protocol + '//' +location.hostname + url;
            $("#filepreview").attr("src", newurl);
            $("#filepreview2").attr("src", newurl);
            var countserver = '<?php echo $this->config->item('path_asset_img'); ?>'.length;
            var countbrowse = newurl.length;
            var filename = newurl.substr(countserver-1,countbrowse-countserver+1);
            $("#txtfileimgname").val(filename);
            $("#txtfileimgname2").val(filename);          
            window.KCFinder = null;
        }
    };
    window.open('<?php echo base_url(); ?>resources/plugin/kcfinder/browse.php?type=images&dir=files/public', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}
function getdatamix(id)
{
    $.ajax({
            type: "GET",
            url: '<?php echo base_url() ?>services/browse/get_detail_mix/'+id,
            data:'',
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
            },
            success: function (data, textStatus) {
                if(data['success']==false)
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'error'
                    });
                }
                else
                {
                    var isivalue = "{'id':'" + data['_id'] + "'}";
                    $("#txtvalue").val(isivalue);
                    $("#txtvalue2").val(isivalue);
                    $("#type").val("mix");
                    $("#type2").val("mix");
                    $("#collectionsdatavaluemix").modal('hide');
                }                
                $("#loadingprocess").slideUp(100);
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
function getavataritemval(id)
{
    $.ajax({
            type: "GET",
            url: '<?php echo base_url() ?>services/browse/get_detail_avataritem/'+id,
            data:'',
            dataType: "json",
            beforeSend: function ( xhr ) {
                $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
                                            '<strong>Warning!</strong> ' +
                                            '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                            '<i class="process">Wait a minute, Your request being processed</i>' +
                                            '</div>').slideDown(100);
            },
            success: function (data, textStatus) {
                if(data['success']==false)
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'error'
                    });
                }
                else
                {
                    var isivalue = "{'id':'" + data['_id'] + "'}";
                    $("#txtvalue").val(isivalue);
                    $("#txtvalue2").val(isivalue);
                    $("#type").val("avataritem");
                    $("#type2").val("avataritem");
                    $("#collectionsdatavalueavataritem").modal('hide');
                }                
                $("#loadingprocess").slideUp(100);
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
function ubahdata(id)
{
    var url='<?php echo base_url(); ?>brand/banner/get_data/'+id;
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
                    $("#txtid2").val(data['_id']);
                    $("#type2").val(data['type']);
                    $("#IDCode2").val(data['ID']);
                    $("#name2").val(data['name']);
                    $("#txtdescriptions2").val(data['Descriptions']);
                    $("#txtfileimgname2").val(data['urlPicture']);
                    $("#txtvalue2").val(data['dataValue']); 
                    $("#filepreview2").attr("src", data['pictureurl']);
                    $("#txttag2").val(data['tag']);
                    $("#txtcolor2").val(data['textcolor']);
                    $("#brand2").val(data['brand']);
                }
                else
                {
                    $.pnotify({
                        title: "Fail",
                        text: data['message'],
                        type: 'info'
                    });
                    $('#editdata').modal('hide');
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
            var url='<?php echo base_url(); ?>brand/banner/delete/'+id;
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
                            $("input, textarea").val("");
                            $("#filepreview").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
                            $("#filepreview2").attr("src", '<?php echo base_url(); ?>resources/image/none.jpg');
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
    });
}
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Home Banner</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Banners</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Banner</a></li>
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
                                        <th width="10%">ID</th>
                                        <th width="20%">Name</th>
                                        <th width="30%">Descriptions</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Picture</th>                        
                                        <th width="10%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="7">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST"  enctype="multipart/form-data" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("brand/banner/cruid_banner"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <input type="hidden" name="txtfileimgname" id="txtfileimgname" value="" />
                                    <input type="hidden" name="type" id="type" value="" />
                                    <label for="IDCode" class="col-sm-3 control-label">ID</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="IDCode" id="IDCode" value="" maxlength="255" placeholder="Banner ID" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="generate" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Banner!</p>
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
                                        <p class="help-block">Store of Banner!</p>
                                    </div>
                                </div>
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="txtdescriptions" class="col-sm-3 control-label">Descriptions</label>
                                    <div class="col-sm-6">
                                        <textarea name="txtdescriptions" id="txtdescriptions" class="form-control autosize" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Descriptions of Banner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txttag" class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-6">
                                        <textarea name="txttag" id="txttag" style="width:100%" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Tag of Banner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtcolor" class="col-sm-3 control-label">Text Color</label>
                                    <div class="col-sm-6">
                                        <select id="txtcolor" name="txtcolor" class="form-control">
                                            <?php
                                            foreach($colorls as $dtcolor)
                                            {
                                                echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Text Color of Banner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fileimage" class="col-sm-3 control-label">Banner Picture</label>
                                    <div class="col-sm-3">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="fileimage" id="fileimage" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="browsebutton" class="btn-orange btn"><i class="icon-search"></i> <span>Browse</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <img id="filepreview" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" />
                                        <p class="help-block">Image file of Banner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Destination Value</label>
                                    <div class="col-sm-9">
                                        <div class="btn-toolbar">
                                            <div class="btn-group">
                                                <button id="urlbutton" type="button" class="btn btn-danger"><i class="icon-link"></i> URL</button>
                                                <button id="mixbutton" type="button" class="btn btn-green"><i class="icon-magic"></i> Mix</button>
                                                <button id="itembutton" type="button" class="btn btn-midnightblue"><i class="icon-stethoscope"></i> Item</button>
                                                <button id="collectionbutton" type="button" class="btn btn-sky"><i class="icon-heart-empty"></i> Hastag</button>
                                            </div>                                        
                                        </div> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="txtvalue" class="col-sm-3 control-label">Value</label>
                                    <div class="col-sm-6">
                                        <textarea name="txtvalue" id="txtvalue" class="form-control" readonly="true" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Value of Banner!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Banner","module9","Add",TRUE,FALSE,"home"))
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("brand/banner/cruid_banner"); ?>" enctype="multipart/form-data">
                    <table width="100%" class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="15%">
                                <label for="IDCode2">ID</label>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <input type="hidden" name="txtfileimgname" id="txtfileimgname2" value="" />
                                <input type="hidden" name="type" id="type2" value="" />
                            </td>
                            <td width="85%">
                                <div class="col-sm-8">
                                    <input type="text" name="IDCode" id="IDCode2" value="" maxlength="255" placeholder="Banner ID" class="form-control {required:true}" />
                                </div>
                                <div class="col-sm-4">
                                    <button id="generate2" class="btn-sky btn"><i class="icon-credit-card"></i> <span>Generate</span></button>
                                </div>                                
                            </td>
                        </tr>
                        <tr>
                            <td><label for="name2">Name</label></td>
                            <td><input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" /></td>
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
                            <td><label for="txtdescriptions2">Descriptions</label></td>
                            <td><textarea name="txtdescriptions" id="txtdescriptions2" class="form-control autosize" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="txttag2">Tags</label></td>
                            <td><textarea name="txttag" id="txttag2" style="width:100%" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="txtcolor2">Text Color</label></td>
                            <td>
                                <select id="txtcolor2" name="txtcolor" class="form-control">
                                    <?php
                                    foreach($colorls as $dtcolor)
                                    {
                                        echo "<option value='".$dtcolor['name']."'>".$dtcolor['name']." ".$dtcolor['color']."</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="fileimage2">Banner Picture</label></td>
                            <td>
                                <div class="col-sm-6">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="icon-plus"></i>
                                        <span>Choose file...</span>
                                        <input type="file" name="fileimage" id="fileimage2" />
                                    </span>
                                </div>
                                <div class="col-sm-6">
                                    <button id="browsebutton2" class="btn-orange btn"><i class="icon-search"></i> <span>Browse</span></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><img id="filepreview2" src="<?php echo base_url(); ?>resources/image/none.jpg" alt="No Image" class="img-thumbnail" style="max-width:100px; max-height:100px;" /></td>
                        </tr>
                        <tr>
                            <td><label>Destination Value</label></td>
                            <td>
                                <div class="col-sm-12">
                                    <div class="btn-toolbar">
                                        <div class="btn-group">
                                            <button id="urlbutton2" type="button" class="btn btn-danger"><i class="icon-link"></i> URL</button>
                                            <button id="mixbutton2" type="button" class="btn btn-green"><i class="icon-magic"></i> Mix</button>
                                            <button id="itembutton2" type="button" class="btn btn-midnightblue"><i class="icon-stethoscope"></i> Item</button>
                                            <button id="collectionbutton2" type="button" class="btn btn-sky"><i class="icon-heart-empty"></i> Hastag</button>
                                        </div>                                        
                                    </div> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="txtvalue2">Value</label></td>
                            <td>
                                <textarea name="txtvalue" id="txtvalue2" class="form-control" readonly="true" cols="55" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                            <?php 
                            if($this->m_checking->actions("Banner","module9","Edit",TRUE,FALSE,"home"))
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
<div id="collectionsdatavalueurl" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <form method="POST" id="tipefrmurlset" action="#">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">URL Value</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="url" name="txturldestination" id="txturldestination" value="" maxlength="255" placeholder="http://" class="form-control {url:true}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">                
                    <button id="tblseturl" type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="collectionsdatavaluemix" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Avatar Mix Value</h4>
            </div>
            <div class="modal-body">
                <table id="datatablemix" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Title</th>
                            <th width="20%">Gender</th>
                            <th width="15%">Size</th>
                            <th width="10%">Picture</th>
                            <th width="25%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">No Data</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="collectionsdatavaluehastag" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Hastag Value</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <textarea name="txtvaluetag" id="txtvaluetag" style="width:100%" cols="55" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">                
                <button id="tblsethastag" type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Set Value</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="collectionsdatavalueavataritem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Avatar Item Value</h4>
            </div>
            <div class="modal-body">
                <p align="right">
                    <a id="reloadavataritem" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                </p>
                <table id="datatableavaitem" width="100%" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="5%">Code</th>
                            <th width="15%">Name</th>
                            <th width="15%">Type</th>
                            <th width="10%">Gender</th>
                            <th width="10%">Category</th>
                            <th width="10%">Payment</th>
                            <th width="10%">Brand</th>
                            <th width="10%">Size</th>
                            <th width="5%">Color</th>
                            <th width="5%">Operation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="11">No Data</td>
                        </tr>
                    </tbody>
                </table>
                <?php
                /*$index=0;
                echo "<div class='panel-heading'>";
                echo "<h4>Avatar Item</h4>";
                echo "<div class='options'>";
                echo "<ul class='nav nav-tabs'>";
                foreach($tipe as $dt)
                {
                    $terpilihtab = "";
                    if($index==0)
                    {
                        $terpilihtab = "class='active' ";
                    }
                    $title=(!isset($dt['title'])?"":$dt['title']);echo $title;
                    echo "<li ".$terpilihtab."><a href='#tabschild-1".$index."' data-toggle='tab'>".$title."</a></li>";
                    $index++;
                }
                echo "</ul>";
                echo "</div>";
                echo "</div>";
                $index=0;
                echo "<div class='panel-body'>";
                echo "<div class='tab-content'>";
                foreach($tipe as $dt)
                { 
                    $this->mongo_db->select_db("Assets");
                    $this->mongo_db->select_collection("AvatarBodyPart");
                    $listtipe=$this->mongo_db->find(array("parent"=>$dt['name']),0,0,array('name'=>1));
                    $terpilihtab = "";
                    if($index==0)
                    {
                        $terpilihtab = "active";
                    }
                    if($listtipe->count()>0)
                    {
                        $index2=$index;
                        echo "<div class='tab-pane ".$terpilihtab."' id='tabschild-1".$index."'>";
                        echo "<div id='anakdari".$index.$index2."'>";
                        echo "<div class='clear'>&nbsp;</div>";
                        echo "<div class='panel panel-danger'>";
                        echo "<div class='panel-heading'>";
                        echo "<h4>".$dt['title']." Part</h4>";
                        echo "<div class='options'>";
                        echo "<ul class='nav nav-tabs'>";
                        $i=0;
                        foreach($listtipe as $dt2)
                        {
                            $terpilihtab = "";
                            if($i==0)
                            {
                                $terpilihtab = "class='active' ";
                            }
                            $title2=(!isset($dt2['title'])?"":$dt2['title']);
                            echo "<li ".$terpilihtab."><a href='#subchild".$index.$index2."' data-toggle='tab'>".$title2."</a></li>";
                            $index2++;
                            $i++;
                        }
                        echo "</ul>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='panel-body'>";
                        echo "<div class='tab-content'>";
                        $index2=$index;
                        $i=0;
                        foreach($listtipe as $dt2)
                        {
                            $terpilihtab = "";
                            if($i==0)
                            {
                                $terpilihtab = "active";
                            }
                            echo "<div class='tab-pane ".$terpilihtab."' id='subchild".$index.$index2."'>";
                            echo "<div class='clear'>&nbsp;</div>";
                            $this->mongo_db->select_db("Assets");
                            $this->mongo_db->select_collection("Avatar");
                            $dataimg = $this->mongo_db->find(array("tipe"=>$dt2['name']),0,0,array("name"=>1));
                            generate_img("duapanel_".$index.$index2,$dataimg);
                            echo "<div class='clear'>&nbsp;</div>";
                            echo "</div>";
                            $index2++;
                            $i++;
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='clear'>&nbsp;</div>";
                        echo "</div>";
                        echo "</div>";
                        $index2++;
                    }
                    else 
                    {
                        echo "<div class='tab-pane ".$terpilihtab."' id='tabschild-1".$index."'>";
                        echo "<div class='clear'>&nbsp;</div>";
                        $this->mongo_db->select_db("Assets");
                        $this->mongo_db->select_collection("Avatar");
                        $dataimg=$this->mongo_db->find(array("tipe"=>$dt['name']),0,0,array("name"=>1));
                        generate_img("satupanel_".$index,$dataimg);
                        echo "<div class='clear'>&nbsp;</div>";
                        echo "</div>";
                    }
                    $index++;
                }
                echo "</div>";
                echo "</div>";
                function generate_img($index="1000",$jmldata=array())
                {
                    echo "<script type='text/javascript'>";
                    echo "$(document).ready(function() {";
                    echo "$('#chiledatatable".$index."').dataTable({";
                    echo "'sEmptyTable': 'No Cases available for selection',";
                    echo "'aoColumns': [{ 'sClass' : 'alignRight' }, null, null, null, null, null, null, null, { 'sClass' : 'text-center' }, { 'sClass' : 'text-center','bSortable': false }],";
                    echo "'bJQueryUI': true,";
                    echo "'bDestroy': true,";
                    echo "'bAutoWidth': false,";                    
                    echo "'sPaginationType': 'bootstrap',";
                    echo "'sDom': '<\"row\"<\"col-xs-6\"l><\"col-xs-6\"f>r>t<\"row\"<\"col-xs-6\"i><\"col-xs-6\"p>>',";
                    echo "'oLanguage': {'sLengthMenu': '_MENU_ records per page','sSearch': ''},";
                    echo "'bRetrieve':true";
                    echo "});";
                    echo "});";
                    echo "</script>";
                    echo "<div style='margin-top:10px;overflow: auto;'>";                    
                    echo "<table id='chiledatatable".$index."' class='table table-striped table-bordered datatables datatable_rd' cellpadding='0' cellspacing='0' border='0'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th width='5%'>No</th>";
                    echo "<th width='10%'>ID</th>";
                    echo "<th width='15%'>Name</th>";
                    echo "<th width='10%'>Type</th>";
                    echo "<th width='10%'>Gender</th>";
                    echo "<th width='15%'>Category</th>";
                    echo "<th width='15%'>Payment</th>";
                    echo "<th width='10%'>Brand</th>";
                    echo "<th width='5%'>Color</th>";
                    echo "<th width='5%'>Operation</th>";
                    echo "</tr>";
                    echo "</thead>";
                    if($jmldata)
                    {
                        echo "<tbody>";
                        $i=1;
                        foreach($jmldata as $listdt)
                        {
                            $childtipe=(!isset($listdt['tipe'])?"":$listdt['tipe']);  
                            $childcode=(!isset($listdt['code'])?"":$listdt['code']);
                            $childname=(!isset($listdt['name'])?"":$listdt['name']);                       
                            $childbrand=(!isset($listdt['brand_id'])?"":$listdt['brand_id']);
                            $childcategory=(!isset($listdt['category'])?"":$listdt['category']);
                            $childpayment=(!isset($listdt['payment'])?"":$listdt['payment']);                        
                            $childcolor=(!isset($listdt['color'])?"":$listdt['color']);
                            $childgender=(!isset($listdt['gender'])?"":$listdt['gender']);
                            $warna= "<span style='width: 15px;height: 15px;display:block;margin:20px 3px 3px 3px;background-color: ".$childcolor.";'></span>";
                            $operations=detail_onclick("setvalueavataritem('".$listdt['_id']."')","",'Set Value',"accept.png","Set Value","linkdetail");
                            echo "<td>".$i."</td>";
                            echo "<td>".$childcode."</td>";
                            echo "<td>".$childname."</td>";
                            echo "<td>".$childtipe."</td>";
                            echo "<td>".$childgender."</td>";
                            echo "<td>".$childcategory."</td>";
                            echo "<td>".$childpayment."</td>";
                            echo "<td>".$childbrand."</td>";
                            echo "<td>".$warna."</td>";
                            echo "<td>".$operations."</td>";
                            echo "</tr>";
                            $i++;
                        }
                        echo "</tbody>";
                    }
                    echo "</table>";                    
                    echo "</div>";
                }
                function detail_onclick($confirm="",$url="",$alt='Detail',$src="grid.png",$pesantext="",$class="linkaction")
                {
                    return "<a onclick=\"".$confirm.";return false;\" title=\"".$alt."\" href=\"".$url."\" class=\"".$class."\"><img alt=\"".$alt."\" src=\"".base_url()."resources/image/icon/".$src."\" /></a>&nbsp;";
                }*/
                ?>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
});
</script>