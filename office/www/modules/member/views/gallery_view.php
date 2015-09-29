<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
$(document).ready(function() {
    $('.fancybox').fancybox({
        padding: 0,
        openEffect : 'elastic',
        openSpeed  : 150,
        closeEffect : 'elastic',
        closeSpeed  : 150,
        closeClick : true
    });
    $('.gallery').mixitup();
    $("#galleryfilter").change(function(e) {
        var cat = $("#galleryfilter option:selected").data('filter');
        $('.gallery').mixitup('filter', cat);
    });
    $('#GoList').click(function(e) {
        $('.gallery').mixitup('toList');
        $(this).addClass('active');
        var delay = setTimeout(function(){
            $('.gallery').addClass('full-width');
            $('#GoGrid').removeClass('active');
        });
    });
    $('#GoGrid').click(function(e) {
        $('#GoList').removeClass('active');
        $(this).addClass('active');
        var delay = setTimeout(function(){
            $('.gallery').mixitup('toGrid');
            $('.gallery').removeClass('full-width');
        });
    });
    $('.mix a').click(function(e){
        e.preventDefault();
        $('.modal-title').empty();
        $('.modal-body').empty();

        var title = $(this).siblings('h4').html();
        $('.modal-title').html(title);

        var img= '<img class="img-responsive" src=' +$(this).attr("href")+ '></img>';
        $(img).appendTo('.modal-body');

        $('#gallarymodal').modal({show:true});
    });
    $('a.panel-collapse').click(function() {
        $(this).children().toggleClass("icon-chevron-down icon-chevron-up");
        $(this).closest(".panel-heading").next().toggleClass("in");
        $(this).closest(".panel-heading").toggleClass('rounded-bottom');
        return false;
    });
});
</script>
<div id="confirmdlg">&nbsp;</div>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Gallery</h4>
                    <div class="options">
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group clearfix">
                                <select id="galleryfilter" class="form-control pull-left">
                                    <option value="all" data-filter="all">all</option>
                                    <?php
                                    foreach($listkeysearch["values"] as $dt)
                                    {
                                        echo "<option value='".$dt."' data-filter='".$dt."'>".$dt."</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="pull-right">
                                <div class="btn-toolbar form-group clearfix">
                                    <button class="btn btn-default sort" data-sort="random"><i class="icon-random"></i><span class="hidden-xs"> Randomize</span></button>
                                    <div class="btn-group">
                                        <button class="btn btn-default sort active" data-sort="default" data-order="desc">Default</button>
                                        <button class="btn btn-default sort" data-sort="data-name" data-order="desc"><i class="icon-sort-by-alphabet"></i><span class="hidden-xs"> Name</span></button>
                                        <button class="btn btn-default sort" data-sort="data-name" data-order="asc"><i class="icon-sort-by-alphabet-alt"></i><span class="hidden-xs"> Name</span></button>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-default active" id="GoGrid"><i class="icon-th"></i></button>
                                        <button class="btn btn-default" id="GoList"><i class="icon-th-list"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="margin-top:0; margin-bottom:10px;" />
                    <div class="row">
                        <ul class="gallery list-unstyled">
                            <?php
                            foreach($listimage as $dtimg)
                            {
                                echo "<li class='mix ".$dtimg['keysearch']."' data-name='".$dtimg['title']."'>";
                                echo "<a href='".$dtimg['image']."' class='fancybox'>";
                                echo "<img src='".$dtimg['image']."' style='width:150px; height:150px;' />";
                                echo "</a>";
                                echo "<h4>".$dtimg['title']."</h4>";
                                echo "</li>";
                            }
                            ?>
                            <li class="gap"></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>