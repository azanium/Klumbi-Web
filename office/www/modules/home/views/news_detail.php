<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-danger">
              <div class="panel-heading"><h4><?php echo $title; ?></h4></div>
              <div class="panel-body"><?php echo $this->tambahan_fungsi->filter_text($data); ?></div>
              <div id="taglikebutton" class="panel-footer">
                  <p class="alignRight"><?php
                  if($this->session->userdata('login'))
                  {
                        if($btnlove)
                        {
                            echo "<a href='".$this->template_admin->link("home/news/userlike/".$txtid."/".$txtalias)."' class='tooltips likebutton' data-trigger='hover' data-original-title='Unlike This' title='Unlike This'><i class='icon-thumbs-down-alt'>You like this</i></a>";
                        }
                        else
                        {
                            echo "<a href='".$this->template_admin->link("home/news/userlike/".$txtid."/".$txtalias)."' class='tooltips likebutton' data-trigger='hover' data-original-title='Like This' title='Like This'><i class='icon-thumbs-up-alt'>Like this</i></a>";
                        }
                  }
                  ?>
                  <strong>Total Like</strong> <span class='badge badge-info'><?php echo $totallike; ?></span></p>
              </div>
            </div>
        </div>
    </div>  
    <?php
    if($this->session->userdata('login'))
    { 
    ?>    
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline" method="POST" id="formdata" action="<?php echo $this->template_admin->link("home/news/send/".$txtalias); ?>">
                <div class="input-group well col-md-12">
                    <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>" />
                    <input type="hidden" name="txtcount" id="txtcount" value="<?php echo $txtcount; ?>" />
                    <input type="hidden" name="txtlimit" id="txtlimit" value="<?php echo $txtlimit; ?>" />
                    <input type="hidden" name="txtpage" id="txtpage" value="<?php echo $txtlimit; ?>" />
                    <input type="hidden" name="txtjudul" id="txtjudul" value="<?php echo $title; ?>" />
                    <textarea name="txtmessage" id="txtmessage" class="form-control autosize {required:true}"></textarea>
                    <button type="submit" class="btn btn-primary"><i class="icon-comments"></i> Comment</button>
                </div>
           </form>
        </div>
    </div>
    <?php
    }
    else
    {
    ?>
    <input type="hidden" name="txtid" id="txtid" value="<?php echo $txtid; ?>" />
    <input type="hidden" name="txtcount" id="txtcount" value="<?php echo $txtcount; ?>" />
    <input type="hidden" name="txtlimit" id="txtlimit" value="<?php echo $txtlimit; ?>" />
    <input type="hidden" name="txtpage" id="txtpage" value="<?php echo $txtlimit; ?>" />
    <?php
    }
    ?> 
    <div id="loadnewmore" class="row" style="display: none;">
        <button id="loadnewbutton" type="button" class="col-md-12 btn btn-sky-alt"><span id="jmlnewdt" class="badge badge-info">30</span> New Comments</button>
    </div>
    <div class="row">                
        <ul class="panel-comments col-md-12" id="loadlistdata">
            <?php
            if($listdata)
            {
                $i=0;
                foreach($listdata as $dt_pesan)
                {
                    $id_panel = "lsdata".$i."_old";
                    $picture = base_url()."resources/image/index.jpg";
                    $namauser = "";
                    $namauserprof = "";
                    echo "<li id='".$id_panel."'>";
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("Account");
                    $temp_account=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$dt_pesan["user_id"])));
                    if($temp_account)
                    {
                        $namauserprof = (!isset($temp_account['username'])?"":$temp_account['username']);
                        if(isset($temp_account['fb_id']))
                        {
                            if($temp_account['fb_id']!="")
                            {
                                $picture = "https://graph.facebook.com/".$temp_account['fb_id']."/picture?type=large";//large,smaller,square
                            }
                        }                        
                    }
                    $this->mongo_db->select_collection("Properties");                    
                    $temp_user=$this->mongo_db->findOne(array('lilo_id'=>(string)$dt_pesan["user_id"]));
                    if($temp_user)
                    {
                        $namauser = (!isset($temp_user['avatarname'])?"No Name":$temp_user['avatarname']);
                        if(isset($temp_user['picture']))
                        {
                            if($temp_user['picture']!="")
                            {
                                $picture = $temp_user['picture'];
                            }
                        }
                    }
                    echo "<img src='".$picture."' alt='".$namauser."' />";
                    echo "<div class='content'>";
                    if($dt_pesan["user_id"]==$this->session->userdata('user_id'))
                    {
                        echo "<a href='#' onclick='hapuscomment(\"".(string)$dt_pesan["_id"]."\",\"".$id_panel."\");return false;' class='close tooltips' title='Delete this Comment' data-trigger='hover' data-original-title='Delete this Comment'>&times;</a>";
                    }     
                    echo "<span class='commented'>";
                    echo "<a href='".$this->template_admin->link("home/account/index/".$namauserprof)."'>".$namauser."</a>";
                    echo "<p>".$dt_pesan["comment"]."</p>";
                    echo "</span>";
                    echo "<span class='actions'>";
                    echo "<i class='error alignRight'>".date('Y-m-d H:i:s', $dt_pesan['datetime']->sec)."</i>";
                    echo "</span>";
                    echo "</div>";
                    echo "</li>";
                    $i++;
                }
            }            
            ?>
        </ul>
    </div>
    <?php
        if($txtlimit<$txtcount)
        {
            echo "<div id='loadlistpagging' class='row'>";
            echo "<button id='loadmorebutton' type='button' class='col-md-12 btn-midnightblue-alt'><span id='loadtext'>Load more data</span> <i class='icon-comments-alt'></i></button>";
            echo "</div>";
        }
    ?>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('textarea.autosize').autosize({append: "\n"});
    $("#loadmorebutton").click(function(){
        var url = "<?php echo base_url()."home/news/loadmoredata/".$txtid."/".$txtalias."/"; ?>";
            url += $("#txtpage").val() + "/";
            url += $("#txtlimit").val() + "/";
            url += $("#txtcount").val();
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
                if(data['success']==true)
                {                                           
                    $("#loadlistdata").append(data["message"]);
                    $("#txtpage").val(data["page"]);
                    $("#txtlimit").val(data["limit"]);
                    if(data['jml'] < data["limit"])
                    {
                        $("#loadlistpagging").hide(200);
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
    });
    $("#loadnewbutton").click(function(){
        var url = "<?php echo base_url()."home/news/loaddatanew/".$txtid."/".$txtalias."/"; ?>";
            url += $("#txtcount").val();
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
                if(data['success']==true)
                {                                           
                    $("#loadnewmore").hide();
                    $("#loadlistdata").prepend(data["message"]);
                    $("#txtcount").val(data['count'])
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
    });
    <?php
    if($this->session->userdata('login'))
    { 
    ?>
    $( "#formdata" ).submit(function() {
        var url=$("#formdata").attr('action');
        var datapost=$("#formdata").serialize();
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
                    <?php
                    $picture = base_url()."resources/image/index.jpg";
                    $namauser = "";
                    $namauserprof = "";
                    $this->mongo_db->select_db("Users");
                    $this->mongo_db->select_collection("Account");
                    $temp_account=$this->mongo_db->findOne(array('_id'=>$this->mongo_db->mongoid((string)$this->session->userdata('user_id'))));
                    if($temp_account)
                    {
                        $namauserprof = (!isset($temp_account['username'])?"":$temp_account['username']);
                        if(isset($temp_account['fb_id']))
                        {
                            if($temp_account['fb_id']!="")
                            {
                                $picture = "https://graph.facebook.com/".$temp_account['fb_id']."/picture?type=large";//large,smaller,square
                            }
                        }                        
                    }
                    $this->mongo_db->select_collection("Properties");                    
                    $temp_user=$this->mongo_db->findOne(array('lilo_id'=>(string)$this->session->userdata('user_id')));
                    if($temp_user)
                    {
                        $namauser = (!isset($temp_user['avatarname'])?"No Name":$temp_user['avatarname']);
                        if(isset($temp_user['picture']))
                        {
                            if($temp_user['picture']!="")
                            {
                                $picture = $temp_user['picture'];
                            }
                        }
                    }
                    ?>
                    var id="rnd_"+Math.floor((Math.random()*10000)+1)+"awalinsert";
                    var currentdate = new Date();
                    var datetime = currentdate.getFullYear() + "-" + currentdate.getMonth() + "-" + currentdate.getDay() + " " +  currentdate.getHours() + ":" + currentdate.getMinutes() + ":" + currentdate.getSeconds();
                    $("#loadlistdata").prepend(
                        "<li id='" + id + "'>" + 
                        "<img src='<?php echo $picture; ?>' alt='Me' />" +
                        "<div class='content'>" +
                        "<a href='#' onclick='hapuscomment(\""+ data['_id'] +"\",\""+ id +"\");return false;' class='close tooltips' title='Delete this Comment' data-trigger='hover' data-original-title='Delete this Comment'>&times;</a>" +
                        "<span class='commented'>" +
                        "<a href='<?php echo $this->template_admin->link("home/account"); ?>'>Me</a>" +
                        "<p>" +
                        $("#txtmessage").val() + 
                        "</p>" +
                        "</span>" +
                        "<span class='actions'>" +
                        "<i class='error alignRight'>"+ datetime +"</i>" +
                        "</span>" +
                        "</div>" + 
                        "</li>");
                    $("textarea").val("");
                    var jmlcount = $("#txtcount").val() +1;
                    $("#txtcount").val(jmlcount)
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
    setInterval(function() {
        var url = "<?php echo base_url()."home/news/loadnewdata/".$txtid."/".$txtalias."/"; ?>";
            url += $("#txtcount").val();
        $.ajax({
            type: "POST",
            url: url,
            data:"",
            dataType: "json",
            success: function (data, textStatus) {
                if(data['success']==true)
                {                        
                    if($("#txtcount").val()<data['count'])
                    {
                        $("#loadnewmore").show();
                        $("#jmlnewdt").text(data['jml']);
                    }    
                    else
                    {
                        $("#loadnewmore").hide();
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
            }
       });
    },10000);    
    <?php
    }
    ?> 
});  
function hapuscomment(idcomment,idtag)
{
    BootstrapDialog.confirm("Are you sure want to delete your comment ?", function(result){
        if(result) 
        {
            var url='<?php echo base_url(); ?>home/news/delete/'+idcomment + "/<?php echo $txtalias; ?>";
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
                            var jmlcount = $("#txtcount").val() - 1;
                            $("#txtcount").val(jmlcount)
                            $( "#" + idtag ).remove();
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
</script>