<?php
namespace app\admin\controller;
use think\Db;
use think\Cache;

class Collect extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : $param['page'];
        $param['limit'] = intval($param['limit']) <1 ? 100 : $param['limit'];
        $where=[];

        $order='collect_id desc';
        $res = model('Collect')->listData($where,$order,$param['page'],$param['limit']);

        $this->assign('list',$res['list']);
        $this->assign('total',$res['total']);
        $this->assign('page',$res['page']);
        $this->assign('limit',$res['limit']);

        $param['page'] = '{page}';
        $param['limit'] = '{limit}';
        $this->assign('param',$param);

        $collect_break_vod = Cache::get('collect_break_vod');
        $collect_break_art = Cache::get('collect_break_art');

        $this->assign('collect_break_vod',$collect_break_vod);
        $this->assign('collect_break_art',$collect_break_art);

        $this->assign('title', '采集资源管理');
        return $this->fetch('admin@collect/index');
    }

    public function test()
    {
        $param = input();
        $res = model('Collect')->vod($param);
        return json($res);
    }

    public function info()
    {
        if (Request()->isPost()) {
            $param = input('post.');
            $res = model('Collect')->saveData($param);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }

        $id = input('id');
        $where=[];
        $where['collect_id'] = ['eq',$id];
        $res = model('Collect')->infoData($where);
        $this->assign('info',$res['info']);
        $this->assign('title','采集接口信息');
        return $this->fetch('admin@collect/info');
    }

    public function del()
    {
        $param = input();
        $ids = $param['ids'];

        if(!empty($ids)){
            $where=[];
            $where['collect_id'] = ['in',$ids];

            $res = model('Collect')->delData($where);
            if($res['code']>1){
                return $this->error($res['msg']);
            }
            return $this->success($res['msg']);
        }
        return $this->error('参数错误');
    }


    public function union()
    {
        $collect_break_vod = Cache::get('collect_break_vod');
        $collect_break_art = Cache::get('collect_break_art');

        $this->assign('collect_break_vod',$collect_break_vod);
        $this->assign('collect_break_art',$collect_break_art);

        $this->assign('title', '联盟资源库');
        return $this->fetch('admin@collect/union');
    }

    public function load()
    {
        $param = input();

        $collect_break = Cache::get('collect_break'. $param['flag']);
        $url = $this->_ref;
        if(!empty($collect_break)){
            echo '正在载入断点位置，请稍后。。。';
            $url = $collect_break;
        }
        mac_jump($url);
    }

    public function api()
    {
        $param = input();

        //分类
        $type_list = model('Type')->getCache('type_list');
        $this->assign('type_list',$type_list);

        if(!empty($param['pg'])){
            $param['page'] = $param['pg'];
            unset($param['pg']);
        }
        if($param['mid']=='' || $param['mid']=='1'){
            return $this->vod($param);
        }
        elseif($param['mid']=='2'){
            return $this->art($param);
        }
    }

    public function timing()
    {
        return $this->fetch('admin@collect/timing');
    }

    public function bind()
    {
        $param = input();
        $ids = $param['ids'];
        $col = $param['col'];
        $val = $param['val'];

        if(!empty($col)){
            $config = config('bind');
            $config[$col] = intval($val);
            $data = [];
            $data['id'] = $col;
            $data['st'] = 0;
            if(intval($val)>0){
                $data['st'] = 1;
            }

            $res = mac_arr2file( APP_PATH .'extra/bind.php', $config);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!',null, $data);
        }
        return $this->error('参数错误');
    }

    public function vod($param)
    {
        if($param['ac'] != 'list'){
            Cache::set('collect_break_vod', url('collect/api').'?'. http_build_query($param) );
        }
        $res = model('Collect')->vod($param);
        if($res['code']>1){
            return $this->error($res['msg']);
        }
        Cache::rm('collect_break_vod');

        if($param['ac'] == 'list'){

            $bind_list = config('bind');
            foreach($res['type'] as $k=>$v){
                $key = $param['cjflag'] . '_' . $v['type_id'];
                $res['type'][$k]['isbind'] = 0;
                if( intval($bind_list[$key])>0 ){
                    $res['type'][$k]['isbind'] = 1;
                }
            }

            $this->assign('page',$res['page']);
            $this->assign('type',$res['type']);
            $this->assign('list',$res['data']);

            $this->assign('total',$res['page']['recordcount']);
            $this->assign('page',$res['page']['page']);
            $this->assign('limit',$res['page']['pagesize']);

            $param['page'] = '{page}';
            $param['limit'] = '{limit}';
            $this->assign('param',$param);

            $this->assign('param_str',http_build_query($param)) ;

            return $this->fetch('admin@collect/vod');
        }

        mac_echo('<style type="text/css">body{font-size:12px;color: #333333;line-height:21px;}span{font-weight:bold;color:#FF0000}</style>');
        model('Collect')->vod_data($param,$res );
    }

    public function art($param)
    {
        if($param['ac'] != 'list'){
            Cache::set('collect_break_art', url('collect/api').'?'. http_build_query($param) );
        }
        $res = model('Collect')->art($param);
        if($res['code']>1){
            return $this->error($res['msg']);
        }
        Cache::rm('collect_break_art');

        if($param['ac'] == 'list'){

            $bind_list = config('bind');
            foreach($res['type'] as $k=>$v){
                $key = $param['cjflag'] . '_' . $v['type_id'];
                $res['type'][$k]['isbind'] = 0;
                if( intval($bind_list[$key])>0 ){
                    $res['type'][$k]['isbind'] = 1;
                }
            }

            $this->assign('page',$res['page']);
            $this->assign('type',$res['type']);
            $this->assign('list',$res['data']);

            $this->assign('total',$res['page']['recordcount']);
            $this->assign('page',$res['page']['page']);
            $this->assign('limit',$res['page']['pagesize']);

            $param['page'] = '{page}';
            $param['limit'] = '{limit}';
            $this->assign('param',$param);

            $this->assign('param_str',http_build_query($param)) ;

            return $this->fetch('admin@collect/art');
        }

        mac_echo('<style type="text/css">body{font-size:12px;color: #333333;line-height:21px;}span{font-weight:bold;color:#FF0000}</style>');
        model('Collect')->art_data($param,$res );
    }


}
