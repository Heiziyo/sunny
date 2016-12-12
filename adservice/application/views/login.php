<!doctype html>
<html>
<head>
    <title>上海国韵实业有限公司--管理后台</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link type="text/css" rel="stylesheet" href="/css/main.css" />
    <link type="text/css" rel="stylesheet" href="/css/login.css" />
    <link type="text/css" rel="stylesheet" href="/css/style.css" />
</head>
<body>
    <form class="form-signin ajax" id="form1" method="post" action="">
        <div id="loginbox">
            <div class="login_logo">
                <img src="img/login_logo.jpg" width="175" height="30" alt=""/>
            </div>

            <div class="login_textarea">
                <div class="login_lefticon">
                    <img src="img/login_name.jpg" width="50" height="40" alt=""/>
                </div>
                <label for="textfield"></label>
                <input  type="text" class="login_rightta"  placeholder="用户名" name="username" >
            </div>

            <div class="login_textarea">
                <div class="login_lefticon">
                    <img src="img/login_password.jpg" width="50" height="40" alt=""/>
                </div>
                <label for="textfield"></label>
                <input type="password" placeholder="密码" name="password" class="login_rightta"   >
            </div>

            <div class="login_toolarea">
                <div class="login_forget"><a href="#">FORGET PASSWORD?</a></div>
                <button type="submit" class="btn login_sumbit">登录</button>
            </div>
            <?=form_hash('login')?>
        </div>
    </form>
    <div id="popup-msg" class="alert" style="display:none"></div>
    <script type="text/javascript" src="/public/js/jquery-1.8.2.min.js"></script>
    <script src="/js/common.js?v=03"></script>
    <div style="clear: both;"></div>
    <div class="copy_right">
      <a>上海国韵实业有限公司--管理后台</a>
    </div>
</body>
</html>