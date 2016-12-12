<link rel="stylesheet" type="text/css" href="/css/units.css">
<link rel="stylesheet" type="text/css" href="/libs/bootstrap/css/bootstrap.min.css?version=1.06">
<link rel="stylesheet" type="text/css" href="/libs/font-awesome/css/font-awesome.min.css?version=1.06">
<script type="text/javascript" src="/libs/jquery.min.js?version=1.06"></script>
<div id="content">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <script type="text/javascript" src="/libs/md5.js?version=1.06"></script>
        <div class="panel panel-default clearfix">
            <div class="panel-body fixed-width">
                <div class="form-group">
                    <label class="col-sm-2 control-label">素材名称 <span class="font-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" value="<?= @$unitsData['adv_name']; ?>" name="adv_name"
                               id="creative_name"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">素材权重 <span class="font-danger">*</span></label>
                    <div class="col-sm-4">
                        <select id="creative_weight" name="creative_weight" class="form-control" style="width: 80px">
                            <option
                                value="1" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 1): ?>  selected="selected" <?php endif; ?>>
                                1
                            </option>
                            <option
                                value="2" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 2): ?>  selected="selected" <?php endif; ?>>
                                2
                            </option>
                            <option
                                value="3" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 3): ?>  selected="selected" <?php endif; ?>>
                                3
                            </option>
                            <option
                                value="4" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 4): ?>  selected="selected" <?php endif; ?>>
                                4
                            </option>
                            <option
                                value="5" <?php if (!@$unitsData['creative_weight'] || @$unitsData['creative_weight'] == 5): ?>  selected="selected" <?php endif; ?>>
                                5
                            </option>
                            <option
                                value="6" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 6): ?>  selected="selected" <?php endif; ?>>
                                6
                            </option>
                            <option
                                value="7" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 7): ?>  selected="selected" <?php endif; ?>>
                                7
                            </option>
                            <option
                                value="8" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 8): ?>  selected="selected" <?php endif; ?>>
                                8
                            </option>
                            <option
                                value="9" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 9): ?>  selected="selected" <?php endif; ?>>
                                9
                            </option>
                            <option
                                value="10" <?php if (@$unitsData['creative_weight'] && @$unitsData['creative_weight'] == 10): ?>  selected="selected" <?php endif; ?>>
                                10
                            </option>
                        </select>
                        <span class="help-block">数字越大，权重越高</span>
                    </div>

                </div>
                <?php if (!@$_GET['campaign_id'] && !@$unitsData['campaign_id']) : ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">投放<span class="font-danger">*</span></label>
                        <div class="col-sm-4">
                            <select id="campaign_id" name="campaign_id" class="form-control" style="width: 80px">
                                <?php foreach ($campaign as $campaignKey => $campaignVal) : ?>
                                    <?php echo '<option value="' . $campaignKey . '">' . $campaignVal . '</option>'; ?>
                                <?php endforeach; ?>
                            </select>
                            <span class="help-block">请选择投放</span>
                        </div>

                    </div>
                <?php else : ?>
                    <input type="hidden" name="campaign_id" id="campaign_id"
                           value="<?php echo @$_GET['campaign_id'] ? @$_GET['campaign_id'] : @$unitsData['campaign_id'] ?>">
                <?php endif ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label">素材时间 <span class="font-danger">*</span></label>
                    <div class="col-sm-4">
                        <input type="text" id="creative_date_range"
                            <?php if (@$unitsData['adv_id]']) :?> value="<?php echo @$unitsData['adv_start'] . '至' . @$unitsData['adv_end'] ?>"
                            <?php endif; ?>
                               name="creative_date_range" class="form-control daterange-form">
                        <span class="help-block">素材时间必须在投放时间范围内</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">素材类型 <span class="font-danger">*</span></label>
                    <div class="col-sm-4">
                        <div class="radio-horizontal">
                            <input
                                type="radio" <?php if (@$unitsData['creative_unit_type'] && @$unitsData['creative_unit_type'] == 'banner'): ?>  checked="" <?php endif; ?>
                                name="creative_unit_type" id="zone_type_banner" value="banner"
                                onclick="creative_unit_type_targeting(this.value);" checked="checked"> 条形横幅
                        </div>
                        <div class="radio-horizontal">
                            <input
                                type="radio" <?php if (@$unitsData['creative_unit_type'] && @$unitsData['creative_unit_type'] == 'interstitial'): ?>  checked="" <?php endif; ?>
                                name="creative_unit_type" id="zone_type_interstitial" value="interstitial"
                                onclick="creative_unit_type_targeting(this.value);"> 全屏
                        </div>
                        <div class="radio-horizontal">
                            <input
                                type="radio" <?php if (@$unitsData['creative_unit_type'] && @$unitsData['creative_unit_type'] == 'mini_interstitial'): ?>  checked=""<?php endif; ?>
                                name="creative_unit_type" id="zone_type_mini_interstitial" value="mini_interstitial"
                                onclick="creative_unit_type_targeting(this.value);"> 小屏广告
                        </div>
                        <div class="radio-horizontal">
                            <input
                                type="radio" <?php if (@$unitsData['creative_unit_type'] && @$unitsData['creative_unit_type'] == 'open'): ?>  checked="" <?php endif; ?>
                                name="creative_unit_type" id="zone_type_open" value="open"
                                onclick="creative_unit_type_targeting(this.value);"> 开机广告
                        </div>
                    </div>
                </div>

                <div id="zone_size_banner" class="field-group">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">素材尺寸 <span class="font-danger">*</span></label>
                        <div class="col-sm-5">
                            <div id="custom_size_div" class="field">
                                <input class="form-control" style="width: 70px; display: inline-block" type="text"
                                       value="<?php echo @$unitsData['adv_width'] ?>" name="adv_width" id="custom_width"
                                       size="3">
                                <label>宽度<font color="red">*</font></label> x
                                <input class="form-control" style="width: 70px; display: inline-block" type="text"
                                       value="<?php echo @$unitsData['adv_height'] ?>" name="adv_height" id="custom_height"
                                       size="3">
                                <label>高度<font color="red">*</font></label>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="uploadMedia">
                    <div id="creative_upload_div" class="form-group">
                        <label class="col-sm-2 control-label">素材素材 <span class="font-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="file" name="creative_file"
                                   id="creative_file"><?php if(@$unitsData['adv_creative_url']) {echo '素材路径：' . @$unitsData['adv_creative_url'] ;}?>
                            <?php if (@$unitsData['adv_creative_url']) :?>
                                <input type="hidden" id="creative_file1" value="1">
                            <?php endif;?>
                            <br>素材必须是图片或视频
                        </div>
                    </div>

                    <div id="click_url_div" class="form-group">
                        <label class="col-sm-2 control-label">点击事件<span class="font-danger"> &nbsp</span></label>
                        <div class="col-sm-7">
                            <input type="text" name="adv_click_url" value="<?php echo @$unitsData['adv_click_url'] ?>"
                                   id="click_url" class="form-control"
                                   style="float: left; width: 701px;">
                        </div>
                    </div>
                </div>


                <div id="third-party-code">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">第三方统计<span class="font-danger"> &nbsp</span></label>
                        <div class="col-sm-8">
                            <input type="text" value="<?php echo @$unitsData['adv_impression_tracking_url'] ?>"
                                   name="adv_impression_tracking_url" id="tracking_pixel"
                                   class="form-control">
                            <label class="help-block">秒针监测代码</label>
                            <input type="text" value="<?php echo @$unitsData['adv_impression_tracking_url_iresearch'] ?>"
                                   name="adv_impression_tracking_url_iresearch"
                                   id="tracking_url_iresearch" class="form-control">
                            <label class="help-block">艾瑞监测代码</label>
                            <input type="text" value="<?php echo @$unitsData['adv_impression_tracking_url_admaster'] ?>"
                                   name="adv_impression_tracking_url_admaster"
                                   id="tracking_url_admaster" class="form-control">
                            <label class="help-block">Admaster</label>
                            <input type="text" value="<?php echo @$unitsData['adv_impression_tracking_url_nielsen'] ?>"
                                   name="adv_impression_tracking_url_nielsen"
                                   id="tracking_url_admaster_jiami" class="form-control">
                            <label class="help-block">Admaster(加密)</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--        <script>
            var clickUrlArr;
            var clickUrlTypeNowIndex = 0;

            $("#display-creative-weight").val($("#creative_weight").val());
            $("#display-creative-weight").change(function () {
                $("#creative_weight").val($(this).val());
            });
/*
            function creative_unit_type_targeting(type) {
                switch (type) {
                    case "banner" :
                        $("#zone_type_banner").attr("checked", "true");
                        $("#zone_size_banner").removeClass("hidden");
                        $("#zone_size_mini_interstitial").addClass("hidden");
                        $("#chooseMediaType").removeClass("hidden");
                        $("#uploadMedia").removeClass("hidden");
                        $("#uploadMediaForOpen").addClass("hidden");
                        $("#picInfoLabel").html("素材必须是图片");
                        break;
                    case "interstitial" :
                        $("#zone_type_interstitial").attr("checked", "true");
                        $("#zone_size_banner").addClass("hidden");
                        $("#zone_size_mini_interstitial").addClass("hidden");
                        $("#chooseMediaType").removeClass("hidden");
                        $("#uploadMedia").removeClass("hidden");
                        $("#uploadMediaForOpen").addClass("hidden");
                        $("#picInfoLabel").html("素材必须是图片或视频");
                        break;
                    case "mini_interstitial" :
                        $("#zone_type_banner").attr("checked", "true");
                        $("#zone_size_banner").removeClass("hidden");
                        $("#zone_size_mini_interstitial").addClass("hidden");
                        $("#chooseMediaType").removeClass("hidden");
                        $("#uploadMedia").removeClass("hidden");
                        $("#uploadMediaForOpen").addClass("hidden");
                        $("#picInfoLabel").html("素材必须是图片");
                        break;
                    case "open" :
                        $("#zone_type_interstitial").attr("checked", "true");
                        $("#zone_size_banner").addClass("hidden");
                        $("#zone_size_mini_interstitial").addClass("hidden");
                        $("#chooseMediaType").removeClass("hidden");
                        $("#uploadMedia").removeClass("hidden");
                        $("#uploadMediaForOpen").addClass("hidden");
                        $("#picInfoLabel").html("素材必须是图片或视频");
                        break;
                }
            }
*/


            //设置元素默认值
            $(function () {
                //素材类型
                creative_unit_type_targeting("banner");
            });

        </script>
-->
        <div class="form-group">
            <div class="col-sm-4">
                <input id="btn_save" type="button" class="btn btn-primary" value="保 存" onclick="CreateCreative()">
            </div>
        </div>
    </form>

    <script type="text/javascript">
        function CreateCreative() {
            var campaign_id = $("#campaign_id").val();
            //素材
            var creative_name = $("#creative_name").val();
            var creative_weight = $("#creative_weight").val();
            var creative_date_range = $("#creative_date_range").val();
            var creative_unit_type = $("input[type='radio'][name='creative_unit_type']:checked").val();
            var creative_format = $("#creative_format").val();
            var custom_width = $("#custom_width").val();
            var custom_height = $("#custom_height").val();
            var creative_format_mini_interstitial = $("#creative_format_mini_interstitial").val();
            var custom_width_mini_interstitial = $("#custom_width_mini_interstitial").val();
            var custom_height_mini_interstitial = $("#custom_height_mini_interstitial").val();
            var creative_type = $("input[type='radio'][name='adv_type']:checked").val();
            var click_url_type = $("#click_url_type").val();
            var click_url = $("#click_url").val();
            var creative_file = $("#creative_file").val();
            var creative_file1 = $("#creative_file1").val();
            var tracking_pixel = $("#tracking_pixel").val();
            var tracking_url_iresearch = $("#tracking_url_iresearch").val();
            var tracking_url_admaster = $("#tracking_url_admaster").val();
            var tracking_url_admaster_jiami = $("#tracking_url_admaster_jiami").val();
            if (campaign_id == "") {
                alertBox("缺少投放id参数。");
                return false;
            }
            /*            var extStart = creative_file.lastIndexOf(".");
             var ext = creative_file.substring(extStart, creative_file.length).toUpperCase();*/
            if (creative_name == "") {
                alertBox("请输入素材名称。");
                return false;
            }
            if (creative_weight == null || creative_weight == "") {
                alertBox("请选择素材权重。");
                return false;
            }
            if (creative_date_range == "") {
                alertBox("请选择素材开始-结束日期。");
                return false;
            }
//条形横幅、全屏

            if (custom_width == "" || custom_height == "") {
                alertBox("请输入素材尺寸。");
                return false;
            }

            /*                if(ext != ".BMP" && ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG"){
             alertBox('请上传图片或视频文件！');
             return false;
             }*/
            if (creative_file == "" && !creative_file1) {
                alertBox("请上传素材文件。");
                return false;
            }

            $("#btn_save").attr("type", "submit");
        }
    </script>
</div>
<script type="text/javascript" src="/js/global.js?version=1.06"></script>
<link rel="stylesheet" type="text/css" href="/libs/daterangepicker/daterangepicker-bs3.css?version=1.06">
<link rel="stylesheet" type="text/css" href="/libs/datatables/dataTables.bootstrap.css?version=1.06">
<script type="text/javascript" src="/libs/moment/moment.min.js?version=1.06"></script>
<script type="text/javascript" src="/libs/daterangepicker/daterangepicker.js?version=1.06"></script>
<script type="text/javascript" src="/libs/datatables/jquery.dataTables.js?version=1.06"></script>
<script type="text/javascript" src="/libs/datatables/dataTables.bootstrap.js?version=1.06"></script>
<script type="text/javascript" src="/libs/datatables/dataTables.formattedNum.js?version=1.06"></script>
<script type="text/javascript" src="/libs/bootstrap/js/bootstrap.min.js?version=1.06"></script>
