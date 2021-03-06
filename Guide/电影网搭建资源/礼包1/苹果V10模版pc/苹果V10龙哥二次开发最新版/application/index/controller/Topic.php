<?php
namespace app\index\controller;
use think\Controller;

class Topic extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->label_topic_index();
        return $this->fetch('topic/index');
    }

    public function search()
    {
        $param = mac_param_url();
        $this->assign('param',$param);
        return $this->fetch('topic/search');
    }

    public function detail()
    {
        $info = $this->label_topic_detail();
        return $this->fetch(  mac_tpl_fetch('topic',$info['topic_tpl'],'detail')  );
    }

    public function ajax_detail()
    {
        $info = $this->label_topic_detail();
        return $this->fetch('topic/ajax_detail');
    }

}
