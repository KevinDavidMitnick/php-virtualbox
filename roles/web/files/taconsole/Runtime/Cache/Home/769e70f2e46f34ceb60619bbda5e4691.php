<?php if (!defined('THINK_PATH')) exit();?>
<html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
<title><?php echo C('WEB_SITE_TITLE');?></title>
<link href="/taconsole/Public/static/bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="/taconsole/Public/static/bootstrap/css/user.css" rel="stylesheet">
<script>
    var home_url = "<?php echo U('index/index');?>";
</script>

<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<style type="text/css">

	.dropdown {
		padding-top: 15px;
		text-align: right;
	}
	.dropdown a {
		display: inline !important;
		color: #999;
	}


.navbar .nav > li > a {
    padding: 10px 0px 10px 10px;

}
.navbar .brand {
	padding-right: 0;
}
a {text-decoration: none;}
.dropdown a:hover {
	text-decoration: none;
	color: #4f9de0;
}
</style>
<body>
<header>
    <!-- 导航条================================================== -->
    <div class="navbar navbar-inverse navbar-fixed-top" id="TaconsolePane">
        <div class="navbar-inner">
            <div class="container">
                <a class="brand" href="<?php echo U('index/index');?>">Trunkey</a>
                <!--<a class="brand" href="<?php echo U('index/index');?>"><img src="turnkeylogo.png" alt="logo" /></a>-->
                <div class=".dropdown.dropdown collapse pull-right">
                    <?php if(is_login()): ?><ul class="nav" style="margin-right:0">
                            <li class="dropdown">
                                <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left:0;padding-right:0"><?php echo get_username();?>：</a>、-->
                                <span style="color: #999999;"><?php echo get_username();?></span>
                                <a onclick="add()"  id="vm_add">新建模拟器</a>
                                <a href="<?php echo U('User/profile');?>">修改密码</a>
                                <span id="lc_admin" style="display:none;">
                                	<a class="dropdown_a" href="<?php echo U('Home/Index/manage');?>" >用户管理</a>
                                </span>
                                <a href="<?php echo U('User/logout');?>">退出</a>
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
                <li class="xuj_con_li_1">模拟器名称</li>
                <li class="xuj_con_li_2">状态</li>
                <!--<li class="xuj_con_li_3"><a href="66&">操作</a></li>-->
                <li class="xuj_con_li_3">操作</li>
            </ul>
        </li>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="xnj_con_tab" index="<?php echo $key;?>" value="<?php echo $key;?>">
                <ul>
                    <input type="hidden" value="<?php echo ($vo["vmname"]); ?>" id="vmname">
                    <li class="xuj_con_li_1"><?php echo ($vo["vmname"]); ?></li>
                    <?php if(($vo["vmid"]) == "running"): ?><li class="xuj_con_li_2 xnj_user_tb<?php echo $key;?> vm_state" uuid="<?php echo ($vo["vmname"]); ?>" state="<?php echo ($vo["vmid"]); ?>" index="<?php echo $key;?>">运行中</li><?php endif; ?>
                    <?php if(($vo["vmid"]) == "shutdown"): ?><li class="xuj_con_li_2 xnj_user_tb<?php echo $key;?> vm_state" uuid="<?php echo ($vo["vmname"]); ?>" state="<?php echo ($vo["vmid"]); ?>" index="<?php echo $key;?>">已关闭</li><?php endif; ?>
                    <li class="xuj_con_li_3 xnj_user_zt"  index="<?php echo $key;?>" value="<?php echo ($vo["vmname"]); ?>">
                        <a class="start<?php echo $key;?> start" href="javascript:start('<?php echo ($vo["vmname"]); ?>')">开机</a>
                        <a class="cose<?php echo $key;?> cose"   href="javascript:cose('<?php echo ($vo["vmname"]); ?>')">关机</a>
                        <a class="shanqu<?php echo $key;?> shanqu" href="javascript:destroy('<?php echo ($vo["vmname"]); ?>')">删除</a>
                        <a class="chongqi<?php echo $key;?> chongqi" href="javascript:reboot('<?php echo ($vo["vmname"]); ?>')">重启</a>
                        <a href="<?php echo U('index/index');?>&ip=<?php echo ($vo["ip"]); ?>&port=<?php echo ($vo["port"]); ?>&" class="lianjie<?php echo $key;?> lianjie"  ip="<?php echo ($vo["ip"]); ?>" port="<?php echo ($vo["port"]); ?>">连接</a>
                    </li>
                </ul>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>

</body>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
<script src="/taconsole/Public/static/bootstrap/js/html5shiv.js"></script>
<![endif]-->

<!--[if lt IE 9]>
<script type="text/javascript" src="/taconsole/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/taconsole/Public/static/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/taconsole/Public/static/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/taconsole/Public/static/bootstrap/js/bootstrap.min.js"></script>
<!--<![endif]-->
<script type="text/javascript" src="/taconsole/Public/static/json2.js"></script>
<script type="text/javascript" src="/taconsole/Public/static/user.js"></script>
<script>
    //设置显示或者隐藏后台管理
    var is_admin = "<?php echo ($is_admin); ?>";
    if(is_admin == 1){
       $("#lc_admin").show();
    }
    //设置虚拟机器状态.
    set_vmstate();
    
     $('.dropdown a').hover(function (){
    	$(this).css('color','#4f9ed0');
    },function () {
    	$(this).css('color','#999');
    })
</script>
</html>