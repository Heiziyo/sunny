
$(function(){

    //地点
    var all_Loc="";
    city_num = 0;
    var loc_len = location_data.items.length;
    var selected_value = $("#region_value").val().split(",");

    for(i = 0; i < loc_len; i++){
        var checked = "";
        for (var j in selected_value){
            if( selected_value[j]== location_data.items[i].value ){
                checked = "checked ='checked'"
            }
        }

        if(location_data.items[i].type == 'REGION'){
            if(city_num != 0){ //如果是最后一个城市，加上ul结束标签
                all_Loc = all_Loc + "<div class='clearfix'></div></ul></li>"
            }
            if(i==0){
                all_Loc = all_Loc + "<li class='region'><input "+checked+" type='checkbox' name='geo_select' value='"+location_data.items[i].value+"'><span>" + location_data.items[i].name+"</span>";
            }
            else{
                all_Loc = all_Loc + "</li><li class='region'><input "+checked+" type='checkbox' name='geo_select' value='"+location_data.items[i].value+"'><span>" + location_data.items[i].name+"</span>";
            }
            city_num = 0;
        }

        if(location_data.items[i].type == 'CITY'){
            if (city_num == 0){   //如果是第一个城市，加上ul标签
                all_Loc = all_Loc + " <span class='open_city'>[ + ]</span><ul style='display: none' class='cities'>";
                //<span class='close_city'>×</span>
            }
            all_Loc = all_Loc + "<li><input "+checked+" type='checkbox' name='geo_select' value='"+location_data.items[i].value+"'><span>" + location_data.items[i].name + "</span></li>";
            city_num ++;
            if(i==loc_len){ all_Loc= all_Loc + "</ul></li>"}
        }
    }

    $("#all_Loc").append(all_Loc);

    $("#all_Loc .region .open_city").click(function(){   // 展开该省的城市
     //   $(this).next().removeClass("hidden");
        $(this).next().toggle();

    });

    //点击其他地方隐藏 popup
    $(document).mouseup(function (e)
    {
        var container = $(".cities");

        if (!container.is(e.target)
            && container.has(e.target).length === 0)
        {
            container.hide();
        }
    });

    $("#all_Loc .region .close_city").click(function(){   // 关闭该省的城市
        $(this).parent().css('display','none');
    });


    $("#all_Loc input").change(function(){
        var select = $(this);
        var value = select.val();
        var place = select.next().text();
        if (select.prop("checked")==true){
            //如果某省已经被选择，再选择该省下的某市，取消对省的选择
            if(value.length >= 8 ){
                var region = $(select.parent().parent().parent().children()[0]);    //该市所属的省
                if(region.prop("checked")==true){
                    region.removeAttr("checked");
                    removeLoc(region.val());
                }
                //显示改市所属的省
                var regionName = region.next().text();
                place = regionName+' - '+place
            }
            //如果市已经被选择，再选中该市的省，取消对该神所有市的选择
            if(value.length == 5){
                var cities = select.next().next().next().find("li");
                for(i=0; i<cities.length; i++){
                    if($(cities[i]).find("input").prop("checked") == true){
                        $(cities[i]).find("input").removeAttr("checked");
                        removeLoc($(cities[i]).find("input").val());
                    }
                }
            }
            showSelected(value, place);
        }else{
            removeLoc(value);
	    $("#region_select_all").prop("checked", false);
        }
    });
    $("#region_select_all").change(function(event) {
        var obj = $(this);
        var checked = obj.prop("checked");
        $("#all_Loc .region > input").each(function(){
            var region = $(this);
            if( (checked && region.prop("checked") == false) || (checked == false && region.prop("checked") == true) ){
                region.trigger("click");
            }
        })
    });
});

//广告投放地点选择
function removeLoc(value){
    $("#"+value).remove();
    processForm("del",value);
}

function showSelected(value, place) {
    var node = $("#"+value);
    if(!node.length){
        var html = "<li class='region' id='"+value+"'><a class='region-close'>×</a>"+place+"</li>";
        $("#region-selected").append(html);
        $(".region a").on("click",function(){
            var value = $(this).parent().attr("id");
            $(this).parent().remove();
            processForm("del",value);
            $('#all_Loc input[value='+value+']').prop("checked",false);
        });
        processForm("add",value);
    }
}
function processForm(type,value) {
    var datas = $("#region_value").val();
    if(type=="add") {
        if(datas.indexOf(value)==-1) {
            $("#region_value").val(datas+value+",");
        }
    }else if(type=="del"){
        if(datas.indexOf(value)>=0) {
            var html = datas.replace(value+",","");
            $("#region_value").val(html);
        }
    }
}
function processSelectedData(json) {
    var html = "";
    var value = "";
    for(var i in json) {
        html = html + "<li class='region' id='"+json[i].value+"'><a class='region-close'>×</a>"+json[i].name+"</li>";
        value = value + json[i].value + ",";
    }
    $("#region-selected").append(html);
    $("#region_value").val(value);
    $(".region a").on("click",function(){
        var value = $(this).parent().attr("id");
        $(this).parent().remove();
        processForm("del",value);
        $('#all_Loc input[value='+value+']').prop("checked",false);
    });
}
// end地点选择

function geo_targeting(status){
    if (status=="off"){
        $("#geo_targeting_all").attr("checked", "true");
        document.getElementById('country_target').style.display='none';
    }
    if (status=="on"){
        $("#geo_targeting_co").attr("checked", "true");
        document.getElementById('country_target').style.display='block';
    }
}
function publication_targeting(status){
    if (status=="off"){
        $("#publication_targeting_all").attr("checked", "true");
        $("#publicationtargetingtable").addClass("hidden");
    }
    if (status=="on"){
        $("#publication_targeting_co").attr("checked", "true");
        $("#publicationtargetingtable").removeClass("hidden");
    }
}
function quality_targeting(status){
    if (status=="off"){
        $("#qualityTargetingAll").attr("checked", "true");
        $("#chooseQuality").addClass("hidden");
    }
    if (status=="on"){
        $("#qualityTargetingSome").attr("checked", "true");
        $("#chooseQuality").removeClass("hidden");
    }
}

//投放时间段
function time_targeting(status){
    if (status=="off"){
        $("#choose_time").addClass("hidden");
        $("#time_target").val(16777215);

        $("#choose_time li").each(function(i){
            if( $(this).attr("class") =="selected" ){
                $( this ).removeClass( "selected" );
            }
        });
    }
    if (status=="on"){
        $("#time_some").attr("checked", "true");
        $("#choose_time").removeClass("hidden");
        time_value();
    }
}
$("#time_all").click(function(){
    time_targeting("off");
});
$("#time_some").click(function(){
    time_targeting("on");
});

function time_value(){
    var value = 0;
    $("#choose_time li").each(function(i){
        if( $(this).attr("class") =="selected" ){
            value = value + Math.pow(2,i);
        }
    });
    $("#time_target").val(value);
}

//投放时间
$( "#choose_time li").click(function(){
    if($(this).hasClass("selected")){
        $( this ).removeClass( "selected" );
        time_value();
    }else{
        $( this ).addClass( "selected" );
        time_value();
    }
});


//选择媒体/广告位
$("#publication_targeting_all").click(function(){
    $("#publicationtargetingtable").addClass("hidden");
});
$("#publication_targeting_co").click(function(){
    $("#publicationtargetingtable").removeClass("hidden");
});
function click_placement_checkbox(placement_select){
    var v=$("#publication_selected_ids").val();
    if(placement_select.checked){
        if(v==''){
            v=","+placement_select.value+",";
        }else {
            v=v+placement_select.value+",";
        }
        $("#publication_selected_ids").val(v);
    }else {
        if(v.indexOf(","+placement_select.value+",") >=0){
            v=v.replace(","+placement_select.value+",",",");
        }
        $("#publication_selected_ids").val(v);
    }
}
function click_quality_checkbox(quality_select){
    var v=$("#quality_select_ids").val();
    if(quality_select.checked){
        if(v==''){
            v=","+quality_select.value+",";
        }else {
            v=v+quality_select.value+",";
        }
        $("#quality_select_ids").val(v);
    }else {
        if(v.indexOf(","+quality_select.value+",") >=0){
            v=v.replace(","+quality_select.value+",",",");
        }
        $("#quality_select_ids").val(v);
    }
}
//选择质量
$("#qualityTargetingAll").click(function(){
    $("#chooseQuality").addClass("hidden");
});
$("#qualityTargetingSome").click(function(){
    $("#chooseQuality").removeClass("hidden");
});
function click_quality_checkbox(quality_select){
    var v=$("#quality_select_ids").val();
    if(quality_select.checked){
        if(v==''){
            v=","+quality_select.value+",";
        }else {
            v=v+quality_select.value+",";
        }
        $("#quality_select_ids").val(v);
    } else {
        if(v.indexOf(","+quality_select.value+",") >=0){
            v=v.replace(","+quality_select.value+",",",");
        }
        $("#quality_select_ids").val(v);
    }
}

