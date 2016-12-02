<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/10
 * Time: 10:40
 */

class RabbitMQ
{
    private $conn = null;
    private $channel = null;
    private $exchange = null;

    public function connect(){
	 $CONN_ARGS = array(
		'host'  => '127.0.0.1',
		'port'  => '5672',
		'login' => 'guest',
		'password' => 'guest',
		'vhost' => '/'
	    );

        $this->conn = new AMQPConnection($CONN_ARGS);
        if(!$this->conn->connect()){
            die("cannot connect to the broker!");
        }
        $this->channel = new AMQPChannel($this->conn);
    }

    public function disconnect(){
        $this->conn->disconnect();
    }

    public function create_exchange($ename){
        //创建交换机
        $this->exchange = new AMQPExchange($this->channel);//创建交换机
        $this->exchange->setName($ename);//设置交换机名称
        $this->exchange->setType(AMQP_EX_TYPE_DIRECT);//设置direct类型交换机
        $this->exchange->setFlags(AMQP_DURABLE);//设置持久化
        $this->exchange->declareExchange();//定义交换机
    }

    public function publish($message,$kname){
        $this->channel->startTransaction();
        $this->exchange->publish(json_encode($message),$kname);//向指定路由发送消息
        $this->channel->commitTransaction();
    }

    //绑定交换机和队列
    public function bindQueue($qname,$ename,$kname){
        $this->queue = new AMQPQueue($this->channel);
        $this->queue->setName($qname);
        $this->queue->setFlags(AMQP_DURABLE);
        $this->queue->declareQueue();
        $this->queue->bind($ename,$kname);
        //阻塞接受消息
        $this->queue->consume(array($this,'processMessage'));
    }

    //消息回调处理函数
    public function  processMessage($envelope,$queue){
        $msg = $envelope->getBody();
        echo $msg."\n";
        $this->queue->ack($envelope->getDeliveryTag());
    }
}
