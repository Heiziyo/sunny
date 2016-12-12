<?php
$item = d(@$args[1], NULL);
$privilege = d(@$item['privilege'], array());
$allNodes = F::$f->Model_Privilege->getAllPriNodes(TRUE, $privilege);

if($privilege){
    $privilege = json_decode($privilege, TRUE);
}
?>

<script type="text/javascript">
    var allPrivilege = JSON.parse('<?=json_encode($allNodes)?>');
</script>

<div class="control-group">
    <label class="control-label col-sm-2">
        <i>*</i>权限
        <!--<a onclick="getCheckNodes('priTree');">获取节点</a>-->
    </label>
    <div class="controls col-sm-10 form-inline">
        <ul id="priTree" class="ztree"></ul>
    </div>
</div>
<script type="text/javascript">
    var priSetting = {
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        }
    };

    zTreeObj = $.fn.zTree.init($("#priTree"), priSetting, allPrivilege);

    function getCheckNodes(treeId){
        var treeObj = $.fn.zTree.getZTreeObj(treeId),
            nodes = treeObj.getCheckedNodes(true),
            ch = [];

        for(var i = 0; i < nodes.length; i++){
            var theNode = nodes[i],
                params = {
                    'id': theNode['id'],
                    'name': theNode['name'],
                    'level': theNode['level'],
                    'pid': theNode['pid'],
                    'aid': theNode['aid'],
                    'isDefault': 0
                };
            if(theNode['level'] == 2){
                params['isDefault'] = theNode['isDefault']
            }

            ch.push(params);
        }

        return ch;
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
            var privilege = getCheckNodes('priTree');
            if(privilege){
                privilege = JSON.stringify(privilege);
            }else{
                privilege = '';
            }
            post_params += '&privilege=' + privilege;

            $.post($f.attr('action') || location.href, post_params, function(ret){

                $f.trigger('on_response');

                $f.find(':submit').prop('disabled', false);

                if (ret.code != 0) {
                    popup_msg(ret ? ret.msg : '发生异常错误', 'error');
                } else {
                    hide_popup_msg();
                    window.location = '/system/role';
                }

            }, 'json').error(function(){

                $f.find(':submit').prop('disabled', false);

                $f.trigger('on_response');

                popup_msg('服务器响应错误', 'error');
            });
        });
    });
</script>

