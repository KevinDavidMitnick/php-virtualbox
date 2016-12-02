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
        $list   = D('ucenter_member')->where($map)->select();
        $this->assign('list', $list);
        $this->display();
    }
}