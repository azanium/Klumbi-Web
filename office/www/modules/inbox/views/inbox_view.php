<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<style type="text/css">
    .unread{color: #0055ff;}
    .unread:hover{color: #0075b0;}    
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    $('.select').select2({width: 'resolve',placeholder: "Select contact"});
    $("#editbrandfrm").validate();
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });    
    $("#selectalltbl").click(function(){        
        var nilai = $("#selectalltbl").is(':checked');
        $(".chk-table").each(function () {
            if (nilai) 
            {
                $('.chk-table').prop( "checked", true );
            }
            else
            {
                $('.chk-table').prop( "checked", false );
            }
        });
    });
    $(".actionbutton").click(function(){
        var setmesage = "";
        if($(this).hasClass("delete"))
        {
            setmesage = "delete";
        }
        else if($(this).hasClass("setread"))
        {
            setmesage = "setread";
        }
        else if($(this).hasClass("setunread"))
        {
            setmesage = "setunread";
        }
        else if($(this).hasClass("setimportant"))
        {
            setmesage = "setimportant";
        }
        var url=$("#brandfrm").attr('action');
        var datapost=$("#brandfrm").serialize()+"&action="+setmesage;
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
                    //window.location.reload(); 
                    window.location = '<?php echo current_url(); ?>';
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
function selectall()
{
    var fields = $(".chk-table:checkbox").length;
    var jml = $(".chk-table:checkbox:checked").length;
    if(jml==fields)
    {
        $('#selectalltbl').prop( "checked", true );
    }
    else
    {
        $('#selectalltbl').prop( "checked", false );
    }
}
function lihatdetail(id)
{
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>inbox/detail/'+id,
        data:"",
        dataType: "json",
        beforeSend: function ( xhr ) {
            $("#detailmesage").html('<div class="alert alert-dismissable alert-warning">' +
                                    '<strong>Warning!</strong> ' +
                                    '<img src="<?php echo base_url(); ?>resources/image/1s.gif" alt="loading" />' +
                                    '<i class="process">Wait a minute, Your request being processed</i>' +
                                    '</div>').slideDown(100);
        },
        success: function (data, textStatus) {
            if(data["success"])
            {
                $("#detailmesage").html(data['html']); 
                $("#" + id).removeClass("unread");
            }                                  
        },
        error: function (xhr, textStatus, errorThrown) {
            $("#detailmesage").html((errorThrown ? errorThrown : xhr.status));
        }
    });
}
</script>
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="<?php echo $this->template_admin->link("home/index"); ?>">Home</a></li>
        <li>Account</li>
        <li class="active">Inbox</li>
    </ol>
    <h1>Inbox</h1>
</div>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="panel"><a href="<?php echo $this->template_admin->link("inbox/message/index"); ?>" class="btn btn-primary col-xs-12"><i class="icon-edit"></i> Create New</a></div>
            <div class="panel">
                <div class="list-group"> 
                    <a href="<?php echo $this->template_admin->link("inbox/index"); ?>" class="list-group-item" id="inboxmsg"><?php echo (($unreadinbox>0)?"<span class='badge badge-primary'>".$unreadinbox."</span>":""); ?> Inbox</a>
                    <a href="<?php echo $this->template_admin->link("inbox/index/send"); ?>" class="list-group-item" id="sendmsg">Sent</a>
                    <a href="<?php echo $this->template_admin->link("inbox/index/important"); ?>" class="list-group-item" id="importantmsg"><?php echo (($unreadimportant>0)?"<span class='badge badge-success'>".$unreadimportant."</span>":""); ?> Important</a>                    
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-gray">
                <div class="panel-heading">
                    <h4>Inbox</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <form class="form-inline" method="GET" action="<?php echo $this->template_admin->link("inbox/index/search"); ?>">
                        <div class="input-group">
                            <input placeholder="Search messages..." name="q" value="<?php echo $keysearch; ?>" class="form-control" type="text">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary"><i class="icon-search"></i> Search</button>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("inbox/setmessage"); ?>">
                        <table class="table table-striped table-advance table-hover mailbox">
                            <thead>
                                <tr>
                                    <th width="5%"><span><input type="checkbox" id="selectalltbl" name="checkRow"></span></th>
                                    <th colspan="1">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-magenta dropdown-toggle" href="#" data-toggle="dropdown"> Action <i class="icon-caret-down"></i></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" class="actionbutton setread">Mark Read</a></li>
                                                <li><a href="#" class="actionbutton setunread">Mark Unread</a></li>
                                                <li><a href="#" class="actionbutton setimportant">Move to Important</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#" class="actionbutton delete">Delete</a></li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th colspan="4">                                    
                                        <div class="pull-right">                                        
                                            <?php echo $paging; ?>
                                            <div class="pagination-info" style="margin-right:5px"><?php echo $pagedetail; ?></div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="listdataviewmsg">
                                <?php
                                if($count>0)
                                {
                                    foreach($datalist as $dt)
                                    {
                                        $textcolor = (($dt["read"]==TRUE)?"":"unread");
                                        $namapengirim = "Admin ".$this->config->item('aplicationname');
                                        if($dt["type"] === "inbox")
                                        {
                                            $tempdtuser = $this->m_userdata->user_properties($dt["user_id"]);
                                            $namapengirim = $tempdtuser['fullname'];
                                        }                                    
                                        echo "<tr class='".$textcolor."' id='".(string)$dt['_id']."'>";
                                        echo "<td>";
                                        echo "<span>";
                                        echo "<input type='checkbox' name='idchkinbox[]' value='".(string)$dt['_id']."' class='chk-table' onclick='selectall();' />";
                                        echo "</span>";
                                        echo "</td>";
                                        echo "<td class='text-center'>".$this->template_icon->link_icon_tag("lihatdetail('".(string)$dt['_id']."');return false;","#editdata",'Detail',"<i class='icon-search text-primary'></i>","  class=\"nounderline\" style=\"text-decoration: none;\" data-toggle=\"modal\" ")."&nbsp;".$this->template_icon->link_icon_tag("",base_url()."inbox/delete/".(string)$dt['_id'],'Delete',"<i class='icon-trash text-danger'></i>"," class=\"nounderline\" style=\"text-decoration: none;\"")."</td>";
                                        echo "<td>".$namapengirim."</td>";
                                        echo "<td>".word_limiter((($dt['subject']=="")?"No Subject":$dt['subject']),6)."</td>";                                
                                        echo "<td class='text-right'>".date('l, d F Y', $dt['datetime']->sec)."</td>";
                                        echo "<td class='hidden-xs text-center'>".date('h:i:s a', $dt['datetime']->sec)."</td>";
                                        echo "</tr>";
                                    }
                                }
                                else
                                {
                                    echo "<tr>";
                                    echo "<td colspan='6'>Empty Message</td>";
                                    echo "</tr>";                                
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5">
                                        <div class="pull-right">
                                            <?php echo $paging; ?>
                                            <div class="pagination-info" style="margin-right:5px"><?php echo $pagedetail; ?></div>
                                        </div>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </form>
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
                <h4 class="modal-title">Detail Message</h4>
            </div>
            <div id="detailmesage" class="modal-body"></div>
        </div>
    </div>
</div>