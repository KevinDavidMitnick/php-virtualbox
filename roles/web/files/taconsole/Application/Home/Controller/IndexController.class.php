<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use OT\DataDictionary;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends HomeController {

	//系统首页
    public function index(){
        $uid  = is_login();
        $uid || $this->redirect('User/login');

        $list = D('Member')->get_vminfo($uid);
        $is_admin = $uid == 1 ? 1 : 0;
        $this->assign("is_admin",$is_admin);
        $this->assign('list',$list);
        $this->display();
    }

    public function vm_create(){
        $vm['uid'] = is_login();//trim($_REQUEST['uid']);
        //判断是否超过最大模拟器开通限制，如果超过则失败返回.
        $cnt = M("vminfo")->where(array("uid" => $vm["uid"]))->count();
        if($cnt >= VM_LIMIT){
            $fail["info"]="模拟器创建失败,您最多只能创建".$cnt."个模拟器!";
            $fail['status']=0;
            $this->ajaxReturn($fail);
            return;
        }

        $vm['vmname'] = M('vminfo')->order('vmname DESC')->getField('vmname')+1;
        $vm['ip'] = VMIP;
        $vm['port'] = M('vminfo')->order('port DESC')->getField('port')+1;
        $vm['port'] = $vm['port'] < 1024 ? 6001 : $vm['port'];
        $vm['vmid'] = "shutdown";

        //create vm on local system.
        $message = array(
            'func' => "VmCreate",
            'vm' => array("vmname" => $vm['vmname'])
        );
        sendMessage($message);
        $success["info"]="模拟器创建成功";
        $success["status"]=1;

        $fail["info"]="模拟器创建失败";
        $fail['status']=0;
        $id = D('vminfo')->add($vm);
        if($id){
            $this->ajaxReturn($success);
        }else{
                $this->ajaxReturn($fail);
        }
    }

    public function vm_start(){
        $vmname = trim($_POST["vmname"]);
        $vmport = M('vminfo')->where(array("vmname" => $vmname))->getField('port');
        $vm['vmid'] = "running";

        //create vm on local system.
        $message = array(
            'func' => "VmStart",
            'vm' => array("vmname" => $vmname,"vmport" => $vmport)
        );
        $str = sendMessage($message);
        $success["info"]="模拟器启动成功";
        $success["status"]=1;
        $success["str"] = $str;

        $fail["info"]="模拟器启动失败";
        $fail['status']=0;
        $id = D('vminfo')->where("vmname=".$vmname)->save($vm);
        if($id){
            $this->ajaxReturn($success);
        }else{
            $this->ajaxReturn($fail);
        }
    }

    public function vm_stop(){
        $vmname = trim($_POST["vmname"]);
        $vm['vmid'] = "shutdown";

        //create vm on local system.
        $message = array(
            'func' => "VmStop",
            'vm' => array("vmname" => $vmname)
        );
        sendMessage($message);
        $success["info"]="模拟器关闭成功";
        $success["status"]=1;

        $fail["info"]="模拟器关闭失败";
        $fail['status']=0;
        $id = D('vminfo')->where("vmname=".$vmname)->save($vm);
        if($id){
            $this->ajaxReturn($success);
        }else{
            $this->ajaxReturn($fail);
        }
    }

    public function vm_reboot(){
        $vmname = trim($_POST["vmname"]);
        $vmport = M('vminfo')->where(array("vmname" => $vmname))->getField('port');

        //create vm on local system.
        $message = array(
            'func' => "VmReboot",
            'vm' => array("vmname" => $vmname,"vmport" => $vmport)
        );
        sendMessage($message);
        $success["info"]="模拟器启动成功";
        $success["status"]=1;

        $this->ajaxReturn($success);
    }

    public function vm_delete(){
        $vmname = trim($_POST["vmname"]);

        //create vm on local system.
        $message = array(
            'func' => "VmDelete",
            'vm' => array("vmname" => $vmname)
        );
        sendMessage($message);
        $success["info"]="模拟器删除成功";
        $success["status"]=1;

        $fail["info"]="模拟器删除失败";
        $fail['status']=0;
        $id = D('vminfo')->where("vmname=".$vmname)->delete();
        if($id){
            $this->ajaxReturn($success);
        }else{
            $this->ajaxReturn($fail);
        }
    }

    public function manage(){
        $map['id']  =   array('neq',1);
        $User = D('ucenter_member'); // 实例化User对象
	$count = $User->where($map)->count();// 查询满足要求的总记录数
	$Page = new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $Page->lastSuffix = false;
	$Page -> setConfig('header','(总用户数：%TOTAL_ROW%个)');
	$Page -> setConfig('first','首页');
	$Page -> setConfig('last','尾页');
	$Page -> setConfig('prev','上一页');
	$Page -> setConfig('next','下一页');
	$Page -> setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

	// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
	$list = $User->where($map)->order('username')->limit($Page->firstRow.','.$Page->listRows)->select();
	$show = $Page->show();// 分页显示输出
	$this->assign('list',$list);// 赋值数据集
	$this->assign('page',$show);// 赋值分页输出
	$this->display(); // 输出模板    
    }
}






