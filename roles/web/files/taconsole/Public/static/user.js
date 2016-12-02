//显示创建虚拟机按钮
function vm_enable(){
	$("#vm_add").attr("onclick","add();");
	$("#vm_add").css('cursor','pointer');
}

//隐藏创建虚拟机按钮
function vm_disable(){
	$("#vm_add").removeAttr("onclick");
	$("#vm_add").css('cursor','not-allowed');
}


//添加虚拟机
function add(){
    vm_disable();
    $.post("index.php?s=/home/index/vm_create",{}, function(result){
        if(result.status){
            alert(result.info);
            location.href = "index.php";
        } else{
            alert(result.info);
            vm_enable();
        }
    }, 'json');
}

//开机
function start(vmname){
    var data ={"vmname":vmname};
    $.post("index.php?s=/home/index/vm_start",data, function(result){
        if(result.status){
            alert(result.info);
            location.href = "index.php";
        } else{
            alert(result.info);
        }
    }, 'json');
}

//关机	
function cose(vmname){
    var data ={"vmname":vmname};
    $.post("index.php?s=/home/index/vm_stop",data, function(result){
        if(result.status){
            alert(result.info);
            location.href = "index.php";
        } else{
            alert(result.info);
        }
    }, 'json');
}

//删除
function destroy(vmname)
{
    var data ={"vmname":vmname};
    $.post("index.php?s=/home/index/vm_delete",data, function(result){
        if(result.status){
            alert(result.info);
            location.href = "index.php";
        } else{
            alert(result.info);
        }
    }, 'json');
}
		var reboot_id=null;
		var reboot_key=null;
		function  unset(vm_id ,key) {
			$('.xnj_zq').show();
			reboot_id=vm_id;
			reboot_key=key;
			
		}

//重启
function reboot(vmname)
{
    var data ={"vmname":vmname};
    $.post("index.php?s=/home/index/vm_reboot",data, function(result){
        if(result.status){
            alert(result.info);
            //location.href = "index.php";
        } else{
            alert(result.info);
        }
    }, 'json');
}

		function  guanbi() {
			$('.xnj_sc').hide();
		}
		function  quxiao() {
			$('.xnj_zq').hide();
		}

//获取状态
function set_vmstate(){
    $(".vm_state").each(function(){
        var uuid = $(this).attr("uuid");
        var index = $(this).attr("index");
        var state = $(this).attr("state");
        change_status(uuid,state,index);
    });
}


function change_status(uuid,status,index){
	if (status == 'running') {
		$('.cose'+index).css('color','#4f9de0');
		$('.chongqi'+index).css('color','#4f9de0');
		$('.lianjie'+index).css('color','#4f9de0');
		//不能开机
		$('.start'+index).hover(function () {
			$(this).removeAttr("href");
			$(this).css('cursor','not-allowed');
		})
		
		//不能删除
		$('.shanqu'+index).hover(function () {
			$(this).removeAttr("href");
			$(this).css('cursor','not-allowed');
		})
		
		//可以关机
		$('.cose'+index).hover(function () {
			$(this).attr("href","javascript:cose('" +uuid +"')");
			$(this).css('cursor','pointer');
		})
		
		//可以重启
		$('.chongqi'+index).hover(function () {
			$(this).attr("href","javascript:reboot('" +uuid +"')");
			$(this).css('cursor','pointer');
		})
		//可以连接
		$('.lianjie'+index).hover(function () {
			var ip = $(this).attr("ip");
			var port = $(this).attr("port");
			var addr = home_url+"&ip="+ip+"&port="+port+"&";
			$(this).attr("href",addr);
			$(this).css('cursor','pointer');
		})

		$('.xnj_user_tb' + index).html("运行中");
	}
	
	if (status == 'shutdown') {
		$('.start'+index).css('color','#4f9de0');
		$('.shanqu'+index).css('color','#4f9de0');
		//不能关机
		$('.cose'+index).hover(function () {
			$(this).removeAttr("href");
			$(this).css('cursor','not-allowed');
		})
		
		//不能重启
		$('.chongqi'+index).hover(function () {
			$(this).removeAttr("href");
			$(this).css('cursor','not-allowed');
		})
		//不能连接
		$('.lianjie'+index).hover(function () {
			$(this).removeAttr("href");
			$(this).css('cursor','not-allowed');
		})
		//可以开机
		$('.start'+index).hover(function () {
			$(this).attr("href","javascript:start('" +uuid +"')");
			$(this).css('cursor','pointer');
		})
		//可以删除
		$('.shanqu'+index).hover(function () {
			$(this).attr("href","javascript:destroy('" +uuid +"')");
			$(this).css('cursor','pointer');
		})
		$('.xnj_user_tb' + index).html("已关闭");
	}
}

