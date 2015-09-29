<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('#rankquestjournalfinis , #rankquestactive').dataTable( {
            "sEmptyTable": "No Cases available for selection",
            'aoColumns': [ { "sClass" : "alignRight" }, null, { "sClass" : "alignRight" }],
            "bJQueryUI": true,
            "bDestroy": true,
            "bAutoWidth": false,
            "bRetrieve":true,
            "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page",
                "sSearch": ""
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
</script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Rank Quest</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Finish</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-table"></i> Active</a></li>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">
                            <table id="rankquestjournalfinis" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="80%">Quest</th>
                                        <th width="10%">Count Players</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rankjournal as $val)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$val['ID']."</td>";
                                            echo "<td>".$val['Description']."</td>";
                                            echo "<td>".$val['count']."</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <table id="rankquestactive" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th width="10%">ID</th>
                                        <th width="80%">Quest</th>
                                        <th width="10%">Count Players</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($rankactive as $val)
                                        {
                                            echo "<tr>";
                                            echo "<td>".$val['ID']."</td>";
                                            echo "<td>".$val['Description']."</td>";
                                            echo "<td>".$val['count']."</td>";
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
    </div>
</div>