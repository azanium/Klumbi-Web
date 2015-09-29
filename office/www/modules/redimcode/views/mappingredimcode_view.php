<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    $('.select').select2({width: 'resolve'});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bFilter": false,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("redimcode/mappingredimcode/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
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
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
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
                        $("#listdataavatar").html("");
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
        var datapost=$("#brandfrm").serialize();
        $.ajax({
            type: 'POST',
            url: '<?php echo $this->template_admin->link("services/api/get_list_avatar_bysearch"); ?>',
            data: datapost,
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
                            texthtml += "<input type='checkbox' name='avatarid[]' value='" + data['data'][i]._id + "' id='checkbox" + i + "' />";
                            texthtml += "<label for='checkbox" + i + "' >";
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
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>redimcode/mappingredimcode/delete/'+id;
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
                            $("#listdataavatar").html("");
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
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Redemption Code Mapping</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Avatar Redeem Maps</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Avatar Redeem Map</a></li>
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
                                        <th width="45%">Redeem Code</th>
                                        <th width="45%">Avatar Detail</th>
                                        <th width="5%">Operation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("redimcode/mappingredimcode/cruid_mappingredimcode"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="redimcode" class="col-sm-3 control-label">Redeem Code</label>
                                    <div class="col-sm-9">
                                      <select id="redimcode" name="redimcode" class="select {required:true}" style="width:100%">
                                        <?php 
                                        foreach($isiredimcode as $dt)
                                        {
                                            $tglexpire="";
                                            if($dt['expire']!="")
                                            {
                                                $tglexpire=date('Y-m-d', $dt['expire']->sec);
                                            }
                                            echo "<option value='".$dt['_id']."'>".$dt['name']." CODE :".$dt['code']."(Count: ".$dt['count'].") Expire: ".$tglexpire."</option>";
                                        }
                                        ?>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="brand" class="col-sm-3 control-label">Filter By Store</label>
                                    <div class="col-sm-9">
                                        <select id="brand" name="brand" class="form-control">
                                            <option value=''>All Store</option>
                                            <?php 
                                            foreach($brand as $dt)
                                            {
                                                echo "<option value='".$dt['brand_id']."'>".$dt['name']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tipe" class="col-sm-3 control-label">Filter By Avatar Type</label>
                                    <div class="col-sm-9">
                                        <select id="tipe" name="tipe" class="form-control">
                                            <option value=''>All Avatar Type</option>
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
                                <div class="form-group">
                                    <label for="gender" class="col-sm-3 control-label">Filter By Gender</label>
                                    <div class="col-sm-9">
                                        <select id="gender" name="gender" class="form-control">
                                            <option value=''>All Gender</option>
                                            <?php 
                                            foreach($this->tambahan_fungsi->list_gender() as $dt=>$value)
                                            {
                                                echo "<option value='".$dt."'>".$value."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <button id="filtersearch" type="button" class="btn-midnightblue btn"> <i class="icon-filter"></i> <span>Filter and Show</span></button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <div id="listdataavatar" style="padding: 20px;">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Avatar Redeem Code","module5","Add",TRUE,FALSE,"home"))
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