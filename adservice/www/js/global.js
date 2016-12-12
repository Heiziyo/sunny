//菜单高亮显示
$(function(){
    var current_url = location.href.toLocaleLowerCase();

    var current_link = "";
    var num = 0;
    var first = 0;
    $("#menu ul a").each(function(){

        current_link = $(this).attr("href").toLocaleLowerCase();

        if (current_url.indexOf(current_link) != -1 ) {

            if(num === 0){
                $(this).addClass("active");
                num ++; first = $(this);
            } else if ($(this).attr("href").length > first.attr("href").length) {
                $(this).addClass("active");
                first.removeClass("active");first = $(this);
            }
        }

    });
    var num = 0;
    $("#menu>li>a").each(function(){
        current_link = $(this).attr("href").toLocaleLowerCase();

        if (current_url.indexOf(current_link) != -1 ) {

            if(num === 0){
                $(this).addClass("active");
                num ++; first = $(this);
            } else if ($(this).attr("href").length > first.attr("href").length) {
                $(this).addClass("active");
                first.removeClass("active");first = $(this);
            }
        }
    });

    if(first!=0){
        first.parent().parent().removeClass().addClass("panel-collapse in").prev().find("span").removeClass("fa-angle-right").addClass("fa-angle-down");
    }

    //菜单箭头
    $("#menu .accordion-toggle").click(function(){
        if( $(this).next().hasClass("in") ) {
            $(this).find("span.fa").addClass("fa-angle-right").removeClass("fa-angle-down");
        } else if( $(this).next().hasClass("collapse") ) {
            $(this).find("span.fa").addClass("fa-angle-down").removeClass("fa-angle-right");
        }
    });


    //操作提示框 如果没内容 不显示
    if($.trim( $("#info-content").html() ) == ""){
        $("#info-alert").remove();
    }

    //日期选择 默认时间
    d = new Date();
    var today = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();

    if($('.daterange-form').val()=="0" || $('.daterange-form').val()== ""){
        $(".daterange-form").val(today+" 至 " + today);
    }

    if($('.singleDatePicker').val()=="0" || $('.singleDatePicker').val()== "") {
        $('.singleDatePicker').val(today);
    }

    $('#daterange-btn #value').html($('#adunit-daterange').val());

    try{
        var dateRangeBtn = $('#adunit-daterange').val().split(" 至 ");
        //日期选择 起至日期 用在查看报表 每个页面只能有一个
        $('#daterange-btn').daterangepicker({
            startDate: dateRangeBtn[0],
            endDate: dateRangeBtn[1],
            ranges: {
                '昨天': [moment().subtract('days', 1), moment().subtract('days', 1)],
                '这个月': [moment().startOf('month'), moment().endOf('month')],
                '上个月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            maxDate: new Date()
        },function(start, end) {
            $('#daterange-btn #value').html(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
            $('#adunit-daterange').val(start.format('YYYY-MM-DD') + ' 至 ' + end.format('YYYY-MM-DD'));
        });

    }catch(e){}

    try{
        var dateRangeForm = $('.daterange-form').val().split(" 至 ");
        //日期选择 起至日期 用在form页面
        $('.daterange-form').daterangepicker({
            startDate: dateRangeForm[0],
            endDate: dateRangeForm[1]
        });
        $('.daterange-form').keydown(function(){return false;});
    }catch(e){}


    $('#test').daterangepicker();

    $('.singleDatePicker').daterangepicker({
        singleDatePicker: true,
        maxDate: new Date()
    });
    $('.singleDatePicker').keydown(function(){return false;});


//全选
    $('#check_all').change(function(){
        if($(this).prop('checked')==true){
            $('.check_me').prop('checked', true);
        }else{
            $('.check_me').prop('checked', false);
        }
    });

    //model弹框 如果一个页面有多个 remote弹框 避免内容cache
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
        $(".iframe-popup").empty();
    });

});

//set cookie 将已选中的unit存入
function setCookie(c_name,value,expiredays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate()+expiredays);
    document.cookie=c_name+ "=" +escape(value)+ ";path=/" +
        ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
}

//读取cookies
function getCookie(c_name)
{
    if (document.cookie.length>0)
    {
        c_start=document.cookie.indexOf(c_name + "=");
        if (c_start!=-1)
        {
            c_start=c_start + c_name.length+1;
            c_end=document.cookie.indexOf(";",c_start);
            if (c_end==-1) c_end=document.cookie.length;
            return unescape(document.cookie.substring(c_start,c_end));
        }
    }
    return "";
}
//获取url参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
//去除字符串首尾的逗号
function remove_head_tail_commas(str) {
    while (str.substr(0, 1) == ",") {
        str = str.substr(1, str.length - 1);
    }
    while (str.substr(-1, 1) == ",") {
        str = str.substr(0, str.length - 1);
    }

    return str;
}
//弹出框
function alertBox(title){

alert(title);
    return false;

}

//删除前的确认弹出框 删除按钮为链接
function deleleConfirm(target,title){

    $(target).click(function(){
        alertString = '<div class="modal fade" id="myModal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+title+'</h4></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">取 消</button><a id="del-id" class="btn btn-success">确 定</a></div></div></div></div>';

        $("#myModal").remove();
        $('.modal-backdrop').remove();
        $('body').append(alertString);
        $('#del-id').attr("href",$(this).attr('href') );
        $('#myModal').modal();
        return false;
    });

}
//操作前的确认,action为确认后要执行的js函数,parameter为action参数
function confirmAction(title,action,parameter){

    alertString = '<div class="modal fade" id="myModal"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+title+'</h4></div><div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">取 消</button><button id="doAction" type="button" class="btn btn-success">确 定</button></div></div></div></div>';

    $("#myModal").remove();
    $('.modal-backdrop').remove();
    $('body').append(alertString);
    $('#myModal').modal();
    $('#doAction').click(function(){
        action(parameter);
    });
}
//报表总数格式
function totalNumberFormat(value,type){

    var len = value.length, original_len = len;

    if(len>5){
        value = value.substr(0,value.length-3);
        len = len - 3;
    }

    var htmlValue = "";
    var arrayNum = value.split("");

    //如果超出8位数
    var j = 0; //用来取数字 arrayNum[j] 表示第几位
    if(len > 5) {
        var overlenth = len - 5;

        while (overlenth>0)
        {
            if(type == "total_uv") {
                htmlValue += "<li style='background-position: 0 -66px;'>";
            } else {
                htmlValue += "<li>";
            }

            htmlValue = htmlValue + arrayNum[j] + "</li>";

            overlenth--; j++;
        }
    }

    for(var i = 5; i >0; i--){

        if(i == 5 || i == 4){
            if(type == "total_uv"){
                htmlValue += "<li style='background-position: 0 -66px;'>";
            }else{
                htmlValue += "<li>";
            }
        }else{
            htmlValue += "<li style='background-position: 0 -33px;color: #666;'>";
        }

        if(i>len){
            htmlValue += "0</li>";
        }else{
            htmlValue = htmlValue + arrayNum[j] + "</li>";
            j++;
        }
    }
    $('#'+type).html(htmlValue);

    if(original_len > 5 ){
        $('#'+type).append("<li style='background-position: 0 -33px;color: #666;'>K</li>");
    }
}

//去除所有的FlashMessage Div
function RemoveFlashMessageBox() {
    msgBoxes = $(".noticeMessage");
    length = msgBoxes.length;
    for (var i = 0; i < length; i++) {
        msgBoxes[i].remove();
    }

    msgBoxes = $(".successMessage");
    length = msgBoxes.length;
    for (var i = 0; i < length; i++) {
        msgBoxes[i].remove();
    }

    msgBoxes = $(".errorMessage");
    length = msgBoxes.length;
    for (var i = 0; i < length; i++) {
        msgBoxes[i].remove();
    }
}

//分页用
var oCache = {
    iCacheLower: -1
};

function fnSetKey( aoData, sKey, mValue )
{
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
    {
        if ( aoData[i].name == sKey )
        {
            aoData[i].value = mValue;
        }
    }
}

function fnGetKey( aoData, sKey )
{
    for ( var i=0, iLen=aoData.length ; i<iLen ; i++ )
    {
        if ( aoData[i].name == sKey )
        {
            return aoData[i].value;
        }
    }
    return null;
}
// end

function popupInfoForLogin(message,baseUrl){
    if(message === "请重新登录！"){
        alertBox(message+"<a class='popUpLoginLink' href='"+baseUrl+"login/index'>点击这里重新登录</a>");
    } else{
        alertBox(message);
    }
}
