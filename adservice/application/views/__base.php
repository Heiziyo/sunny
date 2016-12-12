<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>上海国韵实业有限公司--管理后台</title>

	<link type="text/css" rel="stylesheet" href="/public/bootstrap/css/bootstrap.min.css">
    <!-- <link href ="/js/extjs5.1/packages/ext-theme-classic/build/resources/ext-theme-classic-all.css" rel="stylesheet" type="text/css"> -->
    <link type="text/css" rel="stylesheet" href="/public/bootstrap/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="/public/zTree_v3/css/zTreeStyle/zTreeStyle.css">
    <link rel="stylesheet" href="/public/js/chosen/chosen.css">
    <link rel="stylesheet" href="/public/css/multiple-select.css">
    <link rel="stylesheet" href="/public/metronic/css/DT_bootstrap.css" />
    <link rel="stylesheet" href="/public/jquery-ui-1.11.3/jquery-ui.min.css" />
    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <link href="/css/style.css" rel="stylesheet" type="text/css">
    <script src="/js/function.js"></script>
    <!--  <script src="/js/extjs5.1/ext-all.js?"></script>
    <script src="/js/ext-lang-zh_CN.js"></script>
    <script src="/js/extjs-event.js"></script>-->
    <?php if (!empty($__css['file'])) :?>
        <?php foreach ($__css['file'] as $file) :?>
            <?php
            if (! preg_match('@^(?:https?://|/)@i', $file)) {
                $file = "/css/$file";
            }
            ?>
            <link type="text/css" rel="stylesheet" href="<?=$file?>"/>
        <?php endforeach;?>
    <?php endif;?>

    <link type="text/css" rel="stylesheet" href="/css/main.css?v=20140226" />

    <?php if (!empty($__css['inner'])) :?>
        <style>
            <?php foreach ($__css['inner'] as $code) : ?>
            <?=$code?>
            <?php endforeach;?>
        </style>
    <?php endif;?>

    <script type="text/javascript" src="/public/js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="/public/js/jquery-ui-1.9.1.custom.min.js"></script>
    <script type="text/javascript" src="/public/zTree_v3/js/jquery.ztree.all.min.js"></script>

    <?php if (!empty($__js['file'])) :?>
        <?php foreach ($__js['file'] as $file) :?>
            <?php
            if ( ! preg_match('@^(?:https?://|/)@i', $file)) {
                $file = "/js/$file";
            }
            ?>
            <script type="text/javascript" src="<?=$file?>"></script>
        <?php endforeach;?>
    <?php endif;?>
    <?php if (!empty($__js['inner'])) : ?>
        <script type="text/javascript">
            <?php
            if(is_array($__js['inner']))
                foreach ($__js['inner'] as $code) :?>
            <?=$code?>
            <?php endforeach;
        else
            echo $__js['inner'];
        ?>
        </script>
    <?php endif;?>

    <?php


    $_currentMenu = NULL;
    $_currentMenuText = NULL;
    ?>
</head>
<body>
<div  id="top_box">
<!--    <div class="top_logo">
        <p>广告投放业务管理平台</p>
    </div>-->
    <div class="top_logo">
        <a  href="/"><img src="/images/top_logo.jpg" width="205" height="80" alt=""/></a>
    </div>
    <div  class="top_navbox">
        <?php if (@$me['id']) :?>
            <div class="top_nav">
                <ul class="overwriteul">
                    <?php foreach ($_menus as $_menuText => $_menu) :?>
                        <?php if ($_menu['permission']) :?>
                            <?php
                            if ($c_menu == $_menu['name']) {
                                $_currentMenu = $_menu;
                                $_currentMenuText = $_menuText;
                            }
                            ?>
                            <a href="<?=$_menu['url']?>"><li class="<?= $_menu['css']; ?>"><?=  $_menuText;?></li></a>
                        <?php endif;?>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="top_nav_tool">
                <div class="top_nav_hello">你好，<span class="fwhite"><a href="/me" ><?=$me['realname']?></a></span></div>
                <a href="/logout"><div class="top_nav_exit"></div></a>
            </div>
        <?php else :?>
            <div class="top_nav_tool">
                <div class="top_nav_hello"><a style="font-size:18px;color:#000">您好! 访客</a></div>
            </div>
        <?php endif;?>
        <div class="top_mbx"><a href="#">首页</a> > <a href="<?=$_currentMenu['url']?>"> <?=$_currentMenuText?> </a> >  <?= $title?></div>
     </div>
</div>


<div >
    <?php if ($_currentMenu) :?>
            <div id="cont_box">
                <div class="cont_left">
                    <ul class="overwriteul">
                        <!-- <li class="nav-header"><?=$_currentMenuText?></li> -->
                        <?php foreach($_currentMenu['sub'] as $_subMenuText => $_subMenu) :?>
                            <?php
                            if (empty($_subMenu['permission'])) continue;

                            $_item = trim(str_replace('/', '.', $_subMenu['url']), '.');
                            //$_isActive = @explode('.', $_item)[1] === @explode('.', $c_submenu)[1];
                            $_itemAry = explode('.', $_item);
                            $c_submenuAry = explode('.', $c_submenu);
                            $_isActive = FALSE;
                            if(isset($_itemAry[1]) && isset($c_submenuAry[1]) && $_itemAry[1] == $c_submenuAry[1]){
                                $_isActive = TRUE;
                            }
                            //$_isActive = preg_match("@^$_item@", "{$c_submenu}");
                            ?>
                            <?php if ($_subMenu['url'] == '') :?>
                                <li class="cont_left_01"><?=$_subMenuText?>&nbsp;<?php if(isset($_subMenu['unprocess']) && !empty($_subMenu['unprocess'])):?><?=$_subMenu['unprocess']?><?php endif;?></li>
                            <?php else :?>
                                <li <?=$_isActive&&empty($_subMenu['sub']) ? 'class="cont_left_01-active"' : 'class="cont_left_01"'?>
                                    <?php if(isset($_subMenu['sub'])) :?> style="height: auto";  <?php endif; ?>
                                >

                                    <a class="fwhite" href="<?=$_subMenu['url']?>"><?=$_subMenuText?>&nbsp;<?php if(isset($_subMenu['unprocess']) && !empty($_subMenu['unprocess'])):?> <?=$_subMenu['unprocess']?> <?php endif;?></a></li>
                            <?php endif;?>
                            <!--三级菜单-->
                            <?php if(!empty($_subMenu['sub'])) :  ?>
                                <ul id="<?=$_subMenu['name']?>">
                                    <?php foreach($_subMenu['sub'] as $_sub2MenuText => $_sub2Menu) : ?>
                                        <?php if ($_sub2Menu['permission']) :?>
                                            <li <?php if(strpos($_sub2Menu['url'],$_SERVER['REQUEST_URI']) !== FALSE) :?> class="cont_left_01-active" <?PHP else: ?>  class="cont_left_01" <?php endif;?> style="width:180px;">
                                                <a class="fwhite" href="<?=$_sub2Menu['url']?>"> <?=$_sub2MenuText?>&nbsp;<?php if(isset($_sub2Menu['unprocess']) && !empty($_sub2Menu['unprocess'])):?> <?=$_sub2Menu['unprocess']?> <?php endif;?></a></li>

                                            </li>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </ul>
                            <?php endif; ?>


                        <?php endforeach;?>
                    </ul>
                </div>

                <div class="contentdiv">
                    <?=$__content?>
                </div>
        </div>
    <?php else :?>
        <?=$__content?>
    <?php endif;?>
</div>


<!--对话框-->
<div id="common_dialog" class="modal fade hide">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">提示</h4>
            </div>
            <div class="modal-body">
                <p id="common_dialog_info"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default common_dialog_cancel" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary common_dialog_confirm">确认</button>
            </div>
        </div>
    </div>
</div>

<div id="popup-msg" class="alert" style="display:none">
</div>

<script src="/public/bootstrap/js/bootstrap.min.js"></script>
<link href="/public/js/timepicker/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css"/>

<!--<script src="/public/js/timepicker/jquery-ui-timepicker-addon.js"></script>
--><script src="/public/js/timepicker/jquery-ui-sliderAccess.js"></script>
<!--<script src="/public/js/timepicker/localization/jquery-ui-timepicker-zh-CN.js"></script>
--><script src="/public/js/chosen/chosen.jquery.min.js"></script>
<script src="/js/common.js?v=1010"></script>
<script type="text/javascript" src="/public/js/multiple-select.js"></script>

<script language="JavaScript" type="application/javascript">

    function sunMenu(id)
    {
        $("#"+id).toggle();;

    }
</script>
<?php if (@$__msg) :?>
    <script>
        $(function(){
            popup_msg(<?=escape_js_quotes($__msg['msg'], TRUE)?>, <?=escape_js_quotes($__msg['type'], TRUE)?>);
        });
    </script>
<?php endif;?>

<div class="copy_right">
     <a>上海国韵实业有限公司--管理后台</a>
</div>

</body>
</html>
