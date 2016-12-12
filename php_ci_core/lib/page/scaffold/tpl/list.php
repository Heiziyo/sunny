<?php $scaffold_helper->beforeListRender(); ?>
<?php
$canCreate = ! isset($scaffold_config['can_create']) || $scaffold_config['can_create'];
$hideOpColumn = ! empty($scaffold_config['list']['hide_op_column']);
?>
<form class="form-search" method="get" action="">
    <?php $scaffold_helper->beforeSearchFormRender($scaffold_config)?>
    <?php if (!empty($scaffold_config['list']['options'])) :?>
        <?php
            foreach($scaffold_config['list']['options'] as $field => $selectoption){
                echo("<input type=hidden id='".$field."' name='".$field."'>");
                echo($scaffold_config['list']['optionname'][$field].":");
                echo("<select id='m".$field."' multiple='multiple' >");
                foreach($selectoption as $id => $name){
                    echo("<option title='".$name."' value='".$id."'>".$name."</option>");
                }
                echo("</select>");
                echo('<script type="text/javascript">
                    $(function() {
                        $("#m'.$field.'").change(function() {
                            $("#'.$field.'").val($(this).val());
                        }).multipleSelect({
                            width: "9%"
                        });
                    });
                        $("#m'.$field.'").val("'.d(@$_GET[$field], '').'".split(","));
                            </script>
                    ');


            }
        ?>
    <?php endif;?>
    <?php if (!empty($scaffold_config['list']['keyword'])) :?>
        <input type="text" name="kw" placeholder="输入关键字" value="<?=h(@$_GET['kw'])?>" class="txt">
        <input type="submit" id="searchBtn" class="btn" value="搜索">
    <?php endif;?>
    <?php $scaffold_helper->beforeSearchBtnRender($scaffold_config)?>
    <?php if ($canCreate && !$scaffold_helper->createButtonRender()) :?>
    <a class="ml20 btn-primary btn" href="<?=d(@$scaffold_config['create_url'],
    "/{$scaffold_config['controller_directory']}{$scaffold_config['controller']}/create?redirect_uri=".urlencode(get_self_full_url()))?>"><i class="icon-plus"></i> 新建<?=$scaffold_config['name']?></a>
    <?php endif;?>

   <?php $scaffold_helper->afterSearchFormRender($scaffold_config)?>
</form>
    
<?php
    $columns = $scaffold_config['list']['columns'];
    $columnSel = d(@$scaffold_config['list']['columnSel'], array());
    $hideColumns = array();
?>

<div content-id="list">
<?php
if(isset($sort_memcache_key)){
    $cacheSort = Cache_Memcache::sGet($sort_memcache_key);
    if($cacheSort){
        $cacheSort = json_decode($cacheSort, TRUE);
    }else{
        $cacheSort = array();
    }
}
?>
<?php $scaffold_helper->beforeListTableRender(); ?>
<table id="listRow" class="<?=d(@$scaffold_config['list']['table_class'], 'table table-hover')?>">
    <thead>
        <?php $scaffold_helper->beforeListTableHeadRender();?>
        <tr class="head">
        <?php
        $columnIndex = 0;
        foreach ($columns as $column_name => $ignor) :
            ?>
        <?php
            //{列属性}列名称
            $tmpColumnName = $column_name;
            preg_match('@^\{(.+?)\}(.+)$@', $column_name, $ma);
            $column_attrs = '';
            if ($ma) {
                $column_attrs = $ma[1];
                //排序处理
                $column_attrs_ary = explode('|', $column_attrs);
                if(isset($column_attrs_ary[0]) && isset($column_attrs_ary[1])){
                    $columnSortName = $column_attrs_ary[1];
                    $columnSortStatus = 'sorting';
                    if($column_attrs_ary[0] == SORT_PREFIX){
                        if(isset($cacheSort[$columnSortName])){
                            if($cacheSort[$columnSortName] == 'DESC'){
                                $columnSortStatus = 'sorting_desc';
                            }else if($cacheSort[$columnSortName] = 'ASC'){
                                $columnSortStatus = 'sorting_asc';
                            }else{
                                $columnSortStatus = 'sorting';
                            }
                        }
                        $column_attrs = ' sort class="'.$columnSortStatus.'" data_sort = "'.$columnSortName.'"';
                    }
                }
                $column_name = $ma[2];
            }
            if ($column_name == '__checkbox__') {
                $column_attrs = 'width="20"';
            }

            $cssDisplay = '';
            if(isset($columnSel[$tmpColumnName]) && $columnSel[$tmpColumnName][1] == 'hide'){
                $cssDisplay = 'style="display:none;"';
                $hideColumns[] = $columnIndex;
            }

            if($column_name == '__checkbox__'){
                $column_attrs .= ' data="__checkbox__' . $ignor . '" ' . $cssDisplay;
            }else {
                $column_attrs .= ' data="' . $ignor . '" ' . $cssDisplay;
            }
            $columnIndex++;
        ?>
        <th <?=empty($column_attrs) ? '' : ' '.$column_attrs?>>
            <?php if ($column_name == '__checkbox__') : ?>
            <input type="checkbox" class="sel-all"/>
            <?php elseif (! $scaffold_helper->headColumnRender($column_name)) :?>
            <?=$column_name?>
            <?php endif;?>
        </th>
        <?php endforeach;?>

        <?php if (!$hideOpColumn) :?>
            <?php if($columnSel) : ?>
                <th id="showSelColumn">操作<img src="/images/plus.png" width="16" height="16">
                    <script>
                        $(function(){
                            $('#showSelColumn').mouseover(function(){
                                if($('#showSelContent').is(':hidden')){
                                    $('#showSelContent').slideDown(300);;
                                }
                            });

                            $('#showSelContent').mouseover(function(el){
                                $('#showSelContent').show();
                            }).mouseout(function(){
                                $('#showSelContent').hide();
                            });

                            $('#showSelContent input').click(function(){
                                var showIds = [],hideIds = [],userSle = [];
                                $('#showSelContent input').each(function(){
                                    if($(this)[0].checked){
                                        showIds.push($(this).attr('name'));
                                        userSle.push($(this).attr('data'));
                                    }else{
                                        hideIds.push($(this).attr('name'));
                                    }
                                });

                                if(userSle){
                                    if(typeof $('.form-search input[name=showFields]')[0] == 'undefined'){
                                        $('.form-search').append('<input type="hidden" name="showFields">');
                                    }
                                    $('.form-search input[name=showFields]').val(userSle);
                                }

                                var _index = 0, _showIndex = [], _hideIndex = [];
                                $('#listRow thead th').each(function(){
                                    var _name = $(this).attr('data');
                                    if(inArray(_name, showIds)){
                                        $(this).css("display", '');
                                        _showIndex.push(_index);
                                    }else if(inArray(_name, hideIds)){
                                        $(this).css("display", 'none');
                                        _hideIndex.push(_index);
                                    }
                                    _index++;
                                });

                                $("#listRow tbody tr").each(function () {
                                    for(var i = 0, j = _showIndex.length;i < j;i++){
                                        $(this).find("td").eq(_showIndex[i]).css("display", "");
                                    }
                                    for(var i = 0, j = _hideIndex.length;i < j;i++){
                                        $(this).find("td").eq(_hideIndex[i]).css("display", "none");
                                    }
                                });
                            });

                        });
                    </script>
                </th>
                <th style="position: absolute;width:0;">
                    <div id="showSelContent" class="cuscont_list hidden-box hidden-loc-info" id="box-3">
                        <ul>
                            <?php foreach($columnSel as $key => $val) : ?>
                                <li><input data="<?=$key?>" name="<?=$val[0]?>" <?=$val[1] == 'show' ? 'checked' : ''?> type="checkbox" value=""><?=$val[2]?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </th>
            <?php else : ?>
                <th>操作</th>
            <?php endif; ?>
        <?php endif;?>

        </tr>
        <?php $scaffold_helper->afterListTableHeadRender();?>
    </thead>
    <tbody>
    <?php if (empty($scaffold_items)) :?>
    <tr>
        <td colspan="<?=count($columns) + ($hideOpColumn ? 0 : 1)?>" style="text-align:center;font-size:14px;font-weight:bold;color:#999">
            <p>没有数据</p>
        </td>
    </tr>
    <?php else :?>
    <?php foreach ($scaffold_items as $i => $scaffold_item) :?>
    <tr>
        <?php foreach ($columns as $column_index => $method) :?>
        <td>
            <?php if ($column_index == '__checkbox__') :?>
            <input type="checkbox" class="sel-item" value="<?=$scaffold_item[$scaffold_config['primary_key']]?>"/>
            <?php elseif ($method == '__LINE__') :?>
            <?=$column_index + 1 ?>
            <?php elseif (strpos($method, 'cb_') !== FALSE) :?>
            <?=$scaffold_helper->$method($scaffold_item)?>
            <?php elseif (strpos($method, '{') !== FALSE) :?>
            <?=$scaffold_helper->processTpl($method, $scaffold_item)?>
            <?php else :?>
            <?=@$scaffold_item[$method]?>
            <?php endif;?>
        </td>
        <?php endforeach;?>
        <?php if (!$hideOpColumn) :?>
        <td>
            <?php if (!empty($scaffold_item[$scaffold_config['primary_key']])) :?>
            <?php $scaffold_helper->beforeOpColumnRender($scaffold_item); ?>
            <?php if ( ! $scaffold_helper->OpColumnRender($scaffold_item)) :?>
            <?=$scaffold_helper->editLink($scaffold_config, $scaffold_item)?>
            <?=$scaffold_helper->deleteLink($scaffold_config, $scaffold_item)?>
            <?php endif;?>
            <?php $scaffold_helper->afterOpColumnRender($scaffold_item); ?>
            <?php endif;?>
        </td>
        <?php endif;?>
    </tr>
    <?php endforeach;?>
    <?php
        if(!empty($scaffold_item_total)){
            //print_r($scaffold_item_total);
            echo("<tr class='head'>");
            $i=0;
            foreach ($columns as $column_name => $ignor){
                if($i==0) echo("<th>总计</th>");
                else {
                    if(isset($scaffold_item_total[$ignor])){
                        echo("<th>".$scaffold_item_total[$ignor]."</th>");
                    }
                    else echo("<th>-</th>");
                }
                $i++;
            }
            if (!$hideOpColumn) echo("<th></th>");
            echo("</tr>");
        }
    ?>
    </tbody>
    <?php endif;?>
    <tfoot>
        <?php $scaffold_helper->beforeListTableFootRender();?>
        <?php if (isset($columns['__checkbox__'])) :?>
        <!--<tr class="dark">
            <td colspan="<?/*=count($columns) + 1*/?>">
                <input type="checkbox" class="sel-all"/>
                <?php /*if ( ! $scaffold_helper->batchActionRender()) :*/?>
                <input type="button" class="batch-del-btn" value="删除"/>
                <?php /*endif;*/?>
            </td>
        </tr>-->
        <?php endif;?>
        <?php $scaffold_helper->afterListTableFootRender();?>
    </tfoot>
</table>
<script type="text/javascript">
    var hideColumns = '<?=json_encode($hideColumns)?>';
    hideColumns = JSON.parse(hideColumns);

    $(function(){
        if(hideColumns) {
            $("#listRow tbody tr").each(function () {
                for(var i = 0, j = hideColumns.length;i < j;i++){
                    $(this).find("td").eq(hideColumns[i]).css("display", "none");
                }
            });
        }

        $('th[sort]').click(function(){
            var $el = $(this),
                sortField = $el.attr('data_sort'),
                sortType = 'DESC';
            if(!sortField){
                return ;
            }

            if($el.hasClass('sorting')){
                sortType = 'DESC';
                $el.attr('class', 'sorting_desc');
            }else if($el.hasClass('sorting_asc')){
                sortType = 'DESC';
                $el.attr('class', 'sorting_desc');
            }else if($el.hasClass('sorting_desc')){
                sortType = 'ASC';
                $el.attr('class', 'sorting_asc');
            }
            var $sortField = $('.form-search').find('input[name=sort_'+sortField+']');
			console.log($sortField);
            if($sortField[0]){
                $sortField.val(sortType);
            }else {
                $('.form-search').append('<input type="hidden" name="sort_'+sortField+'" value="'+sortType+'"/>');
            }
            $('#searchBtn').trigger('click');
        });
    });
</script>
<?php $scaffold_helper->afterListTableRender(); ?>

<div class="pagination"><?=$scaffold_pagination?></div>

<!--END LIST-->
</div>

<form id="delete-form" action="/<?=$scaffold_config['controller_directory'].$scaffold_config['controller']?>/delete" method="post" style="display:none">
    <input type="hidden" name="<?=$scaffold_config['primary_key']?>" value=""/>
</form>

<div id="modal-edit" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3></h3>
  </div>
  <div class="modal-body">
  </div>
  <div class="modal-footer">
    <a href="javascript:;" class="btn" data-dismiss="modal" aria-hidden="true">取消</a>
    <a href="javascript:;" class="btn btn-primary">确认</a>
  </div>
</div>

<script>
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

$(function(){
    $(document).delegate('.del-btn', 'click', function(event){
        var id = $(this).attr('rel');
        if ( ! confirm('你确定要删除记录吗？')) {
            return;
        }
        $('#delete-form').find(':hidden').val(id);
        
        <?php if (@$scaffold_config['ajax']) :?>
        popup_msg('删除中...', 'info');
        $.post($('#delete-form').attr('action'), $('#delete-form').serialize(), function(ret){
            if (ret.code === 0) {
                popup_msg(ret.msg, 'succ');
                load_partial('list');
            } else {
                popup_msg(ret.msg, 'error');
            }
        }, 'json');
        <?php else :?>
        $('#delete-form').get(0).submit();
        <?php endif;?>
    });
    
    function get_selected_ids()
    {
        return $(':checkbox.sel-item:checked').map(function(){
            return this.value;
        }).get().join(',');
    }
    
    window.get_selected_ids = get_selected_ids;
    
    $(document).delegate('.batch-del-btn', 'click', function(event){
        var checkedUids = get_selected_ids();
        if ( ! checkedUids) {
            popup_msg('未选择记录', 'error');
            return;
        }
        if ( ! confirm('你确定要删除所选记录吗？')) {
            return;
        }
        $('#delete-form').find(':hidden').val(checkedUids).end()
        .get(0).submit();
    });
    
    $(document).delegate(':checkbox.sel-all,:checkbox.sel-item', 'click', function(){
        var $t = $(this), isChecked = $t.attr('checked');
        if ($t.is('.sel-all')) {
            if (isChecked) {
                $(':checkbox.sel-item,:checkbox.sel-all').attr('checked', true);
            } else {
                $(':checkbox.sel-item,:checkbox.sel-all').attr('checked', false);
            }
        } else {
            if (isChecked) {
                if ($(':checkbox.sel-item:not(:checked)').length == 0) {
                    $(':checkbox.sel-all').attr('checked', true);    
                }
            } else {
                $(':checkbox.sel-all').attr('checked', false);
            }
        }
    });
    
    $(document).delegate('.list-table tbody', 'click', function(event){
        var $target = $(event.target);
        if ($target.is('a,input,select')) {
            return;
        }
        var $checkbox = $target.closest('tr').find(':checkbox');
        if ($checkbox.length) {
            $checkbox.attr('checked', ! $checkbox.attr('checked'));
        }
    });

<?php if (@$scaffold_config['ajax']) :?>
    
    var $modal = $('#modal-edit');

    $modal.delegate('.btn-primary', 'click', function() {
        var $f = $modal.find('form');
        
        if (!$f.length) {

            return; 
        }

        $f.trigger('submit'); 

    }).delegate('form', 'ajax_succ', function(event, result) {

        delete result.redirect_uri; 

        $modal.modal('hide');
        
        if (result.code == 0) {
            load_partial('list');
        }
    });

    $(document).delegate('a[href*="/create"],a[href*="/edit"]', 'click', function(event){
        event.preventDefault();
        
        var href = $(this).attr('href');

        if (/create/.test(href)) {
            $modal.find('.modal-header h3').html('添加<?=$scaffold_config['name']?>');
        } else {
            $modal.find('.modal-header h3').html('编辑<?=$scaffold_config['name']?>');
        }

        $modal.modal({
            remote : 'about:blank',
            keyboard : true
        }).css({
            width: 850,
            'margin-left': '-375px'
        });

        popup_msg('数据加载中...', 'info');

        $modal.find('.modal-body').load(href, function(){
            hide_popup_msg();
            $(window).trigger('ajax_load_page');
        });
    });

<?php endif;?>
});
</script>

<?php $scaffold_helper->afterListRender(); ?>
