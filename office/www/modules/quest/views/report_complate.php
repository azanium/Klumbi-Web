<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#datatablequestdrjumlah').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "sScrollX": "100%",
        "bDestroy": true,
        "bPaginate": true,
        "sScrollXInner": "100%",  
        "bScrollCollapse": true,
        "aaSortingFixed": [ [0, 'desc'] ],
        "aaSorting": [[ 0, 'desc' ]],
        "aoColumnDefs": [
			{ "bVisible": false, "aTargets": [0] }
		],
        "bAutoWidth": true,
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        'aoColumns': [ null, null, null, null, null, { "sClass" : "text-center" }],
        "fnDrawCallback": function ( oSettings ) {
                if ( oSettings.aiDisplay.length == 0 )
                {
                        return;
                }
                var nTrs = $('tbody tr', oSettings.nTable);
                var iColspan = nTrs[0].getElementsByTagName('td').length;
                var sLastGroup = "";
                for ( var i=0 ; i<nTrs.length ; i++ )
                {
                        var iDisplayIndex = oSettings._iDisplayStart + i;
                        var sGroup = oSettings.aoData[ oSettings.aiDisplay[iDisplayIndex] ]._aData[0];
                        if ( sGroup != sLastGroup )
                        {
                                var nGroup = document.createElement( 'tr' );
                                var nCell = document.createElement( 'td' );
                                nCell.colSpan = iColspan;
                                nCell.className = "group";
                                nCell.innerHTML = sGroup;
                                nGroup.appendChild( nCell );
                                nTrs[i].parentNode.insertBefore( nGroup, nTrs[i] );
                                sLastGroup = sGroup;
                        }
                }
        }
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
});
function detailquestjournal(id)
{
    var url='<?php echo base_url(); ?>quest/report/quest_journal/'+id;
    $.ajax({
                type: "GET",
                url: url,
                data:"",
                dataType: "html",
                beforeSend: function ( xhr ) {
                    $("#loadingprocess").html('<div class="alert alert-dismissable alert-warning">' +
						'<strong>Warning!</strong> ' +
                                                '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                                '<i class="process">Wait a minute, Your request being processed</i>' +
						'</div>').slideDown(100);
                },
                success: function (data, textStatus) {
                    $("#loadingprocess").slideUp(100);
                    $('#responsemenu').html(data);                       
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
<div id="responsemenu">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Completed Quest Per Players</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <table id="datatablequestdrjumlah" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th>Jumlah</th>
                                <th width="25%">Full Name</th>
                                <th width="25%">Email</th>
                                <th width="25%">Twitter</th>
                                <th width="15%">Handphone</th>                        
                                <th width="15%">Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($countperjml as $dt=>$val)
                            {
                                $detail=$this->template_icon->detail_onclick("detailquestjournal('".$val['_id']."')","",'Detail',"grid.png","","linkdetail");            
                                echo "<tr>";
                                echo "<td>".$val['count']."</td>";
                                echo "<td>".$val['fullname']."</td>";
                                echo "<td>".$val['email']."</td>";
                                echo "<td>".$val['twitter']."</td>";
                                echo "<td>".$val['phone']."</td>";                        
                                echo "<td align='center'>".$detail."</td>";
                                echo "</tr>";
                            }                    
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>