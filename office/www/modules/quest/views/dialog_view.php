<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
var dttable;
var counter=1;
var optioncounter=1;
var idotomatis=0;
$(document).ready(function() {
    $('textarea.autosize').autosize({append: "\n"});
    dttable=$('#datatable').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bServerSide": true,
        "sAjaxSource": "<?php echo $this->template_admin->link("quest/dialog/list_data"); ?>",
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight","bSortable": false }, {"bSortable": false }, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('#datatablechile , #datatablechilequiz').dataTable( {
        "bJQueryUI": true,
        "bProcessing": true,
        "bFilter": true,
        "bAutoWidth": false,
        "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page",
            "sSearch": ""
        },
        "aoColumns": [ { "sClass" : "alignRight" }, null, null, { "sClass" : "text-center","bSortable": false }]
    });
    $('.dataTables_filter input').addClass('form-control').attr('placeholder','Search...');
    $('.dataTables_length select').addClass('form-control');
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
    $('#reload , #resetall').click(function(){
        dttable.fnClearTable(0);
	dttable.fnDraw();
        $('#frmchilddialog').html('');
        $('#frmchilddialog2').html('');
        idotomatis=0;
        counter=1;
        optioncounter=1;
    });
    $( "#adddialogid" ).click(function() { 
        var texthtml="<div id='lstable"+counter+"' style='margin: 30px;padding: 10px;'>";
        texthtml= texthtml +"<div class='form-group'>";
        texthtml= texthtml + "<div class='col-sm-12' align='right'>";
        texthtml= texthtml + "<button type='button' class='btn-danger btn' onclick='removeoption(\""+counter+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button>";
        texthtml= texthtml + "</div>";
        texthtml= texthtml + "</div>";         
        texthtml= texthtml +"<div class='form-group'>";
        texthtml= texthtml +"<input type='hidden' name='chiledcount[]' value='"+counter+"' />";       
        texthtml= texthtml +"<label class='col-sm-3 control-label'>ID</label>";
        texthtml= texthtml + "<div class='col-sm-9'>";
        texthtml= texthtml + "<input type='text' name='chiledid[]' value='"+idotomatis+"' maxlength='255' placeholder='ID' class='form-control {required:true}' />";
        texthtml= texthtml + "</div>";
        texthtml= texthtml + "</div>";        
        texthtml= texthtml +"<div class='form-group'>";
        texthtml= texthtml +"<label class='col-sm-3 control-label'>Description</label>";
        texthtml= texthtml + "<div class='col-sm-9'>";
        texthtml= texthtml + "<textarea name='descriptionchild[]' class='form-control autosize' cols='55' rows='3'></textarea>";
        texthtml= texthtml + "</div>";
        texthtml= texthtml + "</div>"; 
        texthtml= texthtml + "<div id='chileoption" + counter + "'>&nbsp;</div>";        
        texthtml= texthtml +"<div class='form-group'>";
        texthtml= texthtml +"<div class='col-sm-3'>&nbsp;</div>";
        texthtml= texthtml + "<div class='col-sm-9'>";
        texthtml= texthtml + "<button type='button' class='btn-midnightblue btn' onclick='tambahoption(\"" + counter + "\");return false;'><i class='icon-plus-sign'></i> <span>Add New Options</span></button>";
        texthtml= texthtml + "</div>";
        texthtml= texthtml + "</div>"; 
        texthtml= texthtml +"</div>";
        $("#frmchilddialog").append(texthtml);
        counter++;
        idotomatis++;
        return false; 
    });
    $( "#adddialogid2" ).click(function() {
        var texthtml="<div id='lstable"+counter+"'>";
        texthtml= texthtml +"<table width='100%'>";
        texthtml= texthtml + "<tr>";
        texthtml= texthtml + "<td colspan='2' align='right'>";
        texthtml= texthtml + "<button type='button' class='btn-danger btn' onclick='removeoption(\""+counter+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button>";
        texthtml= texthtml + "</td>";
        texthtml= texthtml + "</tr>";         
        texthtml= texthtml +"<tr>";
        texthtml= texthtml + "<td>";
        texthtml= texthtml +"<input type='hidden' name='chiledcount[]' value='"+counter+"' />";  
        texthtml= texthtml +"<label class='control-label'>ID</label>";
        texthtml= texthtml + "</td>";
        texthtml= texthtml + "<td>";
        texthtml= texthtml + "<input type='text' name='chiledid[]' value='"+idotomatis+"' maxlength='255' placeholder='ID' class='form-control {required:true}' />";
        texthtml= texthtml + "</td>"; 
        texthtml= texthtml + "</tr>";
        texthtml= texthtml +"<tr>";        
        texthtml= texthtml + "<td>";
        texthtml= texthtml +"<label class='control-label'>Description</label>";
        texthtml= texthtml + "</td>";
        texthtml= texthtml + "<td>";
        texthtml= texthtml +"<textarea name='descriptionchild[]' class='form-control autosize' cols='55' rows='3'></textarea>";
        texthtml= texthtml + "</td>";        
        texthtml= texthtml + "</tr>";
        texthtml= texthtml + "<tr>";
        texthtml= texthtml + "<td colspan='2'>";
        texthtml= texthtml + "<div id='chileoption" + counter + "'>&nbsp;</div>";
        texthtml= texthtml + "</td>";
        texthtml= texthtml + "</tr>";
        texthtml= texthtml +"<tr>";        
        texthtml= texthtml + "<td>&nbsp;</td>";
        texthtml= texthtml + "<td>";
        texthtml= texthtml +"<button type='button' class='btn-midnightblue btn' onclick='tambahoption2(\"" + counter + "\");return false;'><i class='icon-plus-sign'></i> <span>Add New Options</span></button>";
        texthtml= texthtml + "</td>";        
        texthtml= texthtml + "</tr>";
        texthtml= texthtml + "</table>"; 
        texthtml= texthtml +"</div>";
        $("#frmchilddialog2").append(texthtml);
        counter++;
        idotomatis++;
        return false; 
    });
    $("#frmduplikat").validate({
        submitHandler: function(form) {
            var url=$("#frmduplikat").attr('action');
            var datapost=$("#frmduplikat").serialize();
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
                        $('#inneropenduplikat').modal('hide');
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
                        idotomatis=0;
                        counter=1;
                        optioncounter=1;
                        $('#frmchilddialog').html('');
                        $('#frmchilddialog2').html('');
                        $("input, textarea").val("");
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
                        idotomatis=0;
                        counter=1;
                        optioncounter=1;
                        $('#frmchilddialog').html('');
                        $('#frmchilddialog2').html('');
                        $("input, textarea").val("");
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
function selectthisquiz(kodeitem,isitext)
{
    var group=$("#groupdialogquiz").val();
    var optionnumber=$("#optionnumberquiz").val();
    $("#chiledanak"+group+"_"+optionnumber).val(kodeitem);
    $("#textareachiledanak"+group+"_"+optionnumber).val(isitext);
    $("#innerframequiz").modal('hide');
}
function selectthisitem(kodeitem,isitext)
{
    var group=$("#groupdialog").val();
    var optionnumber=$("#optionnumber").val();
    $("#chiledanak"+group+"_"+optionnumber).val(kodeitem);
    $("#textareachiledanak"+group+"_"+optionnumber).val(isitext);
    $("#innerframedialog").modal('hide');
}
function ubahdata(id)
{
    idotomatis=0;
    counter=1;
    optioncounter=1;
    var i=1;
    var j=1;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url(); ?>quest/dialog/detail/'+id,
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
            $("#loadingprocess").slideUp(100);
            if(data['success']==true)
            {                        
                $('#txtid2').val(data['_id']);
                $('#name2').val(data['name']);
                $('#description2').val(data['description']);
                $('#type2').val(data['typedialog']);
                $("#frmchilddialog").html("");
                $("#frmchilddialog2").html("");
                if(data['dialogs'].length>0)
                {
                    var texthtml="";
                    for(i=0; i<data['dialogs'].length;i++)
                    {                    
                        texthtml= texthtml +"<div id='lstable"+i+"'>";                        
                        texthtml= texthtml +"<table width='100%'>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td colspan='2' align='right'><button type='button' class='btn-danger btn' onclick='removeoption(\""+i+"\");return false;'><i class='icon-remove'></i> <span>Remove</span></button></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td>";
                        texthtml= texthtml +"<input type='hidden' name='chiledcount[]' value='"+i+"' />";
                        texthtml= texthtml +"<label class='control-label'>ID</label>";
                        texthtml= texthtml +"</td>";
                        texthtml= texthtml +"<td>";
                        texthtml= texthtml +"<input type='text' name='chiledid[]' value='"+data['dialogs'][i]['id']+"' maxlength='255' placeholder='ID' class='form-control {required:true}' />";
                        texthtml= texthtml +"</td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td><label class='control-label'>Description</label></td>";
                        texthtml= texthtml +"<td><textarea name='descriptionchild[]' class='form-control autosize' cols='55' rows='3'>"+data['dialogs'][i]['description']+"</textarea></td>";
                        texthtml= texthtml +"</tr>";
                        var html2="";
                        if(data['dialogs'][i]['options'].length>0)
                        {
                            for(j=0;j<data['dialogs'][i]['options'].length;j++)
                            {                            
                                var terpilih1="selected='selected'";
                                var terpilih2="";
                                var terpilih3="";
                                var terpilih4="";
                                var anakchile="";
                                if(data['dialogs'][i]['options'][j]['option_type']=="0")
                                {
                                    terpilih1="";
                                    terpilih2="selected='selected'";
                                    terpilih3="";
                                    terpilih4="";
                                    anakchile="<div class='col-sm-6'><input type='text' name='descriptionchile"+i+"[]' id='chiledanak"+i+"_"+j+"' value='"+data['dialogs'][i]['options'][j]['description']+"' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
                                    anakchile=anakchile+"<div class='col-sm-6'><input type='text' name='descriptionchilenextid"+i+"[]' value='"+data['dialogs'][i]['options'][j]['next_id']+"' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
                                }
                                else if(data['dialogs'][i]['options'][j]['option_type']=="1")
                                {
                                    terpilih1="";
                                    terpilih2="";
                                    terpilih3="selected='selected'";
                                    terpilih4="";
                                    anakchile="<div class='col-sm-5'><input type='text' name='descriptionchile"+i+"[]' id='textareachiledanak"+i+"_"+j+"' value='"+data['dialogs'][i]['options'][j]['description']+"' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
                                    anakchile=anakchile+"<div class='col-sm-5'><input type='text' name='descriptionchilenextid"+i+"[]' id='chiledanak"+i+"_"+j+"' value='"+data['dialogs'][i]['options'][j]['next_id']+"' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
                                    anakchile=anakchile+"<div class='col-sm-2'><button type='button' class='btn-green btn' onclick='showlistquest(\""+i+"\",\""+j+"\");return false;'><i class='icon-gift'></i> Quest ID</button></div>";
                                }
                                else if(data['dialogs'][i]['options'][j]['option_type']=="2")
                                {
                                    anakchile="";
                                    terpilih1="";
                                    terpilih2="";
                                    terpilih3="";
                                    terpilih4="selected='selected'";
                                    anakchile="<div class='col-sm-5'><input type='text' name='descriptionchile"+i+"[]' id='textareachiledanak"+i+"_"+j+"' value='"+data['dialogs'][i]['options'][j]['description']+"' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
                                    anakchile=anakchile+"<div class='col-sm-5'><input type='text' name='descriptionchilenextid"+i+"[]' id='chiledanak"+i+"_"+j+"' value='"+data['dialogs'][i]['options'][j]['next_id']+"' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
                                    anakchile=anakchile+"<div class='col-sm-2'><button type='button' class='btn-sky btn' onclick='selectthisquiz(\""+i+"\",\""+j+"\");return false;'><i class='icon-cogs'></i> Quiz ID</button></div>";
                                }
                                else
                                {
                                    anakchile="";
                                    terpilih1="selected='selected'";
                                    terpilih2="";
                                    terpilih3="";
                                    terpilih4="";
                                }
                                var id_html = "rd_" + i + j + "_" + Math.random().toString(36).substring(7) + Math.floor(Math.random() * 1000);
                                html2=html2+"<table width='100%' id='" + id_html + "' style='border: 1px solid #0073ea;margin-top:10px;'>";
                                html2= html2 +"<tr>";
                                html2= html2 +"<td>";
                                html2=html2+"<select name='optionschile"+i+"[]' class='form-control' id='selectedchoise"+i+"_"+j+"' onchange='strukturoption(\""+i+"\",\""+j+"\");return false;'>";
                                html2=html2+"<option value='' "+terpilih1+">No Options</option>";
                                html2=html2+"<option value='0' "+terpilih2+">Choice</option>";
                                html2=html2+"<option value='1' "+terpilih3+">Quest</option>";
                                html2=html2+"<option value='2' "+terpilih4+">Quiz</option>";
                                html2=html2+"</select>";
                                html2= html2 +"</td>";
                                html2= html2 +"<td>";
                                html2= html2 +"<button type='button' class='btn-danger btn' onclick='removesuboption(\""+ id_html +"\");return false;'><i class='icon-remove'></i></button>";
                                html2= html2 +"</td>";
                                html2= html2 +"</tr>";
                                html2= html2 +"<tr>";
                                html2= html2 +"<td colspan='2'>";
                                html2=html2+"<div id='listchangedata"+i+"_"+j+"'>"+anakchile+"</div>";
                                html2= html2 +"</td>";
                                html2= html2 +"</tr>";
                                html2=html2+"</table>";
                                optioncounter=j+1;
                            }
                        }
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td colspan='2'><div id='chileoption"+i+"'>"+html2+"</div></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"<tr>";
                        texthtml= texthtml +"<td>&nbsp;</td>";
                        texthtml= texthtml +"<td><button type='button' class='btn-midnightblue btn' onclick='tambahoption2(\""+i+"\");return false;'><i class='icon-plus-sign'></i> <span>Add New Options</span></button></td>";
                        texthtml= texthtml +"</tr>";
                        texthtml= texthtml +"</table>";
                        texthtml= texthtml +"</div>"; 
                        idotomatis=i;
                        counter=i+1;
                    }
                    $("#frmchilddialog2").append(texthtml);
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
}
function showlistquiz(divid,idoption)
{
    $("#innerframequiz").modal('show');
    $("#groupdialogquiz").val(divid);
    $("#optionnumberquiz").val(idoption);
}
function showlistquest(divid,idoption)
{
    $("#innerframedialog").modal('show');
    $("#groupdialog").val(divid);
    $("#optionnumber").val(idoption);
}
function strukturoption(divid,idoption)
{
    var selectedcmb=$("#selectedchoise"+divid+"_"+idoption).val();
    if(selectedcmb=="0")
    {
        var htmladd="<div class='col-sm-6'><input type='text' name='descriptionchile"+divid+"[]' id='chiledanak"+divid+"_"+idoption+"' value='' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
        htmladd= htmladd+"<div class='col-sm-6'><input type='text' name='descriptionchilenextid"+divid+"[]' value='' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
        $("#listchangedata"+divid+"_"+idoption).html(htmladd);
    }
    else if(selectedcmb=="1")
    {
        var htmladd="<div class='col-sm-5'><input type='text' name='descriptionchile"+divid+"[]' id='textareachiledanak"+divid+"_"+idoption+"' value='' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
        htmladd= htmladd+"<div class='col-sm-5'><input type='text' name='descriptionchilenextid"+divid+"[]' id='chiledanak"+divid+"_"+idoption+"' value='' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
        htmladd= htmladd+"<div class='col-sm-2'><button type='button' class='btn-green btn' onclick='showlistquest(\""+divid+"\",\""+idoption+"\");return false;'><i class='icon-gift'></i> Quest ID</button></div>";
        $("#listchangedata"+divid+"_"+idoption).html(htmladd);
    }
    else if(selectedcmb=="2")
    {
        var htmladd="<div class='col-sm-5'><input type='text' name='descriptionchile"+divid+"[]' id='textareachiledanak"+divid+"_"+idoption+"' value='' maxlength='255' placeholder='Description' class='form-control {required:true}' /></div>";
        htmladd= htmladd+"<div class='col-sm-5'><input type='text' name='descriptionchilenextid"+divid+"[]' id='chiledanak"+divid+"_"+idoption+"' value='' maxlength='255' placeholder='Next ID' class='form-control {required:true}' /></div>";
        htmladd= htmladd+"<div class='col-sm-2'><button type='button' class='btn-sky btn' onclick='showlistquiz(\""+divid+"\",\""+idoption+"\");return false;'><i class='icon-cogs'></i> Quiz ID</button></div>";
        $("#listchangedata"+divid+"_"+idoption).html(htmladd);
    }
    else
    {
        $("#listchangedata"+divid+"_"+idoption).html("");
    }
}
function tambahoption2(divid)
{
    var id_html = "rd_" + divid + "_" + Math.random().toString(36).substring(7) + Math.floor(Math.random() * 1000);
    var html="<table width='100%' id='" + id_html + "' style='border: 1px solid #0073ea;margin-top:10px;'>";
    html=html + "<tr>";
    html=html + "<td>";
    html=html + "<select name='optionschile" + divid + "[]' class='form-control' id='selectedchoise" + divid + "_" + optioncounter + "' onchange='strukturoption(\"" + divid + "\",\"" + optioncounter + "\");return false;'>";
    html=html + "<option value=''>No Options</option>";
    html=html + "<option value='0'>Choice</option>";
    html=html + "<option value='1'>Quest</option>";
    html=html + "<option value='2'>Quiz</option>";
    html=html + "</select>";    
    html=html + "</td>";
    html=html + "<td>";
    html=html + "<button type='button' class='btn-danger btn' onclick='removesuboption(\""+ id_html +"\");return false;'><i class='icon-remove'></i></button>";
    html=html + "</td>";
    html=html + "</tr>";
    html=html + "<tr>";
    html=html + "<td colspan='2'>";
    html=html + "<div id='listchangedata" + divid + "_" + optioncounter + "'>&nbsp;</div>";
    html=html + "</td>";    
    html=html + "</tr>";
    html=html + "</table>";    
    $("#chileoption" + divid).append(html);
    optioncounter++;
}
function tambahoption(divid)
{
    var id_html = "rd_" + divid + "_" + Math.random().toString(36).substring(7) + Math.floor(Math.random() * 1000);
    var html="<div id='" + id_html + "' class='form-group'>";
    html=html + "<div class='col-sm-3'>";
    html=html + "<select name='optionschile" + divid + "[]' class='form-control' id='selectedchoise" + divid + "_" + optioncounter + "' onchange='strukturoption(\"" + divid + "\",\"" + optioncounter + "\");return false;'>";
    html=html + "<option value=''>No Options</option>";
    html=html + "<option value='0'>Choice</option>";
    html=html + "<option value='1'>Quest</option>";
    html=html + "<option value='2'>Quiz</option>";
    html=html + "</select>";    
    html=html + "</div>";
    html=html + "<div class='col-sm-8' id='listchangedata" + divid + "_" + optioncounter + "'>&nbsp;</div>";
    html=html + "<div class='col-sm-1' align='right'><button type='button' class='btn-danger btn' onclick='removesuboption(\""+ id_html +"\");return false;'><i class='icon-remove'></i></button></div>";
    html=html + "</div>";    
    html=html + "<hr style='border: 1px solid #0073ea;margin-top:10px;'/>";
    $("#chileoption" + divid).append(html);
    optioncounter++;
}
function removesuboption(divid)
{
    $('#'+divid).remove(); 
}
function removeoption(divid)
{
    BootstrapDialog.confirm("Are you sure want to delete this option ?", function(result){
        if(result) 
        {
            $('#lstable'+divid).remove();              
        }
    });
}
function hapusdata(id,pesan)
{
    BootstrapDialog.confirm(pesan, function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>quest/dialog/delete/'+id;
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
    });
}
function duplikat(id)
{
    $("#inneropenduplikat").modal('show');
    $("#txtidduplikat").val(id);
    return false;
}
</script>
<?php
if($this->m_checking->actions("Dialogs","module5","Import Exl",TRUE,FALSE,"home"))
{    
?>
<div id="page-heading">
    <div class="options">
        <div class="btn-toolbar">
            <div class="btn-group hidden-xs">
                <a href='#' class="btn btn-muted dropdown-toggle" data-toggle='dropdown'><i class="icon-cloud-download"></i><span class="hidden-sm"> Export as  </span><span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo $this->template_admin->link("quest/dialog/excell"); ?>" target="_blank">Excell file</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php 
}
?>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-purple">
                <div class="panel-heading">
                    <h4>Dialog</h4>
                    <div class="options">
                        <ul class="nav nav-tabs">
                          <li class="active"><a href="#listdata" data-toggle="tab"><i class="icon-table"></i> Dialogs</a></li>
                          <li><a href="#addnewdata" data-toggle="tab"><i class="icon-plus"></i> Create Dialog</a></li>
                          <?php 
                          if($this->m_checking->actions("Dialogs","module5","Import Txt",TRUE,FALSE,"home"))
                          {
                          ?>
                          <li><a href="#importdata" data-toggle="tab"><i class="icon-cloud-upload"></i> Import Dialog</a></li>
                          <?php 
                          }
                          ?>
                        </ul>
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="tab-content">
                        <div class="tab-pane active" id="listdata">
                            <form method="GET" class="form-horizontal" id="brandfrmexport" action="<?php echo $this->template_admin->link("quest/dialog/export"); ?>">
                                <p align="right">
                                    <a id="reload" class="btn btn-sm btn-success"><i class="icon-refresh"></i> Reload Data</a>
                                </p>                            
                                <table id="datatable" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="3%">&nbsp;</th>
                                            <th width="22%">Name</th>
                                            <th width="60%">Description</th>                        
                                            <th width="10%">Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5">No Data</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>
                                    <?php 
                                    if($this->m_checking->actions("Dialogs","module5","Export",TRUE,FALSE,"home"))
                                    {
                                        echo '<button type="submit" class="btn-green btn"> <i class="icon-cloud-download"></i> <span>Export Selected Data</span></button>&nbsp;&nbsp;';
                                    }
                                    ?>
                                </p>
                            </form>
                        </div>
                        <div class="tab-pane" id="addnewdata">
                            <form method="POST" class="form-horizontal" id="brandfrm" action="<?php echo $this->template_admin->link("quest/dialog/cruid_dialog"); ?>">
                                <div class="form-group">
                                    <input type="hidden" name="txtid" id="txtid" value="" />
                                    <label for="name" class="col-sm-3 control-label">Name</label>
                                    <div class="col-sm-6">
                                      <input type="text" name="name" id="name" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Name of Dialog!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-6">
                                        <textarea name="description" id="description" class="form-control autosize {required:true}" cols="55" rows="3"></textarea>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Description of Dialog!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="type" class="col-sm-3 control-label">Type</label>
                                    <div class="col-sm-6">
                                        <select id="type" name="type" class="form-control">
                                            <option value="npc">NPC</option>
                                            <option value="float">Float</option>
                                            <option value="startup">Start Up</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Type of Dialog!</p>
                                    </div>
                                </div>
                                <div id="frmchilddialog"></div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Dialogs</label>
                                    <div class="col-sm-6">                                      
                                      <button type="button" id="adddialogid" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Dialog</span></button>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">Option Dialog!</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <?php 
                                        if($this->m_checking->actions("Dialogs","module5","Add",TRUE,FALSE,"home"))
                                        {
                                            echo '<button type="submit" class="btn-green btn"> <i class="icon-save"></i> <span>Save</span></button>&nbsp;&nbsp;'; 
                                        }
                                        ?>
                                        <button type="reset" id="resetall" class="btn-default btn resetform"><i class="icon-file-alt"></i> <span>Reset</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php 
                        if($this->m_checking->actions("Dialogs","module5","Import Txt",TRUE,FALSE,"home"))
                        {
                        ?>
                        <div class="tab-pane" id="importdata">                            
                            <form method="POST" class="form-horizontal" id="brandfrmimport" enctype="multipart/form-data" action="<?php echo $this->template_admin->link("quest/dialog/import"); ?>">
                                <div class="form-group">
                                    <label for="txtfileimport" class="col-sm-3 control-label">File Import</label>
                                    <div class="col-sm-6">
                                        <span class="btn btn-success fileinput-button">
                                            <i class="icon-plus"></i>
                                            <span>Choose file...</span>
                                            <input type="file" name="txtfileimport" id="txtfileimport" />
                                        </span>
                                    </div>
                                    <div class="col-sm-3">
                                        <p class="help-block">File format (*.txt)</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-3">&nbsp;</div>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn-green btn"> <i class="icon-cloud-upload"></i> <span>Process Import</span></button>
                                    </div>
                                </div>
                            </form>                            
                        </div>
                        <?php
                        }
                        ?>
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
                <form method="POST" id="editbrandfrm" action="<?php echo $this->template_admin->link("quest/dialog/cruid_dialog"); ?>">
                    <table class="table table-striped table-bordered table-hover" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td>
                                <input type="hidden" name="txtid" id="txtid2" value="" />
                                <label for="name2">Name</label>
                            </td>
                            <td>
                                <input type="text" name="name" id="name2" value="" maxlength="255" placeholder="Name" class="form-control {required:true}" />
                            </td>
                        </tr>
                        <tr>
                            <td><label for="description2">Description</label></td>
                            <td><textarea name="description" id="description2" class="form-control autosize {required:true}" cols="55" rows="3"></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="type2">Type</label></td>
                            <td>
                                <select id="type2" name="type" class="form-control">
                                    <option value="npc">NPC</option>
                                    <option value="float">Float</option>
                                    <option value="startup">Start Up</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><label>Dialogs</label></td>
                        </tr>
                        <tr>
                            <td colspan="2"><div id="frmchilddialog2"></div></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><button type="button" id="adddialogid2" class="btn-info btn"><i class="icon-edit"></i> <span>Add New Dialog</span></button></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <?php 
                                if($this->m_checking->actions("Dialogs","module5","Edit",TRUE,FALSE,"home"))
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
<div id="inneropenduplikat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="frmduplikat" action="<?php echo $this->template_admin->link("quest/dialog/duplicate"); ?>">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Input Count duplikat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="hidden" name="txtidduplikat" id="txtidduplikat" value="" />
                            <input type="number" name="countduplikat" id="countduplikat" step="1" min="0" max="10000" value="1" maxlength="255" placeholder="Count" class="form-control {number:true}" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">          
                    <button type="submit" class="btn btn-success"><i class="icon-ok-circle"></i> Process</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="innerframedialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Quest Saved</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="groupdialog" id="groupdialog" value="" />
                        <input type="hidden" name="optionnumber" id="optionnumber" value="" />
                        <table id="datatablechile" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">ID</th>
                                    <th width="85%">Description</th>
                                    <th width="5%">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                foreach($listquest as $dt)
                                {
                                    $selected=$this->template_icon->detail_onclick("selectthisitem('".$dt['ID']."','".$dt['Description']."')","",'Detail',"accept.png","","linkdetail");
                                    echo "<tr>";
                                    echo "<td>".$i."</td>";
                                    echo "<td>".$dt['ID']."</td>";
                                    echo "<td>".$dt['Description']."</td>";
                                    echo "<td>".$selected."</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                ?>
                            </tbody>                           
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<div id="innerframequiz" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">List Source Quiz Saved</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="hidden" name="groupdialogquiz" id="groupdialogquiz" value="" />
                        <input type="hidden" name="optionnumberquiz" id="optionnumberquiz" value="" />
                        <table id="datatablechilequiz" class="table table-striped table-bordered datatables datatable_rd" cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="5%">ID</th>
                                    <th width="85%">Description</th>
                                    <th width="5%">Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=1;
                                foreach($listquiz as $dt)
                                {
                                    $selected=$this->template_icon->detail_onclick("selectthisquiz('".$dt['ID']."','".$dt['Description']."')","",'Detail',"accept.png","","linkdetail");
                                    echo "<tr>";
                                    echo "<td>".$i."</td>";
                                    echo "<td>".$dt['ID']."</td>";
                                    echo "<td>".$dt['Description']."</td>";
                                    echo "<td>".$selected."</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                                ?>
                            </tbody>                           
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">                
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="icon-ban-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>