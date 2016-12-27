<?php if (!defined('THINK_PATH')) exit();?><html>
<meta charset="UTF-8">
<title><?php echo C('WEB_SITE_TITLE');?></title>
<link href="/taconsole/Public/static/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/taconsole/Public/static/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<link href="/taconsole/Public/static/bootstrap/css/docs.css" rel="stylesheet">
<link href="/taconsole/Public/static/bootstrap/css/onethink.css" rel="stylesheet">
<link href="/taconsole/Public/static/bootstrap/css/user.css" rel="stylesheet">

<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/taconsole/Public/static/bootstrap/js/html5shiv.js"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="/taconsole/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/taconsole/Public/static/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/taconsole/Public/static/bootstrap/js/bootstrap.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="/taconsole/Public/static/user.js"></script>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<style type="text/css">
/*content*/

 .span12 {
    width: 400px;
    height: 260px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -140px;
    /*margin-top: 100px;*/
    margin-left: -200px;
    background: #FAFAFA;
    box-shadow: 0px 0px 20px #666;
    border: 1px solid #4F9DE0;
}
.control-group {
	width: 310px;
	overflow: hidden;
	text-align: center;
    margin-top: 30px;	
}
.control-group.control-group_2, .control-group.control-group_3, .control-group.control-group_4 {margin-top: 20px;}
.controls {
    display: inline-block;
}
label {
	/*display: inline-block;*/
	float: left;
    width: 90px;
    text-align: right;
    /*margin-top: 20px;*/
    /*padding: 30px 0 0 30px;*/
}
label.labela2 {
	 /*margin-top: 10px;*/
}
input#inputEmail, input#inputPassword {
    width: 170px;
    height: 25px;
    outline: none;
    box-shadow: 0 0 0 1000px white inset;
    margin-left: 20px;
    padding: 2px;
    /*padding-left: 3px;*/
}
.controls {width: 215px;display: inline-block;float: right;}
</style>
<header>
  <!-- 导航条
  ================================================== -->
  <!--<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="<?php echo U('index/index');?>">Trunkey</a>
        <div class="nav-collapse collapse pull-right">
          <?php if(is_login()): ?><ul class="nav" style="margin-right:0">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left:0;padding-right:0"><?php echo get_username();?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo U('User/profile');?>">修改密码</a></li>
                  <li><a href="<?php echo U('User/logout');?>">退出</a></li>
                </ul>
              </li>
            </ul><?php endif; ?>
        </div>
      </div>
    </div>
  </div>-->
</header>
<body>
<div class="span12"  id="TaconsolePane">
  <form class="login-form" action="/taconsole/index.php?s=/Home/User/profile.html" method="post">
    <div class="control-group">
      <label class="control-label" for="inputPassword">原密码</label>
      <div class="controls">
        <input type="password" id="inputPassword"  class="span3" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="old">
      </div>
    </div>
    <div class="control-group control-group_2">
      <label class="control-label" for="inputPassword">新密码</label>
      <div class="controls">
        <input type="password" id="inputPassword"  class="span3" placeholder="请输入密码"  errormsg="密码为6-20位" nullmsg="请填写密码" datatype="*6-20" name="password">
      </div>
    </div>
    <div class="control-group control-group_3">
      <label class="control-label" for="inputPassword">确认密码</label>
      <div class="controls">
        <input type="password" id="inputPassword" class="span3" placeholder="请再次输入密码" recheck="password" errormsg="您两次输入的密码不一致" nullmsg="请填确认密码" datatype="*" name="repassword">
      </div>
      <div class="controls Validform_checktip text-warning"></div>
    </div>
    <div class="control-group control-group_4">
      <div class="controls">
        <button type="submit" class="btn">提 交</button>
        <a class="btn" href="javascript:history.go(-1);">取 消</a>
      </div>
    </div>
  </form>
</div>
</body>
<script type="text/javascript">

  $(document)
          .ajaxStart(function(){
            $("button:submit").addClass("log-in").attr("disabled", true);
          })
          .ajaxStop(function(){
            $("button:submit").removeClass("log-in").attr("disabled", false);
          });

  $("form").submit(function(){
    var self = $(this);
    $.post(self.attr("action"), self.serialize(), success, "json");
    return false;

    function success(data){
      if(data.status){
        window.location.href = data.url;
      } else {
          if(data.info == '-4')
              self.find(".Validform_checktip").text(data.info+" 密码长度过短");
          else
              self.find(".Validform_checktip").text(data.info);
      }
    }
  });

</script>
</html>