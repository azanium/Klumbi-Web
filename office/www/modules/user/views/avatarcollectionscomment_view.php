<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
$(document).ready(function() {
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": false,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("user/avatarcollection/list_itemdatacommet/".$id_user."/".$id_collections); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, {"bSortable": false }, {"bSortable": false},{ "sClass" : "text-center","bSortable": false}]
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
});
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>User Mix Comments</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <p align="right">
                        <a href="<?php echo $this->template_admin->link("user/priviewavatar/index/".$id_user); ?>" class="btn btn-sm btn-info"><i class="icon-arrow-left"></i> Back</a>&nbsp;&nbsp;
                        <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                    </p>
                    <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Email</th>
                                <th width="15%">Username</th>
                                <th width="45%">Comment</th>
                                <th width="15%">Date</th>
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