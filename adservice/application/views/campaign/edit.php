<link rel="stylesheet" type="text/css" href="/css/campaign/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/css/campaign/font-awesome.min.css?version=1.06">
<link rel="stylesheet" type="text/css" href="/css/campaign/style2.css">
<form class="form-horizontal">
<div class="panel-default clearfix">
    <div class="panel-body fixed-width">
        <input type="hidden" value=<?=$campaign_id ?> id="campaign_id">
        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>投放名称</label>
            <div class="controls">
                <input type="text" value="<?=$campaignInfo['campaign_name']?>" id="campaign_name" >
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">简介</label>
            <div class="controls">
                <textarea id="campaign_desc" rows="5" cols="30" ><?=$campaignInfo['campaign_desc']?></textarea>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>广告主</label>
            <div class="controls">
                <select id="belong_to_advertiser" >
                    <option value=''>请选择</option>
                    <?php foreach ($advertiserlList as $val): ?>
                    <option value=<?=$val['entry_id'] ?> <?php if($val['entry_id']==$campaignInfo['belong_to_advertiser'])echo "selected='selected'"; ?>><?=$val['name_zh'] ?></option>
                    <?php endforeach;?>            
                </select>
            </div>
        </div>
    
        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>订单 </label>
            <div class="controls">
                <select id="order_id" >
                         <option value=<?=$campaignInfo['order_id'] ?>><?=$campaignInfo['order_name']?></option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"> <span style="margin-right: 5px">*</span>优先级</label>
            <div class="controls">
                <select id="campaign_priority" >
                    <?php foreach ($campaignPriority as $key=> $val): ?>
                    <option value=<?=$key?> <?php if($key==$campaignInfo['campaign_priority']) echo "selected='selected'"; ?>><?=$val ?></option>
                    <?php endforeach;?>              
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>播放规则</label>
            <div class="controls">
                <?php foreach ($creativeShowRule as $key=> $val): ?>
                    <div class="radio-horizontal">
                    <input type="radio" name="creative_show_rule" value=<?=$key ?> <?php if($key==$campaignInfo['creative_show_rule']) echo "checked"; ?> > <?=$val ?>
                    </div>
                <?php endforeach;?>   
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>投放时间 </label>
            <div class="controls">
                <input type="text" id="campaign_date_range" value="<?=$campaignInfo['campaign_start'].' 至 '.$campaignInfo['campaign_end'];  ?>" class="daterange-form" >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>投放时间段</label>
            <div class="controls">
                <div class="radio-horizontal">
                    <input type="radio" id="time_all" name="choose_time_type" <?php if($campaignInfo['time_target']==16777215) echo 'checked="checked"' ?>>所有时间
                </div>
                <div class="radio-horizontal">
                    <input type="radio" id="time_some" name="choose_time_type" <?php if($campaignInfo['time_target']!=16777215) echo 'checked="checked"' ?>> 指定时间
                </div>
    
                <ul class="list-inline choose_time hidden clearfix" id="choose_time" style="clear: both">
                    <?php for($i=0;$i<24;$i++):?>
                    <li 
                        <?php 
                            $flag = substr($time_slot,$i,1);
                            if($flag==1) echo 'class="selected"'; ?>><?=$i ?></li>
                    <?php endfor;?>
                </ul>
                <div class="clearfix"><input type="hidden" id="time_target" value=""></div>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>曝光限制</label>
            <div class="controls">
                <input size="10" type="text"  min="1" max="10000000000" value=<?=$campaignInfo['total_amount'] ?> id="total_amount"  >
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>投放方式</label>
            <div class="controls">
                <?php foreach ($displayWay as $key=> $val): ?>
                    <div class="radio-horizontal">
                    <input type="radio" name="campaign_display_way" value=<?=$key ?> <?php if($key==$campaignInfo['campaign_display_way']) echo 'checked="checked"' ?> > <?=$val ?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    
        <div class="control-group hidden" id="paiqi_group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-6">
                <table id="paiqi" name="paiqi" class="table table-no-min"><thead>            <tr>                <th colspan="7" style="height:40px;padding-top:3px;border-right:none;">排期设置</th>            </tr>            </thead>            <tbody><tr>                <td colspan="7" style="border-right:none;height:30px;">                    <i style="cursor:pointer;" onclick="lastMonth()" class="fa fa-chevron-left pull-left"></i>                    <i style="cursor:pointer;" onclick="nextMonth()" class="fa fa-chevron-right pull-right"></i>                    <p id="month_text"><b>2016年10月</b></p>                </td>            </tr>            <tr class="date_week">                <td>周日</td>                <td>周一</td>                <td>周二</td>                <td>周三</td>                <td>周四</td>                <td>周五</td>                <td>周六</td>            </tr><tr class="date_row"><td><div><p onclick="select_date($(this))">25日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_25" value=""></div></td><td><div><p onclick="select_date($(this))">26日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_26" value=""></div></td><td><div><p onclick="select_date($(this))">27日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_27" value=""></div></td><td><div><p onclick="select_date($(this))">28日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_28" value=""></div></td><td><div><p onclick="select_date($(this))">29日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_29" value=""></div></td><td><div><p onclick="select_date($(this))">30日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_9_30" value=""></div></td><td><div><p onclick="select_date($(this))">10月1日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_1" value=""></div></td></tr><tr class="date_row"><td><div><p onclick="select_date($(this))">2日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_2" value=""></div></td><td><div><p onclick="select_date($(this))">3日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_3" value=""></div></td><td><div><p onclick="select_date($(this))">4日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_4" value=""></div></td><td><div><p onclick="select_date($(this))">5日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_5" value=""></div></td><td><div><p onclick="select_date($(this))">6日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_6" value=""></div></td><td><div><p onclick="select_date($(this))">7日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_7" value=""></div></td><td><div><p onclick="select_date($(this))">8日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_8" value=""></div></td></tr><tr class="date_row"><td><div><p onclick="select_date($(this))">9日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_9" value=""></div></td><td><div><p onclick="select_date($(this))">10日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_10" value=""></div></td><td><div><p onclick="select_date($(this))">11日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_11" value=""></div></td><td><div><p onclick="select_date($(this))">12日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_12" value=""></div></td><td><div><p onclick="select_date($(this))">13日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_13" value=""></div></td><td><div><p onclick="select_date($(this))">14日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_14" value=""></div></td><td><div><p onclick="select_date($(this))">15日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_15" value=""></div></td></tr><tr class="date_row"><td><div><p onclick="select_date($(this))">16日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_16" value=""></div></td><td><div><p onclick="select_date($(this))">17日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_17" value=""></div></td><td><div><p onclick="select_date($(this))">18日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_18" value=""></div></td><td><div><p onclick="select_date($(this))">19日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_19" value=""></div></td><td><div><p onclick="select_date($(this))">20日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_20" value=""></div></td><td><div><p onclick="select_date($(this))">21日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_21" value=""></div></td><td><div><p onclick="select_date($(this))">22日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_22" value=""></div></td></tr><tr class="date_row"><td><div><p onclick="select_date($(this))">23日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_23" value=""></div></td><td><div><p onclick="select_date($(this))">24日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_24" value=""></div></td><td><div><p onclick="select_date($(this))">25日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_25" value=""></div></td><td><div><p onclick="select_date($(this))">26日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_26" value=""></div></td><td><div><p onclick="select_date($(this))">27日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_27" value=""></div></td><td><div><p onclick="select_date($(this))">28日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_28" value=""></div></td><td><div><p onclick="select_date($(this))">29日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_29" value=""></div></td></tr><tr class="date_row"><td><div><p onclick="select_date($(this))">30日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_30" value=""></div></td><td><div><p onclick="select_date($(this))">31日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_10_31" value=""></div></td><td><div><p onclick="select_date($(this))">11月1日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_11_1" value=""></div></td><td><div><p onclick="select_date($(this))">2日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_11_2" value=""></div></td><td><div><p onclick="select_date($(this))">3日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_11_3" value=""></div></td><td><div><p onclick="select_date($(this))">4日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_11_4" value=""></div></td><td><div><p onclick="select_date($(this))">5日</p><input type="text" onchange="updateDateNumber($(this))" name="2016_11_5" value=""></div></td></tr></tbody></table>
            </div>
        </div>
    
    
    
        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>投放地区</label>
            <div class="controls">
                <div class="radio-horizontal">
                    <input type="radio" onclick="document.getElementById(&#39;country_target&#39;).style.display=&#39;none&#39;;" name="geo_targeting" id="geo_targeting_all" value="1" checked="checked" <?php if($campaignInfo['country_target']==1) echo "checked"; ?>> 所有地区
                </div>
                <div class="radio-horizontal">
                    <input type="radio" onclick="document.getElementById(&#39;country_target&#39;).style.display=&#39;block&#39;;" name="geo_targeting" id="geo_targeting_co" value="2" <?php if($campaignInfo['country_target']==2) echo "checked"; ?>> 指定地区
                </div>
            </div>
        </div>

        <div class="control-group hidden-group">
            <div class="col-sm-10 col-sm-offset-2">
                <div id="country_target" class="clearfix" <?php if($campaignInfo['country_target']!=2) echo  'style="display: none;"' ?>>
                    <input type="checkbox" id="region_select_all">全选
                    <ul class="regions-selections list-inline" id="region-selected">
                           
                    </ul>
                    <input type="hidden" id="region_value" >
                    <ul id="all_Loc" class="clearfix">
                   
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <div style="clear:both"></div>
        <div class="control-group">
            <label class="control-label"><span style="margin-right: 5px">*</span>媒体/广告位 </label>
            <div class="col-sm-8">
                <div id="country_target" class="clearfix">
                    <div id="publicationtargetingtable">
                        <div class="table-w">
    						<table id="zonetable_sort" class="table table-no-min dataTable">
    							<tbody role="alert" aria-live="polite" aria-relevant="all">
    							<?php foreach ($zonesList as $val):?>
    							     <?php if(!empty($val['zones'])):?>
    							     <tr class="odd">
    									<td width="45%" class=" sorting_1"><div><?=$val['media_name'] ?></div></td>
    									<td width="53%" class=" ">
    									<?php foreach ($val['zones'] as $val2):?>
    									   
    									   <div><input value="<?=$val2['entry_id'] ?>" <?php if(in_array($val2['entry_id'],$zones_id_array)) echo 'checked="checked"'; ?> class="pub_<?=$val['media_id'] ?>" name="placement_select[ ]" type="checkbox" onclick="click_placement_checkbox(this);"><?=$val2['zone_name'].'('.$val2['name'].')'?></div>
    									<?php endforeach; ?>
    									</td>
    								</tr>
    								<?php endif;?>
    							<?php endforeach;?>
    							</tbody>
    						</table>
    						<div class="row">
    							<div class="col-xs-6">
    								<div id="zonetable_sort_length" class="dataTables_length"><label>
    								</div>
    							</div>
    						</div>
    					</div>
    				</div>
    			</div> <!-- publicationtargetingtable -->
                    <input type="hidden" value=<?=$zones_id_string ?> id="publication_selected_ids">
            </div>
        </div>
    </div>
    <div class="control-group">
         <div class="controls">
                <input id="btn_save" type="button" class="btn btn-primary" value="保 存" onclick="CreateCampaign()">
         </div>
    </div>
</div>
</form>





<script type="text/javascript" src="/js/campaign/campaign.js"></script>
<script type="text/javascript">

    //设置元素默认值
    $(function(){
        //投放时间段
    	<?php if($campaignInfo['time_target']!=16777215): ?>
        time_targeting("on");
        <?php else: ?>
        time_targeting("off");
        <?php endif;?>
        
        //媒体
       // publication_targeting("on");
        //品质
        <?php //if($campaignInfo['quality_target']==Model_Campaign::quality_target_two): ?>
        //quality_targeting("on");
        <?php //else: ?>
        quality_targeting("off");
        <?php //endif;?>
        });

    //投放地区
    var selecteddata = {items:<?=$city_json ?>};    var location_data = {items:<?=$area ?>};
    //已经选择的地区
    processSelectedData(selecteddata.items);

    $(document).ready(function(){

        $('#belong_to_advertiser').change(function(){
            var advertiser_id = $(this).val();
            $.ajax({
                url : "/advertise/orders/getList",
                data : {'advertiser_id':advertiser_id},
                cache : false,
                type : 'get',
                success : function (data){
                    var result = JSON.parse(data);
                    var data = result.data;
                    $("#order_id option").remove();
                    $.each(data,function (index, obj) {
                    	$('#order_id').append("<option value='"+obj.id+"'>"+obj.name+"</option>");
                    });
                }
            });
            
        });




        

        
        $("input[name='campaign_display_way']").click(function() {
            var displayWay = $("input[name='campaign_display_way']:checked").val();
            selectAllDateInRange();
            showDateDiv(displayWay);
        });
       
        $('#total_amount').change(function() {
            getAvgNumber();
        });

        //如果日期发生变化，则自动选定全部日期
        $('#campaign_date_range').on('apply.daterangepicker', function(ev, picker) {
            selectAllDateInRange();
            load_date_list(yearNow, monthNow);
        });

        load_date_list(0, 0);

        var displayWay = $("input[name='campaign_display_way']:checked").val();
        showDateDiv(displayWay);
    });


    //选定日期范围内的全部日期并设置为平均值
    function selectAllDateInRange() {
        var campaignDisplayWay = $("input[name='campaign_display_way']:checked").val();
        //集中播放不计算平均值
        if (campaignDisplayWay == 2 || campaignDisplayWay == 1) {
            return;
        }
        var campaignDate = $('#campaign_date_range').val();
        var dateStart = campaignDate.split(' 至 ')[0];
        var dateEnd = campaignDate.split(' 至 ')[1];

        var timeCampaignStart = new Date(dateStart.split('-')[0], parseInt(dateStart.split('-')[1]) - 1, dateStart.split('-')[2]).getTime();
        var timeCampaignEnd = new Date(dateEnd.split('-')[0], parseInt(dateEnd.split('-')[1]) - 1, dateEnd.split('-')[2]).getTime();

        var days = (timeCampaignEnd - timeCampaignStart) / (24 * 3600 * 1000) + 1;

        var totalNumber = $('#total_amount').val();
        var number = parseInt(totalNumber / days);

        dateAndNumberJsonObj.items.splice(0, dateAndNumberJsonObj.items.length);
        var timeNow = new Date(timeCampaignStart);
        while (timeNow.getTime() <= timeCampaignEnd) {
            var year = timeNow.getFullYear();
            var month = timeNow.getMonth() + 1;
            var date = year + '_' + month + '_' + timeNow.getDate();
            dateAndNumberJsonObj.items.push({date : date, value : number});
            timeNow.setTime(timeNow.getTime() + 24 * 3600 * 1000);
        }
        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);
        $('#total_amount').val(days * number);
    }

    function showDateDiv(displayWay) {
       if (displayWay == '3') {
            $('#paiqi tr.date_row>td>div.date_choose>input').css('background-color', '');
            $('#paiqi tr.date_row>td>div.date_choose>input').prop('disabled', false);
            $('#paiqi tr.date_row>td>div>p').css('cursor', 'pointer');
            $('#paiqi_group').removeClass('hidden');
        } else {
            $('#paiqi_group').addClass('hidden');
        }
    }

    function select_date(obj) {
        div = obj.parent().eq(0);
        input = div.children().eq(1);

        //检查日期是否在投放日期范围内
        if (checkDate(input.attr("name")) == false) {
            return;
        }

        //平均投放下不允许添加、去除某一天
        var campaignDisplayWay = $("input[name='campaign_display_way']:checked").val();
        if (campaignDisplayWay == 1) {
            return;
        }

        //在编辑投放状态下，不允许修改当天排期
        

        if (div.hasClass("date_choose")) {
            div.removeClass("date_choose");
            input.val('');
            removeDateFromList(input.attr("name"));
        } else {
            div.addClass("date_choose");
            if (input.val() == '') {
                input.val('0');
            }
            addDateToList(input.attr("name"), input.val());
        }

        updateNumberFiled();
    }

    var yearNow = '';
    var monthNow = '';
    var dateAndNumberJson = '{"items":<?=$paiqi ?>}';
    var dateAndNumberJsonObj = JSON.parse(dateAndNumberJson);
    function addDateToList(date, value) {
        dateAndNumberJsonObj.items.push({date : date, value : value});
        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);
    }
    function removeDateFromList(date) {
        for(var index in dateAndNumberJsonObj.items) {
            if (dateAndNumberJsonObj.items[index].date == date) {
                dateAndNumberJsonObj.items.splice([index], 1);
            }
        }
        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);
    }
    function updateDateNumber(input) {
        for(var index in dateAndNumberJsonObj.items) {
            if (dateAndNumberJsonObj.items[index].date == input.attr("name")) {
                dateAndNumberJsonObj.items[index].value = input.val();
            }
        }
        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);

        updateNumberFiled();
    }
    function checkDate(date) {
        var campaignDate = $('#campaign_date_range').val();
        var dateStart = campaignDate.split(' 至 ')[0];
        var dateEnd = campaignDate.split(' 至 ')[1];

        var timeCampaignStart = new Date(dateStart.split('-')[0], parseInt(dateStart.split('-')[1]) - 1, dateStart.split('-')[2]).getTime();
        var timeCampaignEnd = new Date(dateEnd.split('-')[0], parseInt(dateEnd.split('-')[1]) - 1, dateEnd.split('-')[2]).getTime();

        var timeChooseDate = new Date(date.split('-')[0], parseInt(date.split('-')[1]) - 1, date.split('-')[2]).getTime();
        if (timeChooseDate >= timeCampaignStart && timeChooseDate <= timeCampaignEnd) {
            return true;
        }

        return false;
    }

    //求各选定日期平均值
    function getAvgNumber() {
        var campaignDisplayWay = $("input[name='campaign_display_way']:checked").val();
        //集中播放不计算平均值
        if (campaignDisplayWay == 2) {
            return;
        }
        var totalNumber = $('#total_amount').val();

        var length = dateAndNumberJsonObj.items.length;
        if (length == 0) {
            return;
        }
        var number = parseInt(totalNumber / length);
        for(var index in dateAndNumberJsonObj.items) {
            dateAndNumberJsonObj.items[index].value = number;
        }
        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);
        $('#total_amount').val(length * number);
        load_date_list(yearNow, monthNow);
    }




    
    //更新排期数字输入框数值
    function updateNumberFiled() {
        var totalNumber = $('#total_amount').val();
        var val = $("input[name='campaign_display_way']:checked").val();
        var length = dateAndNumberJsonObj.items.length;
        if (length == 0) {
            return;
        }
        //更新json对象值
        if (val == '1') {
            var number = parseInt(totalNumber / length);
            for(var index in dateAndNumberJsonObj.items) {
                dateAndNumberJsonObj.items[index].value = number;
            }
            $('#total_amount').val(length * number);
        } else if (val == '3') {
            var totalFiledNumber = 0;
            for(var index in dateAndNumberJsonObj.items) {
                totalFiledNumber += parseInt(dateAndNumberJsonObj.items[index].value);
            }
            if (totalFiledNumber != totalNumber) {
                $('#total_amount').val(totalFiledNumber);
            }
        }

        dateAndNumberJson = JSON.stringify(dateAndNumberJsonObj);
        load_date_list(yearNow, monthNow);
    }

    function lastMonth() {
        if (monthNow == 1) {
            yearNow = yearNow - 1;
            monthNow = 12;
        } else {
            monthNow = monthNow - 1;
        }
        load_date_list(yearNow, monthNow);
    }
    function nextMonth() {
        if (monthNow == 12) {
            yearNow = yearNow + 1;
            monthNow = 1;
        } else {
            monthNow = monthNow + 1;
        }
        load_date_list(yearNow, monthNow);
    }


    

    function load_date_list(year, month) {
        var nowDate = '';
        if (year == 0 || month == 0) {
            //获取当前月份
            nowDate = new Date();
            yearNow = nowDate.getFullYear();
            monthNow = nowDate.getMonth() + 1;
        }
        //取本月第一天
        nowDate = new Date(yearNow, monthNow - 1);

        //判断第一天是周几
        var firstDayOfWeek = nowDate.getDay();

        var tableHeader = '<thead>\
            <tr>\
                <th colspan="7" style="height:40px;padding-top:3px;border-right:none;">排期设置</th>\
            </tr>\
            </thead>\
            <tr>\
                <td colspan="7" style="border-right:none;height:30px;">\
                    <i style="cursor:pointer;" onclick="lastMonth()" class="fa fa-chevron-left pull-left"></i>\
                    <i style="cursor:pointer;" onclick="nextMonth()" class="fa fa-chevron-right pull-right"></i>\
                    <p id="month_text"><b>' + yearNow + '年' + monthNow + '月</b></p>\
                </td>\
            </tr>\
            <tr class="date_week">\
                <td>周日</td>\
                <td>周一</td>\
                <td>周二</td>\
                <td>周三</td>\
                <td>周四</td>\
                <td>周五</td>\
                <td>周六</td>\
            </tr>';

        var tableHTML = "";
        tableHTML += tableHeader;
        var campaignDisplayWay = $("input[name='campaign_display_way']:checked").val();
        var inputBgTransparent = '';
        var disableInput = '';
        if (campaignDisplayWay == '1') {
            inputBgTransparent = ' style="background-color:transparent;"';
            disableInput = ' disabled="disabled"';
        }
        for (i = 0; i < 6; i++) {
            var end = false;
            var tableBody = '<tr class="date_row">';
            //本月总天数
            var thisMonthDays = new Date(yearNow, monthNow, 0).getDate();
            //计算日期
            for (j = 0; j < 7; j++) {
                var yearNum = yearNow;
                var monthNum = monthNow;
                var dayNum = i * 7 + j + 1 - firstDayOfWeek;
                if (dayNum <= 0) {
                    //如果是上一月的某一天
                    var firstDay = new Date(yearNow, monthNow - 1, 0);
                    yearNum = firstDay.getFullYear();
                    monthNum = firstDay.getMonth() + 1;
                    var lastMonthDays = firstDay.getDate();
                    dayNum = lastMonthDays + dayNum;
                }
//                if (i != 0 && j == 0 && dayNum > thisMonthDays) {
//                    //如果已超出本月最后一天，则不显示新行
//                    end = true;
//                    break;
//                }
                if (i != 0 && dayNum > thisMonthDays) {
                    //如果是下一月的某一天
                    var firstDay = new Date(yearNow, monthNow + 1, 0);
                    yearNum = firstDay.getFullYear();
                    monthNum = firstDay.getMonth() + 1;
                    dayNum = dayNum - thisMonthDays;
                }
                var inputName = yearNum + '-' + monthNum + '-' + dayNum;
                var selectClass = '';
                var value = '';
                for(var index in dateAndNumberJsonObj.items) {
                    if (dateAndNumberJsonObj.items[index].date == inputName) {
                        selectClass = ' class="date_choose"';
                        value = dateAndNumberJsonObj.items[index].value;
                    }
                }
                if (dayNum == 1) {
                    dayNum = monthNum + "月" + dayNum;
                }
                tableBody += '<td><div' + selectClass + '><p onclick="select_date($(this))">' + dayNum + '日</p><input type="text"' + inputBgTransparent + disableInput + ' onchange="updateDateNumber($(this))" name="' + inputName + '" value="' + value + '" style="width:100px"  /></div></td>';
            }
            tableBody += '</tr>';
            tableHTML += tableBody;

            if (end) {
                break;
            }
        }

        $("#paiqi").html(tableHTML);
    }

</script>
<script type="text/javascript">
function CreateCampaign() {
    var campaign_name = $("#campaign_name").val();
    var campaign_desc = $("#campaign_desc").val();
    var order_id = $("#order_id").val();
    var belong_to_advertiser = $("#belong_to_advertiser").val();
    var campaign_priority = $("#campaign_priority").val();
    var creative_show_rule = $("input[type='radio'][name='creative_show_rule']:checked").val();
    var campaign_date_range = $("#campaign_date_range").val();
    
    var campaign_display_way = $("input[type='radio'][name='campaign_display_way']:checked").val();
    var total_amount = $("#total_amount").val();
    var geo_targeting = $("input[type='radio'][name='geo_targeting']:checked").val();
    var region_value = remove_head_tail_commas($("#region_value").val());
    var publication_selected_ids = remove_head_tail_commas($("#publication_selected_ids").val());
    //var quality_targeting = $("input[type='radio'][name='quality_targeting']:checked").val();
    //var quality_select_ids = remove_head_tail_commas($("#quality_select_ids").val());//创意
    var time_target = $("#time_target").val();
    var time_slot_time = [];
    //var time_slot = '';
    var campaign_id = $("#campaign_id").val();
    var creative_name = $("#creative_name").val();
    
//     if(time_target!=16777215)
//     {
//     	var time_target = 2;
//     	var time_slot_selected = $("#choose_time li[class='selected']");
//     	$.each(time_slot_selected,function (index, obj) {
//     		time_slot_time.push($(this).text());
//         });
//     	time_slot = JSON.stringify(time_slot_time);  	
//     }
    
    if (campaign_name == "") {
    alert("请输入投放名称。");
    return false;
    }
    if (campaign_id == "") {
        alert("系统异常");
        return false;
        }
    
    if (belong_to_advertiser == "") {
    alert("请输入广告主。");
    return false;
    }
    if (campaign_priority == null || campaign_priority == "") {
    alert("请选择优先级。");
    return false;
    }
    if (creative_show_rule == null || creative_show_rule == "") {
    alert("请选择播放规则。");
    return false;
    }
    if (campaign_date_range == "") {
    alert("请选择投放开始-结束日期。");
    return false;
    }
    if (time_target == "") {
    alert("请选择投放时间段。");
    return false;
    }
    if (campaign_display_way == null || campaign_display_way == "") {
    alert("请选择投放方式。");
    return false;
    }
    if (total_amount == "") {
    alert("请输入曝光限制。");
    return false;
    }
    if (geo_targeting == null || geo_targeting == "") {
    alert("请选择投放地区。");
    return false;
    }
    if (publication_selected_ids == null || publication_selected_ids == "") {
    alert("请选择投放媒体/广告位。");
    return false;
    }
//     if (quality_targeting == null || quality_targeting == "") {
//     alert("请选择投放设备品质。");
//     return false;
//     }
    
    
    var params = {
                //投放
    'campaign_name' : campaign_name,//投放名称
    'campaign_desc' : campaign_desc,//简介
    'belong_to_advertiser' : belong_to_advertiser,//广告主
    'order_id':order_id,
    'campaign_priority' : campaign_priority,//优先级
    'creative_show_rule' : creative_show_rule,//播放方式
    'campaign_date_range' : campaign_date_range,//投放日期
    'time_target' : time_target,//投放时间类型
    //'time_slot':time_slot,//投放时间段选择
    'campaign_display_way' : campaign_display_way,//投放方式
    'total_amount' : total_amount,//曝光次数
    'country_target' : geo_targeting,//投放区域规则
    'city' : region_value,//投放的城市
    'zones_id' : publication_selected_ids,//广告位数组
    //'quality_target' : quality_targeting,//设备品质
    //'device_id' : quality_select_ids,//设备选择
    'campaign_id': campaign_id,
    'paiqi' : dateAndNumberJson
    };

        $.ajax({
            url : "/advertise/campaign/add",
            data : params,
            cache : false,
            type : 'POST',
            success : function (data){
            	var result = JSON.parse(data);
                if(result.success)
                {
                	popup_msg(result.msg,'succ');
                	window.location.href = '/advertise/campaign';
                }else{
                	popup_msg(result.msg,'error');
                }
            }
        });
    }
    $(document).ready(function(){
        var rightHeightValue = $('#right').height();
        $('#right').height(rightHeightValue);
    });
</script>
            </div>
        </td>
    </tr>
</tbody></table>



<script type="text/javascript">
   	$(document).ready(function(){
        if ($(window).height() > $('#right').height() + 54) {
            var contentHeight = $(window).height();
        } else {
            var contentHeight = $('#right').height() + 54;
        }
        $('#left').height(contentHeight);

        // 自动适应屏幕
        $('#right').width((1 - 170 / $(window).width()) * 100 + "%");
        $(window).trigger('resize'); // highchart 自适应
        $(window).on('resize', function() {
            $('#right').width((1 - 170 / $(window).width()) * 100 + "%");
            if ($(window).height() > $('#right').height() + 54) {
                var contentHeight = $(window).height();
            } else {
                var contentHeight = $('#right').height() + 54;
            }
            $('#left').height(contentHeight);
        });
	});

	//左侧菜单栏上下拖动一起动，左右拖动覆盖右侧内容
	$(window).scroll(function(){
	    $("#left").css({"margin-left": ($(window).scrollLeft()) + "px"});
	 });

    $(function(){
        //tool tip
        $('.btn-group a').tooltip({
            placement:"bottom"
        });
        //隐藏边栏
        var leftState = true;
        $('#changeSidebarPos').click(function() {
            if (leftState === true) { // 展开状态
                $("#right").animate({left: "0", width: "100%"}, "slow");
                leftState = false;
            } else {
                var rightWidthValue = $(window).width() - 169;
                $("#right").animate({left: "169px", width: rightWidthValue}, "slow");
                leftState = true;
            }
            $( "#left" ).toggle( "slow" );
        });

    });
</script>

<script type="text/javascript" src="/js/campaign/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/campaign/daterangepicker-bs3.css">
<link rel="stylesheet" type="text/css" href="/css/campaign/dataTables.bootstrap.css">
<script type="text/javascript" src="/js/campaign/moment.min.js"></script>
<script type="text/javascript" src="/js/campaign/daterangepicker.js"></script>
<script type="text/javascript" src="/js/campaign/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/campaign/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="/js/campaign/dataTables.formattedNum.js"></script>
<script type="text/javascript" src="/js/campaign/global.js"></script>
