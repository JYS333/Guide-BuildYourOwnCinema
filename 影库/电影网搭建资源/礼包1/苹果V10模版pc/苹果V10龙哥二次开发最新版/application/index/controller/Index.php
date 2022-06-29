<?php
namespace app\index\controller;

class Index extends Base
{
    public function index()
    {
        $param = mac_param_url();
        return $this->fetch( 'index/index');
    }

    public function wap_index()
    {
        $param = mac_param_url();
        return $this->fetch( 'index/index');
    }

}
