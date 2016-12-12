<link rel="stylesheet" type="text/css" href="/css/units.css">
<div id="content">

    <div class="btn-position">
        <a class="go-back" href="/campaign/list"><i class="fa fa-chevron-left"></i></a>
        <a class="btn btn-primary" href="units/create"><i class="fa fa-plus"></i> 创建</a>
        <a class="btn btn-warning" href='javascript:void(0);' id="pause" ><i class="fa fa-pause"></i> 暂停</a>
        <a class="btn btn-success" href='javascript:void(0);' id="on" ><i class="fa fa-forward"></i> 运行</a>
        <a class="btn btn-danger delete-button" href='javascript:void(0);' id="del"><i
                class="fa fa-trash-o"></i>
            删除</a>
    </div>

    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline" role="grid">
        <div class="table-search clearfix">
            <div class="dataTables_filter" id="DataTables_Table_0_filter"><label>搜索：
                    <input type="text" name="keyword" onchange="keyword(this.value)" aria-controls="DataTables_Table_0"></label>
            </div>
        </div>
        <div class="table-w">
            <table class="table table-striped dataTable" id="DataTables_Table_0">
                <thead>
                <tr role="row">
                    <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label=""
                        style="width: 22px;"><input style="margin-left:3px;" value="1" type="checkbox" id="check_all"
                                                    onclick="check_all(this.checked)"></th>

                    <script>
                        function check_all(val) {
                            if (val) {
                                $("input[name='select_campaign']").attr("checked", true);
                            } else {
                                $("input[name='select_campaign']").attr("checked", false);
                            }
                        }
                    </script>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 110px;">素材名称
                    </th>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 110px;">投放名称
                    </th>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 110px;">素材类型
                    </th>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 160px;">时间段
                    </th>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 119px;">素材尺寸
                    </th>
                    <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1"
                        colspan="1" style="width: 119px;">素材状态
                    </th>
                    <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="操作"
                        style="width: 513px;">操作
                    </th>
                </tr>
                </thead>

                <tbody role="alert" aria-live="polite" aria-relevant="all">
                <?php if(isset($unitsData)) :?>
                <?php foreach ($unitsData as $unitsDataKey => $unitsDataVal) : ?>

                    <tr class="odd">
                        <td style="width:41px;" class=" sorting_1"><input style="margin-left:3px;" class="check_me"
                                                                          type="checkbox"
                                                                          value="<?= $unitsDataVal['adv_id'] ?>"
                                                                          name="select_campaign"></td>
                        <td> <?= $unitsDataVal['adv_name']; ?></td>
                        <td><?= @$campaign[$unitsDataVal['campaign_id']] ?></td>
                        <td><?= $unitsDataVal['creative_unit_type'] ?></td>
                        <td><?= $unitsDataVal['adv_start'] . '-' . $unitsDataVal['adv_end'] ?></td>
                        <td><?= $unitsDataVal['adv_width'] . 'x' . $unitsDataVal['adv_height'] ?></td>
                        <td><?php $statue =array('--','on','pause');echo $statue[$unitsDataVal['adv_status']] ?></td>
                        <td>
                            <a href="/advertise/units/edit?adv_id=<?= $unitsDataVal['adv_id']; ?>"
                               class="btn btn-primary btn-sm"><i
                                    class="fa fa-pencil"></i> 编辑</a>
                            <a class="btn btn-primary btn-sm preview" data-toggle="modal" id="preview"
                               href="#" onclick="preview('<?= $unitsDataVal['adv_creative_url']; ?>')">素材预览</a>
                            <a
                                class="btn btn-primary btn-sm"
                                href="device?creative_id=2513">投放预览</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else:?>
                    <tr><td colspan="7" style="text-align: center;">没有数据！</td></tr>
                <?php endif;?>
                </tbody>
            </table>
            <div>
                <div class="pagination"><?= $scaffold_pagination ?></div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content iframe-popup">
                </div>
                <a style="font-size: 30px;cursor: pointer;position: absolute;right: -35px;top: -35px;"
                   data-dismiss="modal">
                <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-1x"></i>
                    <i class="fa fa-times-circle-o fa-stack-1x fa-inverse"></i>
                </span>
                </a>
            </div>
        </div>


    </div>
    <script  language="javascript" type="application/javascript">

        $("#pause").click(function(){
            if(confirm("是否暂停所选项？")){
                active('pause');
            }
        });
        $("#on").click(function(){
            if(confirm("是否运行所选项？")){
                active('on');
            }
        });
        $("#del").click(function(){
            if(confirm("是否删除所选项？")){
                active('del');
            }
        });

        function preview(url) {
            window.open(url, '秀视', 'height=500, width=800, top=60, left=60, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no')
        }

       function keyword(val){

           location.href = "?keyword="+val;//location.href实现客户端页面的跳转
       }

        function active(status) {
            var chk_value = [];

            $('input[name="select_campaign"]:checked').each(function () {
                chk_value.push($(this).val());
            });
            if (chk_value == '') {
                alert('请选中后再进行操作！');
                return false;
            }
            do_ajax('units/active', {status: status, adv_ids: chk_value},function (data) {

                if(!data.code){
             /*       if(data.msg != 'del'){
                        alert(data.status+data.msg);
                    }
                    else {*/
                        location.href = "/advertise/units";//location.href实现客户端页面的跳转

                        alert(data.status+data.msg);

                    //}

                }

            });

        }
    </script>