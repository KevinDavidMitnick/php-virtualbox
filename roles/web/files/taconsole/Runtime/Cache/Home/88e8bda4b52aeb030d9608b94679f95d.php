<?php if (!defined('THINK_PATH')) exit();?>
<html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" /> 
<title><?php echo C('WEB_SITE_TITLE');?></title>
<link href="/taconsole/Public/static/bootstrap/css/bootstrap.css" rel="stylesheet">
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
<script type="text/javascript" src="/taconsole/Public/static/user.js"></script>
<!--<![endif]-->
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<style type="text/css">

	.dropdown {
		padding-top: 15px;
		text-align: right;
	}
	.dropdown a {
		display: inline !important;
	}


/*.xnj_user_zt a:hover,
.xnj_user_zt span:hover
 {
	cursor: not-allowed;
	cursor: -ms-not-allowed;
}*/
.navbar .nav > li > a {
    padding: 10px 0px 10px 10px;

}
.navbar .brand {
	padding-right: 0;
}

/*弹框*/
    .fade.modal {
        top:10%;
    }
    span.input-group-addon {
        width: 65px;
        text-align: right;
        /*margin: 20px 10px 0 20px;*/
        display: inline-block;
    }
    input.form-control {
        height: 15px;
        margin-top: 8px;
    }
    div#myModal {
        width:350px;
        /*height: 300px;*/
        margin-left: -175px;
        top: 50%;
        margin-top: -150px;
        border: 1px solid #4f9de0;
    }
    .modal-header {
        padding:0px 9px 0px 0px;
        font-size: 15px;
    }
    .modal-header h4 {
        margin-left: 10px;
        font-size: 17px;
    	/*font-weight: normal;*/
    }
    span#lc_admin a {
        color: #999;
        padding: 10px 0px 10px 10px;
    }
    input[type='text']{
        height: 15px;
        line-height: 15px;
    }
    .modal-body {padding-top: 0px;}
    .xnj_user {margin-top: 45px}
    .modal-footer {
    	background: white;
    	border: none;
    	padding: 0px 19px 20px;
	    text-align: center;
	    margin-top: -10px;
    }
    .modal-footer .btn + .btn {
	    margin-left: 25px;
	}

   .pagination{
        float:right;
	margin-top:0px;
	margin-right:25px;
   }
</style>
<body>
<header>
    <!-- 导航条
    ================================================== -->
    <div class="navbar navbar-inverse navbar-fixed-top"  id="TaconsolePane">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="<?php echo U('index/index');?>">Trunkey</a>
                <div class=".dropdown.dropdown collapse pull-right">
                    <?php if(is_login()): ?><ul class="nav" style="margin-right:0">
                            <li class="dropdown">
                                <!-- Button trigger modal -->
                                <a onclick="btn_open()" data-toggle="modal" data-target="#myModal">
                                   创建用户
                                </a>
                                <a href="<?php echo U('Home/Index/index');?>" >模拟器管理</a>
                            </li>
                        </ul><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="xnj_user">
    <ul class="xnj_user_con">
        <li>
            <ul>
                <li class="xuj_con_li_1">用户账户</li>
                <li class="xuj_con_li_2">真实姓名</li>
                <li class="xuj_con_li_3">操作</li>
            </ul>
        </li>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
            <input type="hidden" value="<?php echo ($vo["id"]); ?>" id="uid">
            <li class="xuj_con_li_1"><?php echo ($vo["username"]); ?></li>
            <li class="xuj_con_li_2 xnj_user_tb<?php echo $key;?>" index="<?php echo $key;?>"><?php echo ($vo["truename"]); ?></li>
            <li class="xuj_con_li_3 xnj_user_zt"  index="<?php echo $key;?>">
            <span class="shanqu<?php echo $key;?> shanqu" onclick="show_unset('<?php echo ($vo["id"]); ?>','<?php echo ($vo["username"]); ?>')">删除</span>
            </li>
        </ul>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<div class="pagination">
　　<?php echo ($page); ?>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button  onclick="btn_close()" type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
                <h4 class="modal-title" id="myModalLabel">创建用戶</h4>
            </div>
            <div class="modal-body">
                <div >
                    <span class="input-group-addon">用户帐号:</span>
                    <input type="text" class="form-control" name="username" id="username"></br>
                    <span class="input-group-addon">真实姓名:</span>
                    <input type="text" class="form-control" name="truename" id="truename"></br>
                    <span class="input-group-addon">&nbsp;&nbsp;密码:</span>
                    <input type="password" class="form-control" name="password" id="password"></br>
                    <span class="input-group-addon">确认密码:</span>
                    <input type="password" class="form-control" name="repassword" id="repassword">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="create_user()">确定</button>
                <button type="button" class="btn btn-default" onclick="btn_close()" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

</body>
<script>
    function create_user(){
        var username = $("#username").val();
        var truename = $("#truename").val();
        var password = $("#password").val();
        var repassword=$("#repassword").val();
        if(!username){
            alert("用户账号未填写!");
            return;
        }
        if(!password){
            alert("密码未填写!");
            return;
        }
        if(password != repassword){
            alert("两次密码输入不一致!");
            return;
        }

        var data = {"username":username,"truename":truename,"password":password};
        $.post("index.php?s=/home/User/user_add",data, function(result) {
            if(result.status){
                alert("用户创建成功.");
                location.reload();
            }else{
                alert("用户创建失败.");
            }
        },"json");
    }

    function show_unset(uid,uname){
        var ret = confirm("确认删除用户"+uname+"?");
        if(ret){
            $.post("index.php?s=/home/User/user_del",{"uid":uid}, function(result) {
            if (result.status) {
                alert("用户"+uname+"删除成功!");
                location.reload();
            }else{
                alert("用户"+uname+"删除失败,"+result.message);
            }
            },"json");
        }
    }
//关闭,取消 
    function  btn_close() {
			$('.modal').hide();
		}
    $('.modal').hide();
    function btn_open() {
    	$('.modal').show();
    }

</script>
</html>