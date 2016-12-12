//顶部弹出消息
(function(){
    var $popup_msg = $('#popup-msg'),
    hideTimer = null,
    hideInterval = 10000,
    minShowTime = 500,
    startTime = 0,
    clearHideTimer = function(){
        if (hideTimer) {
            window.clearTimeout(hideTimer);
            hideTimer = null;
        }
    };

    $popup_msg.delegate('.close', 'click', function(event){
        event.preventDefault();
        $popup_msg.hide();

    });

    function popup_msg(msg, type)
    {
        type = type || 'error';

        if (type == 'succ') type = 'success';

        msg =
        '<button type="button" class="close" style="font-size:16px;margin-top:-4px;">x</button>' +
        msg.replace(/<(?:div|p)[^>]*>/gi, '').replace(/<\/(?:div|p)>/gi, '<br/>').replace(/<br\/>\s*$/, '');

        $popup_msg.html(msg).show();
        $popup_msg.attr('class', 'alert alert-' + type);
        var left = ($(window).width() - ($popup_msg.attr('offsetWidth') || $popup_msg.prop('offsetWidth'))) / 2;
        $popup_msg.css('left', left);//.hide().slideDown();
        startTime = + new Date;
        clearHideTimer();

        if (type == 'success') {
            hideTimer = setTimeout(function(){ hide_msg() }, hideInterval);
        }
    }

    function hide_msg()
    {
        clearHideTimer();

        var showTime = + new Date - startTime;
        if (showTime < minShowTime) {
            hideTimer = setTimeout(function() { hide_msg() }, minShowTime - showTime);
            return;
        }
        $popup_msg.hide();
    }
    window.popup_msg = popup_msg;
    window.hide_popup_msg = hide_msg;


    $(document).delegate('input.numeric', 'keyup', function(event){
        var num = this.value.replace(/[^0-9.+-]+|(.)[+-]/, '$1');

        if (this.value != num) {
            this.value = num;
        }

    });

    $(document).delegate('form.ajax', 'submit', function(event){
        event.preventDefault();

        var base_version;
        if (/version=(\w+)/.test(location.search)) {
            if (!confirm('你当前提交不是基于最新版本的数据，是否继续？')) {
                return;
            }

            base_version = RegExp.$1;
        }

        popup_msg('数据保存中...', 'info');
        var $f = $(this);

        $f.trigger('before_submit');
        var $disabled = $f.find(':disabled[name]');
        $disabled.prop('disabled', false);
        var post_params = $f.serialize();
        $disabled.prop('disabled', true);

        if (!base_version) {
            base_version =
                (($('#version-list a[href*="version="]:eq(1)').attr('href') || '')
                .match(/version=(\w+)/) || [] )[1];

            if (base_version) {
                post_params += '&base_version=' + base_version;
            }
        }

        $.post($f.attr('action') || location.href, post_params, function(ret){
            if (ret.code != 0) {
                popup_msg(ret ? ret.msg : '发生异常错误', 'error');
            } else {

                $f.trigger('ajax_succ', ret);

                if (ret.msg) {
                    popup_msg(ret.msg, 'succ');

                    if (/version=\w+/.test(location.search) && !ret.redirect_uri) {
                        return location.replace(location.href.replace(/version=\w+(&)?/, '').replace(/[&?]$/, ''));
                    }
                }
            }

            if (ret && ret.redirect_uri) {

                hide_popup_msg();
                if (/javascript\s*:\s*(.+)/.test(ret.redirect_uri)) {
                    $.globalEval(RegExp.$1);
                } else {
                    return location.replace(ret.redirect_uri);
                }
            }

            if (ret && ret.code == 0) {
                //location.reload();
            }

        }, 'json').error(function(){
            popup_msg('服务器响应错误', 'error');
        });
    });

    //自动绑定日期控件
    $(document).delegate('input.datepicker', 'focus', function() {
        var $t = $(this);
        if ($t.data('datepicker')) {
            return;
        }
        $t.data('datepicker', 1);
        $t.datepicker({
            onSelect: function(){
                $(this).trigger("change");
            }
        });
    }).delegate('input.datetimepicker', 'focus', function(){
        var $t = $(this);

        if ($t.data('datetimepicker')) {
            return;
        }

        $t.data('datetimepicker', 1);
        $t.datetimepicker({
            timeFormat : 'HH',
            showMinute : false,
            showTime : false
        });
    });

    $(document).delegate('select.filter', 'change', function(){
        $(this).closest('form').trigger('submit');
    });


    //单选、勾选框点击触发显示框架
    function bind_toggle_trigger() {
        $(':checkbox[rel^=trigger-],:radio[rel^=trigger-]').not('.trigger-toggle').click(function(event, from_trigger){
            var $t = $(this), type = $t.attr('rel').replace(/^trigger-/, ''),
            $targets = $('[rel~=target-' + type + ']');
            $untargets = $('[rel~=untarget-' + type + ']');

            if ($t.is(':radio') && ! from_trigger) {
                $(':radio[name=' + this.name + ']').not($t).each(function(){
                    $(this).triggerHandler('click', true);
                });
            }

            if ($t.is(':checked')) {
                $targets.show();
                $untargets.hide();
            } else {
                $targets.each(function(){
                    var $t = $(this);
                    var all_unchecked = true;
                    $.each($t.attr('rel').split(/\s+/), function(i, target){
                        if (/target-(\S+)/.test(target)) {
                            if ($('[rel=trigger-' + RegExp.$1 + ']').prop('checked')) {
                                all_unchecked = false;
                                return false;
                            }
                        }
                    });
                    all_unchecked && $t.hide();
                });
                $untargets.show();
            }
        }).each(function(){
            $(this).addClass('trigger-toggle').triggerHandler('click');
        });
    }
    bind_toggle_trigger();


    var bind_select2 = function() {
        $('select[select2]').each(function(){
            var $sel = $(this);

            if ($sel.data('select2_bind')) {
                return;
            }
            $sel.data('select2_bind', 1);

            $sel.select2({
                allowClear: true
            });
        });
    };
    bind_select2();

    $(window).bind('ajax_load_page', function(){
        bind_toggle_trigger();
        bind_select2();
    });

})();

if($.datepicker) {
    $.datepicker.setDefaults({
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        nextText: '下一月',
        prevText: '上一月',
        dayNames: ['日', '一', '二', '三', '四', '五', '六'],
        dayNamesShort: ['日', '一', '二', '三', '四', '五', '六'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        currentText: '今天',
        closeText: '完成',
        firstDay: 1,
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 2,
        showOtherMonths: true,
        selectOtherMonths: false,
        showAnim: 'slideDown'
    });
};

function fnum(num)
{
     num = (Math.round(num * 100) / 100) + '';
     return num.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function load_partial(content_id)
{
    var url = location.href;

    url += (url.indexOf('?') > 0 ? '&' : '?') + 'r=' + (+ new Date);

    popup_msg('数据加载中...', 'info');

    $.ajax({
        url: url,
        beforeSend: function(jqXHR, settings) {
            jqXHR.setRequestHeader("Partial", content_id);
        },
        success: function(result) {
            $('[content-id=' + content_id + ']').html(result);
            hide_popup_msg();
        }
    });
}

function remove_list_item_val($input, item)
{
    var items = [];

    $.each($input.val().split(','), function(i, val) {
        if (val != item) {
            items.push(val);
        }
    });

    $input.val(items.join(','));
}

function do_ajax(url, params, on_success) {

    $.post(url, params || {}, function(ret) {
        if (ret && ret.code == 0) {
            on_success(ret);
        } else {
            popup_msg(ret ? ret.msg : '服务器响应错误', 'error');
        }
    }, 'json').error(function(){
        popup_msg('服务器响应错误', 'error');
    });
}

function trim(str) {
    return str.replace(/^\s+|\s+$/g, "");
}

function atrim(str) {
    return str.replace(/\s+/g, "");
}

function formatMoney(s, n)
{
    n = n > 0 && n <= 20 ? n : 2;
    s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
    var l = s.split(".")[0].split("").reverse(),
        r = s.split(".")[1];
    t = "";
    for(i = 0; i < l.length; i ++ )
    {
        t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
    }
    return t.split("").reverse().join("") + "." + r;
}

function numToCny( money )
{
    var cnNums = new Array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"); //汉字的数字
    var cnIntRadice = new Array("","拾","佰","仟"); //基本单位
    var cnIntUnits = new Array("","万","亿","兆"); //对应整数部分扩展单位
    var cnDecUnits = new Array("角","分","毫","厘"); //对应小数部分单位
    var cnInteger = "整"; //整数金额时后面跟的字符
    var cnIntLast = "元"; //整型完以后的单位
    var maxNum = 999999999999999.9999; //最大处理的数字

    var IntegerNum; //金额整数部分
    var DecimalNum; //金额小数部分
    var ChineseStr=""; //输出的中文金额字符串
    var parts; //分离金额后用的数组，预定义

    if( money == "" ){
        return "";
    }

    money = parseFloat(money);
    //alert(money);
    if( money >= maxNum ){
        $.alert('超出最大处理数字');
        return "";
    }
    if( money == 0 ){
        ChineseStr = cnNums[0]+cnIntLast+cnInteger;
        //document.getElementById("show").value=ChineseStr;
        return ChineseStr;
    }
    money = money.toString(); //转换为字符串
    if( money.indexOf(".") == -1 ){
        IntegerNum = money;
        DecimalNum = '';
    }else{
        parts = money.split(".");
        IntegerNum = parts[0];
        DecimalNum = parts[1].substr(0,4);
    }
    if( parseInt(IntegerNum,10) > 0 ){//获取整型部分转换
        zeroCount = 0;
        IntLen = IntegerNum.length;
        for( i=0;i<IntLen;i++ ){
            n = IntegerNum.substr(i,1);
            p = IntLen - i - 1;
            q = p / 4;
            m = p % 4;
            if( n == "0" ){
                zeroCount++;
            }else{
                if( zeroCount > 0 ){
                    ChineseStr += cnNums[0];
                }
                zeroCount = 0; //归零
                ChineseStr += cnNums[parseInt(n)]+cnIntRadice[m];
            }
            if( m==0 && zeroCount<4 ){
                ChineseStr += cnIntUnits[q];
            }
        }
        ChineseStr += cnIntLast;
    //整型部分处理完毕
    }
    if( DecimalNum!= '' ){//小数部分
        decLen = DecimalNum.length;
        for( i=0; i<decLen; i++ ){
            n = DecimalNum.substr(i,1);
            if( n != '0' ){
                ChineseStr += cnNums[Number(n)]+cnDecUnits[i];
            }
        }
    }
    if( ChineseStr == '' ){
        ChineseStr += cnNums[0]+cnIntLast+cnInteger;
    }
    else if( DecimalNum == '' ){
        ChineseStr += cnInteger;
    }
    return ChineseStr;

}

function inArray(needle, haystack) {
    var key = '';
    for (key in haystack) {
        if (haystack[key] == needle) {
            return true;
        }
    }

    return false;
}

function show_dialog(info, onConfirm, onCancel){
    info = info || '';
    $('#common_dialog_info').html(info);
    $('#common_dialog').modal('show');
    $('.common_dialog_confirm').click(function(){
        onConfirm();
    });
    $('.common_dialog_cancel').click(function(){
        onCancel();
    });
}

function hide_dialog(){
    $('#common_dialog').modal('hide');
}