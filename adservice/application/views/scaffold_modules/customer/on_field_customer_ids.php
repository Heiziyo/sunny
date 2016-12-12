<?php
$item = d(@$args[1], NULL);
$ids = d(@$item['customer_ids'], NULL);
$rows = FALSE;
if($ids){
    $where = array(
        'status' => Model_User::STATUS_ACTIVE,
        'usertype' => Model_User::TYPE_AD,
        'id' => explode(',', $ids)
    );

    $attr = array(
        'select' => 'id, realname, customerposition, mobile',
        'order_by' => 'id ASC'
    );

    $rows = M('user', 'ad_business')->select($where, $attr);
}
?>
<script>
    var maxCusIndex = 0;
</script>
<div class="control-group">
    <label class="control-label col-sm-2">
        <i>*</i>客户联系人
    </label>
    <div class="controls col-sm-10 form-inline">
        <table class="table table-bordered" id="customers">
            <thead>
            <tr>
                <th>姓名</th>
                <th>职位（必填）</th>
                <th>电话</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if($rows) :
                $indexCus = 0;
                ?>
                <?php foreach($rows as $row) : ?>
                <tr>
                    <td>
                        <input type="hidden" name="id" value="<?=$row['id']?>">
                        <input name="realname<?=$indexCus?>" class="form-control" type="text" value="<?=$row['realname']?>"/>
                    </td>
                    <td>
                        <input name="customerposition<?=$indexCus?>" class="form-control" type="text" value="<?=$row['customerposition']?>"/>
                    </td>
                    <td>
                        <input name="mobile<?=$indexCus?>" class="form-control" type="text" value="<?=$row['mobile']?>"/>
                    </td>
                    <td>
                        <input type="button" class="btn btn-danger" value="删除" onclick="delRange(this, 'customers')"/>
                    </td>
                </tr>
            <?php
            $indexCus++;
            endforeach
            ?>
                <script>
                    maxCusIndex = '<?=$indexCus?>';
                </script>
            <?php else : ?>
                <tr>
                    <td>
                        <input type="hidden">
                        <input name="realname0" class="form-control" type="text" />
                    </td>
                    <td>
                        <input name="customerposition0" class="form-control" type="text" />
                    </td>
                    <td>
                        <input name="mobile0" class="form-control" type="text" />
                    </td>
                    <td>
                        <input type="button" class="btn btn-danger" value="删除" onclick="delRange(this, 'customers')"/>
                    </td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
        <input type="button" class="btn btn-success" value="增加" onclick="addCustomerRange();"/>
    </div>
    <script type="text/javascript">

        var rangeCusHtml = '<tr>'+
            '<td><input type="hidden"><input name="realname##Index" class="form-control" type="text" /></td>'+
            '<td><input name="customerposition##Index" class="form-control" type="text" /></td>'+
            '<td><input name="mobile##Index" class="form-control" type="text" /></td>'+
            '<td><a class="btn btn-danger" onclick="delRange(this, \'customers\')">删除</a></td>'+
            '</tr>';

        function addCustomerRange(){
            maxCusIndex = parseInt(maxCusIndex) + 1;
            if(maxCusIndex >= 5){
                popup_msg('最多只能有5个联系人');
                return;
            }
            var content = rangeCusHtml.replace(/##Index/g, maxCusIndex);
            $('#customers > tbody').append(content);
        }

        function delRange(el, id){
            var domLen = $('#'+id+' > tbody > tr').length;

            if(domLen <= 1){
                popup_msg('至少要有1个联系人');
                return;
            }
            $(el).closest('tr').remove();
        }

        function getRangeData(id){
            var ranges = [];
            if(!id) return ;
            $('#'+ id + ' > tbody > tr').each(function(){

                var data = $(this).find('input'),
                    id = atrim(data[0].value),
                    realname = atrim(data[1].value),
                    customerposition = atrim(data[2].value),
                    mobile = atrim(data[3].value);

                if(realname) {
                    var range = {
                        id: id,
                        realname: realname,
                        customerposition: customerposition,
                        mobile: mobile
                    };
                    ranges.push(range);
                }

            });

            return ranges;
        }

        $(function(){

            $('form').removeAttr('id');
            $('form').submit(function(event){
                event.preventDefault();
                popup_msg('数据保存中...', 'info');
                var $f = $(this);
                $f.trigger('before_submit');
                var $disabled = $f.find(':disabled[name]');
                $disabled.prop('disabled', false);
                var post_params = $f.serialize();

                var customers = getRangeData('customers');

                post_params += '&customers=' + JSON.stringify(customers);

                $disabled.prop('disabled', true);

                $f.find(':submit').prop('disabled', true);

                $.post($f.attr('action') || location.href, post_params, function(ret){

                    $f.trigger('on_response');

                    $f.find(':submit').prop('disabled', false);

                    if (ret.code != 0) {
                        popup_msg(ret ? ret.msg : '发生异常错误', 'error');
                    } else {
                        hide_popup_msg();
                        window.location = '/customer/customer/';
                    }

                }, 'json').error(function(){

                    $f.find(':submit').prop('disabled', false);

                    $f.trigger('on_response');

                    popup_msg('服务器响应错误', 'error');
                });
            });
        });
    </script>
</div>