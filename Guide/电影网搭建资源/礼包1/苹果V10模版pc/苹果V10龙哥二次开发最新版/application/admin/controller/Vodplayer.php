<?php
namespace app\admin\controller;
use think\Db;

class VodPlayer extends Base
{
    var $_pre;
    public function __construct()
    {
        parent::__construct();
        $this->_pre = 'vodplayer';
    }

    public function index()
    {
        $list = config($this->_pre);
        $this->assign('list',$list);
        $this->assign('title','播放器管理');
        return $this->fetch('admin@vodplayer/index');
    }

    public function info()
    {
        $param = input();
        $list = config($this->_pre);
        if (Request()->isPost()) {
            unset($param['flag']);
            $code = $param['code'];
            unset($param['code']);

            $list[$param['from']] = $param;
            $res = mac_arr2file( APP_PATH .'extra/'.$this->_pre.'.php', $list);
            if($res===false){
                return $this->error('保存配置文件失败，请重试!');
            }

            $res = fwrite(fopen('./static/player/' . $param['from'].'.js','wb'),$code);
            if($res===false){
                return $this->error('保存代码文件失败，请重试!');
            }

            return $this->success('保存成功!');
        }

        $info = $list[$param['id']];
        if(!empty($info)){
            $code = file_get_contents('./static/player/' . $param['id'].'.js');
            $info['code'] = $code;
        }
        $this->assign('info',$info);
        $this->assign('title','信息管理');
        return $this->fetch('admin@vodplayer/info');
    }

    public function del()
    {
        $param = input();
        $list = config($this->_pre);
        unset($list[$param['ids']]);
        $res = mac_arr2file(APP_PATH. 'extra/'.$this->_pre.'.php', $list);
        if($res===false){
            return $this->error('删除失败，请重试!');
        }

        return $this->success('删除成功!');
    }

    public function field()
    {
        $param = input();
        $ids = $param['ids'];
        $col = $param['col'];
        $val = $param['val'];

        if(!empty($ids) && in_array($col,['ps','status'])){
            $list = config($this->_pre);
            $ids = explode(',',$ids);
            foreach($list as $k=>&$v){
                if(in_array($k,$ids)){
                    $v[$col] = $val;
                }
            }
            $res = mac_arr2file(APP_PATH. 'extra/'.$this->_pre.'.php', $list);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }
        return $this->error('参数错误');
    }
}
