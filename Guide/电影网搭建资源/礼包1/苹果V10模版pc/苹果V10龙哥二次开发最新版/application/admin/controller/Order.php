<?php
namespace app\admin\controller;
use think\Db;

class Order extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : $param['page'];
        $param['limit'] = intval($param['limit']) <1 ? $this->_pagesize : $param['limit'];
        $where=[];
        if(!empty($param['status'])){
            $where['order_status'] = ['like',$param['status']];
        }
        if(!empty($param['uid'])){
            $where['o.user_id'] = ['eq',$param['uid'] ];
        }
        if(!empty($param['wd'])){
            $where['order_code'] = ['like','%'.$param['wd'].'%'];
        }

        $order='order_id desc';
        $res = model('Order')->listData($where,$order,$param['page'],$param['limit']);

        $this->assign('list',$res['list']);
        $this->assign('total',$res['total']);
        $this->assign('page',$res['page']);
        $this->assign('limit',$res['limit']);

        $param['page'] = '{page}';
        $param['limit'] = '{limit}';
        $this->assign('param',$param);


        $this->assign('title','订单管理');
        return $this->fetch('admin@order/index');
    }


    public function del()
    {
        $param = input();
        $ids = $param['ids'];

        if(!empty($ids)){
            $where=[];
            $where['order_id'] = ['in',$ids];
            $res = model('Order')->delData($where);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }
        return $this->error('参数错误');
    }



}
