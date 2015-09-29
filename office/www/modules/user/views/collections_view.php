<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "sPaginationType": "full_numbers",
        "bJQueryUI": true,
        "bFilter": true,
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/collections/list_data/".$id_user); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false }, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $('#reload').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
    });
});
function lihatconfigurasi(id)
{
    var url='<?php echo base_url(); ?>api/avatarcollect/detail_collections/' + id + "/web";
    $.ajax({
        type: "GET",
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
            try
            {
                if(data["success"])
                {
                    GetUnity().SendMessage("_DressRoom", "ChangeCharacterEvent", data['data']['configurations']);
                }                
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
    return false;
}
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Mix Collections User</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a href="<?php echo $this->template_admin->link("user/index"); ?>" class="btn btn-sm btn-info"><i class="icon-arrow-left"></i> Back</a>
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Owner</th>
                                <th width="10%">Title</th>
                                <th width="10%">Gender</th>
                                <th width="10%">Size</th>
                                <th width="10%">Store</th>
                                <th width="20%">Description</th>
                                <th width="15%">Picture</th>
                                <th width="5%">Operation</th>
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
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
        <?php
        $property['unity_object']='_DressRoom';
        $property['unity_error_image']='getunity.png';
        $property['width']=300;;
        $property['height']=456;        
        $property['maps']='';
        $property['url_unity'] = $this->config->item('path_asset_img').'webplayer/AvatarEditor.unity3d?'.time();
        $this->load->view("unity_object/loadunity",$property);
        ?>
        </div>
    </div>
</div>