#!/usr/bin/env python
#coding=utf8
import pika
import signal
import simplejson
import os
import sys
import fcntl
from daemon import Daemon
from threading import Thread, Event
import datetime

#定义进程运行文件,以及日志文件
runfile = "/var/run/vboxagent.pid"
logfile = "/var/log/vboxagent.log"

connection = None
channel = None
routings=["VmCreate","VmStart","VmStop","VmReboot","VmDelete"]
try:
    connection = pika.BlockingConnection(pika.ConnectionParameters('127.0.0.1'))
    channel = connection.channel()
    #定义routings key,VmCreate,VmStart,VmStop,VmReboot,VmDelete
except:
    connection = None
    channel = None
    

#定义消息回调函数
def VmCreate(vm):
    vmname = vm['vmname']
    cmd = "vboxmanage  clonevm trunkey-x86 --snapshot 0 --mode machine --options link --name %s  --register" % vmname
    os.system(cmd)
    print "VmCreate:%s" % vmname
	
def VmStart(vm):
    vmname = vm['vmname']
    cmd = "vboxmanage startvm %s --type headless" % vmname
    os.system(cmd)
    vmport = vm.get("vmport")
    if vmport:
        cmd = "vboxmanage controlvm  %s  vrdeport %s" % (vmname,vmport)
        os.system(cmd)
    print "VmStart:%s" % vmname

def VmStop(vm):
    vmname = vm['vmname']
    cmd = "vboxmanage  controlvm %s poweroff" % vmname
    os.system(cmd)
    print "VmStop:%s" % vmname

def VmReboot(vm):
    vmname = vm['vmname']
    cmd = "vboxmanage  controlvm %s reset" % vmname
    os.system(cmd)
    print "VmReboot:%s" % vmname
	
def VmDelete(vm):
    vmname = vm['vmname']
    cmd = "vboxmanage  controlvm %s poweroff" % vmname
    os.system(cmd)
    cmd = "vboxmanage unregistervm %s --delete" % vmname
    os.system(cmd)
    print "VmDelete:%s" %vmname

#消息处理函数
def message_handle(ch, method, properties, body):
    vmins = simplejson.loads(body)
    func = vmins.get('func')
    if func:
        eval(func)(vmins['vm'])
    ch.basic_ack(delivery_tag = method.delivery_tag)

def start():
    print 'start recevie'
    global connection,channel,routings
    if  connection is None or  connection.is_closed:
        connection = pika.BlockingConnection(pika.ConnectionParameters('127.0.0.1'))
        channel = connection.channel()
 
    #定义交换机，设置类型为direct
    channel.exchange_declare(exchange='VmInstance', type='direct',durable=True)

    for routing in routings:
        #生成临时队列，并绑定到交换机上，设置路由键
        result = channel.queue_declare(queue=routing,durable=True)
        queue_name = result.method.queue
        channel.queue_bind(exchange='VmInstance',queue=queue_name,routing_key=routing)
        channel.basic_qos(prefetch_count=1)
        channel.basic_consume(message_handle, queue=queue_name)

    channel.start_consuming()

def stop():
    print 'stop receive'
    global connection,channel
    #删除交换机
    channel.exchange_delete("VmInstance")
    #删除队列
    for routing in routings:
        channel.queue_delete(routing) 
    #关闭连接
    connection.close() 

def restart():
    print 'restart receive'
    stop()
    start()

def ensure_dir(f):
    d = os.path.dirname(f)
    if not os.path.exists(d):
        os.makedirs(d)

#定义信号函数处理键盘事件
evt = Event()
def handler(signum,frame):
    print 'Signal handler called with signal', signum
    evt.set()

#定义守护进程
class SysProbeDaemon(Daemon):
    def __init__(self, pidfile):
        Daemon.__init__(self, pidfile, stdout=logfile, stderr=logfile)
   
    def run(self):
        start()
        signal.signal(signal.SIGTERM, handler) 
        while not evt.isSet(): 
            evt.wait(600)
        print str(datetime.datetime.now())
        print "receive stopped" 
   

def main():
    #定义信号函数
    signal.signal(signal.SIGINT,handler)
    signal.signal(signal.SIGTERM,handler)
    #执行参数回调
    func = sys.argv[1]
    eval(func)()

if __name__ == '__main__':
    ensure_dir(logfile)
    ensure_dir(runfile)
    daemon = SysProbeDaemon(runfile)
    if len(sys.argv) == 2:
        if 'start' == sys.argv[1]:
            daemon.start()
        elif 'stop' == sys.argv[1]:
            daemon.stop()
        elif 'status' == sys.argv[1]:
            daemon.status()
        elif 'restart' == sys.argv[1]:
            daemon.restart()
        else:
            print "Unknown command"
            sys.exit(2)
        sys.exit(0)
    else:
        print "usage: %s start|stop|restart|status" % sys.argv[0]
        sys.exit(2)
