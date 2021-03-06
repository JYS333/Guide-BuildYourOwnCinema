<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use login\ThinkOauth;
use app\index\event\LoginEvent;

define('THIRD_LOGIN_CALLBACK', 'http://' . $_SERVER['SERVER_NAME'] . '/index.php/user/logincallback/type/');

class User extends Base
{
    var $_user;

    public function __construct()
    {
        parent::__construct();

        //判断用户登录状态
        $ac = request()->action();
        if(in_array($ac,['login','logout','ajax_login','reg','findpass','oauth','callback'])){

        }
        else{
            $res = model('User')->checkLogin();
            if($res['code']>1){
                model('User')->logout();
                return $this->error($res['msg'], url('user/login'));
            }
            $this->_user = $res['info'];
            $this->assign('obj',$this->_user);
        }
    }

    public function ajax_login()
    {
        return $this->fetch('user/ajax_login');
    }

    public function ajax_info()
    {
        return $this->fetch('user/ajax_info');
    }

    public function ajax_ulog()
    {
        $param = input();
        if($param['ac']=='set') {
            $data = [];
            $data['ulog_mid'] = intval($param['mid']);
            $data['ulog_rid'] = intval($param['id']);
            $data['ulog_type'] = intval($param['type']);
            $data['ulog_sid'] = intval($param['sid']);
            $data['ulog_nid'] = intval($param['nid']);

            if($data['ulog_mid'] ==1 && $data['ulog_type']>3){
                $where2=[];
                $where2['vod_id'] = $data['ulog_rid'];
                $res = model('Vod')->infoData($where2);
                if($res['code']>1){
                    return $res;
                }
                $flag = $data['ulog_type'] == 4 ?'play' :'down';
                $data['ulog_points'] = $res['info'][ 'vod_points_'.$flag ];
            }
            $data['ulog_points'] = intval($data['ulog_points']);
            $res = model('Ulog')->infoData($data);
            if($res['code']==1){
                return json($res);
            }
            if($data['ulog_points']==0) {
                $res = model('Ulog')->saveData($data);
            }
            else{
                $res = ['code'=>2001,'msg'=>'收费收据需单独记录'];
            }
        }
        else{
            $where=[];
            $where['user_id'] = $this->_user['user_id'];
            $param['page'] = intval($param['page']) <1 ? 1 : intval($param['page']);
            $param['limit'] = intval($param['limit']) <1 ? 10 : intval($param['limit']);
            $order='ulog_id desc';
            $res = model('Ulog')->listData($where,$order,$param['page'],$param['limit']);
        }
        return json($res);
    }

    public function ajax_buy_popedom()
    {
        $param = input();
        $data=[];
        $data['ulog_mid'] = 1;
        $data['ulog_rid'] = intval($param['id']);
        $data['ulog_sid'] = intval($param['sid']);
        $data['ulog_nid'] = intval($param['nid']);

        if(!in_array($param['type'],['4','5']) || empty($data['ulog_rid'])  || empty($data['ulog_sid'])  || empty($data['ulog_nid'])  ){
            return json(['code'=>2001,'msg'=>'参数错误']);
        }
        $data['ulog_type'] = $param['type'];
        $data['user_id'] = $this->_user['user_id'];

        $where=[];
        $where['vod_id'] = $data['ulog_rid'];
        $res = model('Vod')->infoData($where);
        if($res['code']>1){
            return json([$res]);
        }
        $col = 'vod_points_'.($param['type']=='4'? 'play':'down');
        $data['ulog_points'] = intval($res['info'][$col]);

        $res = model('Ulog')->infoData($data);
        if($res['code']==1){
            return json(['code'=>1,'msg'=>'您已经购买过此条数据，无需再次支付，请刷新页面重试']);
        }

        if ($data['ulog_points'] > $this->_user['user_points']) {
            return json(['code'=>2002,'msg'=>'对不起,查看此页面数据需要[' . $data['ulog_points'] . ']积分，您还剩下[' . $this->_user['user_points'] . ']积分，请先充值！']);
        }
        else {
            $update = [];
            $update['user_points'] = $this->_user['user_points'] - $data['ulog_points'];
            $where = [];
            $where['user_id'] = $this->_user['user_id'];
            $res = model('User')->where($where)->update($update);
            if ($res === false) {
                return json(['code'=>2003,'msg'=>'对不起,更新用户积分信息失败，请刷新重试！']);
            }
            $res = model('Ulog')->saveData($data);
            return json($res);
        }

    }

    public function index()
    {
		return $this->fetch('user/index');
    }

    public function login()
    {
        if (Request()->isPost()) {
            $param = input();
            $data = [];
            $data['user_name'] = htmlspecialchars(urldecode(trim($param['user_name'])));
            $data['user_pwd'] = htmlspecialchars(urldecode(trim($param['user_pwd'])));
            $data['verify'] = $param['verify'];
            $res = model('User')->login($data);
            return json($res);
        }
        if(!empty(cookie('user_id') && !empty(cookie('user_name')))){
            return redirect('user/index');
        }
        return $this->fetch('user/login');
    }

    public function logout()
    {
        $res = model('User')->logout();
        if(request()->isAjax()){
            return json($res);
        }
        else {
            return redirect('user/login');
        }
    }

    public function oauth($type='')
    {
        empty($type) && $this->error('参数错误');
        //加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);
        //跳转到授权页面
        $this->redirect($sns->getRequestCodeURL());
    }

    //授权回调地址
    public function logincallback($type='',$code='')
    {
        if(empty($type) || empty($code)){
            return $this->error('参数错误');
        }
        //加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);
        $extend = null;

        //请妥善保管这里获取到的Token信息，方便以后API调用
        $token = $sns->getAccessToken($code, $extend);
        //获取当前登录用户信息
        if (is_array($token)) {
            $loginEvent = new LoginEvent();
            $res = $loginEvent->$type($token);
            if ($res['code'] ==1) {

                $col = 'user_openid_'.$type;
                //如果已登录,是否需要重新绑定
                $check = model('User')->checkLogin();
                if($check['code'] == 1){

                    if($check['info'][$col] ==  $res['info']['openid']){
                        //无需再次绑定
                        return json(['code'=>1001,'msg'=>'已经绑定该账号']);
                    }
                    else{
                        //解除原有绑定
                        $where = [];
                        $where[$col] = $res['info']['openid'];
                        $update = [];
                        $update[$col] = '';
                        model('User')->where($where)->update($update);
                        //新绑定
                        $where = [];
                        $where['user_id'] = $this->_user['user_id'];
                        $update = [];
                        $update[$col] = $res['info']['openid'];
                        model('User')->where($where)->update($update);
                        return json(['code'=>1,'msg'=>'绑定成功']);
                    }
                }

                $where=[];
                $where[$col] = $res['openid'];
                $res2 = model('User')->infoData($where);
                //未绑定的需要先创建用户并绑定
                if($res2['code']>1){
                    $data =[];
                    $data['user_name'] = htmlspecialchars(urldecode(trim($res['info']['name'])));
                    $data['user_pwd'] = date('YmdH');
                    $data['user_pwd2'] = date('YmdH');
                    $reg = model('User')->register($data,0);
                    if($reg['code']>1){
                        //注册失败
                        return $this->error('同步信息注册失败，请联系管理员');
                    }
                }
                //直接登录。。。
                $login = model('User')->login(['col'=>$col,'openid'=>$res['info']['openid']]);
                if($login['code']>1){
                    return $this->error($login['msg']);
                }
                $this->redirect('user/index');
            }
            else {
                return $this->error($res['msg']);
            }
        }
        else{
            return $this->error('获取第三方用户信息失败，请重试');
        }
    }

    public function bind()
    {
        return $this->fetch('user/bind');
    }

    public function unbind()
    {
        return $this->fetch('user/unbind');
    }

    public function info()
    {
        if (Request()->isPost()) {
			$param = input();

            if(empty($param['user_pwd'])){
                $this->error('请输入原密码');
                return;
            }
            if(md5($param['user_pwd']) != $this->_user['user_pwd']){
                $this->error('原密码错误');
                return;
            }
            if($param['user_pwd1'] != $param['user_pwd2']){
                $this->error('两次输入的新密码不一致');
                return;
            }

			$data = [];
			$data['user_id'] = $this->_user['user_id'];
            $data['user_name'] =  $this->_user['user_name'];
			$data['user_qq'] = htmlspecialchars(urldecode(trim($param['user_qq'])));
			$data['user_phone'] = htmlspecialchars(urldecode(trim($param['user_phone'])));
			$data['user_question'] = htmlspecialchars(urldecode(trim($param['user_question'])));
			$data['user_answer'] = htmlspecialchars(urldecode(trim($param['user_answer'])));
            if(!empty($param['user_pwd2'])){
                $data['user_pwd'] = htmlspecialchars(urldecode(trim($param['user_pwd2'])));
            }

			$res = model('User')->saveData($data);
			if ($res['code'] == 1) {
				$this->success($res['msg']);
				exit;
			}
			$this->error($res['msg']);
			exit;
		}
		return $this->fetch('user/info');
    }

    public function reg()
    {
        if(Request()->isPost()){
            $param = input();
            $data = [];
            $data['user_name'] = htmlspecialchars(urldecode(trim($param['user_name'])));
            $data['user_pwd'] = htmlspecialchars(urldecode(trim($param['user_pwd'])));
            $data['user_pwd2'] = htmlspecialchars(urldecode(trim($param['user_pwd2'])));
            $data['verify'] = $param['verify'];
            $res = model('User')->register($data,1,$param['uid']);
            return json($res);
        }
        return $this->fetch('user/reg');
    }

    public function portrait()
    {
        if($GLOBALS['config']['user']['portrait_status']==0){
            return json(['code'=>0,'msg'=>'未开启自定义头像功能！']);
        }

        $file = request()->file('file');
        if (empty($file)) {
            return json(['code'=>0,'msg'=>'未找到上传的文件(原因：表单名可能错误，默认表单名“file”)！']);
        }
        if ($file->getMime() == 'text/x-php') {
            return json(['code'=>0,'msg'=>'禁止上传php,html文件！']);
        }

        $upload_image_ext = 'jpg,png,gif,jpeg,bmp';
        if ($file->checkExt($upload_image_ext)) {
            $type = 'image';
        }
        else {
            return json(['code'=>0,'msg'=>'非系统允许的上传格式！']);
        }

        // 上传附件路径
        $_upload_path = ROOT_PATH . 'upload' . '/user/' ;
        // 附件访问路径
        $_save_path = 'upload'. '/user/';

        $upfile = $file->move($_upload_path);
        if (!is_file($_upload_path.$upfile->getSaveName())) {
            return json(['code'=>0,'msg'=>'文件上传失败！']);
        }
        $file = $_save_path.str_replace('\\', '/', $upfile->getSaveName());
        $config= [
            'thumb_type'=>6,
            'thumb_size'=> $GLOBALS['config']['user']['portrait_size'],
        ];
        $dd = model('Image')->makethumb($file,$config,'user',0);
        if(!empty($dd)) {
            $update=[];
            $update['user_portrait'] = $dd['thumb'][0]['file'];
            $where=[];
            $where['user_id'] = $this->_user['user_id'];
            $res = model('User')->where($where)->update($update);
            if($res===false){
                return json(['code'=>0,'msg'=>'更新会员头像信息失败！']);
            }
            return json(['code'=>1,'msg'=>'ok','file'=>'http:' . MAC_PATH . $dd['thumb'][0]['file']]);
        }
        else{
            return json(['code'=>0,'msg'=>'生成缩放头像图片文件失败！']);
        }
    }

    public function regcheck()
    {
        $param = input();
        $t = htmlspecialchars(urldecode(trim($param['t'])));
        $str = htmlspecialchars(urldecode(trim($param['str'])));
        $res = model('User')->user_regcheck($t,$str);
        if($res['code']>1){
            return $str;
        }
        return json($res);
    }

    public function findpass()
    {
        if (Request()->isPost()) {
			$param = input();
            $data=[];
            $data['user_name'] = htmlspecialchars(urldecode(trim($param['user_name'])));
            $data['user_question'] = htmlspecialchars(urldecode(trim($param['user_question'])));
            $data['user_answer'] = htmlspecialchars(urldecode(trim($param['user_answer'])));
            $data['user_pwd'] = htmlspecialchars(urldecode(trim($param['user_pwd'])));
            $data['user_pwd2'] = htmlspecialchars(urldecode(trim($param['user_pwd2'])));
            $data['verify'] = $param['verify'];
            $res = model('User')->findpass($data);
			return json($res);
		}
		return $this->fetch('user/findpass');
    }

    public function buy()
    {
        $param = input();
        if (Request()->isPost()) {
            $flag = input('param.flag');
            if($flag=='card'){
                $card_no = htmlspecialchars(urldecode(trim($param['card_no'])));
                $card_pwd =htmlspecialchars(urldecode(trim($param['card_pwd'])));

                $res = model('Card')->useData($card_no,$card_pwd,$this->_user);
                return json($res);
            }
            else {
                $price = input('param.price');
                if (empty($price)) {
                    return json(['code' => 1001, 'msg' => '参数错误']);
                }

                if ($price < $GLOBALS['config']['pay']['min']) {
                    return json(['code' => 1002, 'msg' => '最小充值金额不能低于' . $GLOBALS['config']['pay']['min'] . '元']);
                }

                $data = [];
                $data['user_id'] = $this->_user['user_id'];
                $data['order_code'] = 'PAY' . mac_get_uniqid_code();
                $data['order_price'] = $price;
                $data['order_time'] = time();
                $data['order_points'] = intval($GLOBALS['config']['pay']['scale'] * $price);
                $res = model('Order')->saveData($data);
                $res['data'] = $data;
                return json($res);
            }
        }
        $this->assign('config',$GLOBALS['config']['pay']);
        return $this->fetch('user/buy');
    }

    public function pay()
    {
        $param = input();
        $order_code = htmlspecialchars(urldecode(trim($param['order_code'])));
        $where = [];
        $where['order_code'] = $order_code;
        $where['user_id'] = $this->_user['user_id'];
        $res = model('Order')->infoData($where);
        if($res['code']>1){
            return $this->error($res['msg']);
        }
        $this->assign('config',$GLOBALS['config']['pay']);
        $this->assign('info',$res['info']);
        return $this->fetch('user/pay');
    }

    public function gopay()
    {
        $param = input();

        $order_code = htmlspecialchars(urldecode(trim($param['order_code'])));
        $order_id = intval((trim($param['order_id'])));
        $payment = strtolower(htmlspecialchars(urldecode(trim($param['payment']))));

        if(empty($order_code) && empty($order_id) && empty($payment) ){
            return $this->error('参数错误');
        }

        if($GLOBALS['config']['pay'][$payment]['appid'] == ''){
            return $this->error('该支付选项未开启');
        }

        //核实订单
        $where['order_id'] = $order_id;
        $where['order_code'] = $order_code;
        $where['user_id'] = $this->_user['user_id'];
        $res = model('Order')->infoData($where);
        if($res['code']>1){
            return $this->error('获取单据失败');
        }
        //跳转到相应页面
        model('Pay'. $payment)->submit($this->user,$res['info'],$param);
    }

    public function upgrade()
    {
        if (Request()->isPost()) {
			$param = input();
            $group_id = intval($param['group_id']);
            $long = $param['long'];
            $points_long = ['day'=>86400,'week'=>86400*7,'month'=>86400*30,'year'=>86400*365];

            if (!array_key_exists($long, $points_long)) {
                return json(['code'=>1001,'msg'=>'非法操作']);
            }

            if($group_id <3){
                return json(['code'=>1002,'msg'=>'请选择自定义收费会员组']);
            }

            $group_list = model('Group')->getCache();
            $group_info = $group_list[$group_id];
            if(empty($group_info)){
               return json(['code'=>1003,'msg'=>'获取会员组信息失败']);
            }

            $point = $group_info['group_points_'.$long];
            if($this->_user['user_points'] < $point){
                return json(['code'=>1004,'msg'=>'积分不够，无法升级']);
            }

            $sj = $points_long[$long];
            $end_time = time() + $sj;
            if($this->_user['user_end_time'] > time() ){
                $end_time = $this->_user['user_end_time'] + $sj;
            }

            $where = [];
            $where['user_id'] = $this->_user['user_id'];

            $data = [];
            $data['user_points'] = $this->_user['user_points'] - $point;
            $data['user_end_time'] = $end_time;
            $data['group_id'] = $group_id;

            $res = model('User')->where($where)->update($data);
            if($res===false){
                return json(['code'=>1009,'msg'=>'升级会员组失败']);
            }
            return json(['code'=>1,'msg'=>'升级会员组成功']);
		}

		$group_list = model('Group')->getCache();
		$this->assign('group_list', $group_list);

		return $this->fetch('user/upgrade');
    }

    public function popedom()
    {
        $type_tree = model('Type')->getCache('type_tree');
        $this->assign('type_tree',$type_tree);

        $n=1;
        $ids = [1=>'列表页',2=>'内容页',3=>'播放页',4=>'下载页','5'=>'试看'];
        foreach($type_tree as $k1=>$v1){
            unset($type_tree[$k1]['type_extend']);
            foreach($ids as $a=>$b) {
                $n++;
                if($v1['type_mid'] == 2 && $a>2){
                    break;
                }
                $type_tree[$k1]['popedom'][$b] = model('User')->popedom($v1['type_id'], $a, $this->_user['group_id']);
            }
            foreach($v1['child'] as $k2=>$v2){
                unset($type_tree[$k1]['child'][$k2]['type_extend']);
                foreach($ids as $a=>$b) {
                    $n++;
                    if($v2['type_mid'] == 2 && $a>2){
                        break;
                    }
                    $type_tree[$k1]['child'][$k2]['popedom'][$b] = model('User')->popedom($v2['type_id'], $a, $this->_user['group_id']);
                }
            }
        }

        $this->assign('type_tree',$type_tree);

		return $this->fetch('user/popedom');
    }

    public function plays()
    {
		$param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : intval($param['page']);
        $param['limit'] = intval($param['limit']) <20 ? 20 : intval($param['limit']);

		$where = [];
		$where['user_id'] = $this->_user['user_id'];
        $where['ulog_mid'] = 1;
		$where['ulog_type'] = 4;
		$order = 'ulog_id desc';
		$res = model('Ulog')->listData($where,$order,$param['page'],$param['limit']);

        $this->assign('list',$res['list']);
        $this->assign('title','我的点播');
		$pages = mac_page_param($res['total'], $param['limit'], $param['page'], url('user/plays',['page' => 'PAGELINK']));
		$this->assign('__PAGING__', $pages);
		return $this->fetch('user/plays');
    }

    public function downs()
    {
		$param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : intval($param['page']);
        $param['limit'] = intval($param['limit']) <20 ? 20 : intval($param['limit']);

		$where = [];
		$where['user_id'] = $this->_user['user_id'];
        $where['ulog_mid'] = 1;
		$where['ulog_type'] = 5;
		$order = 'ulog_id desc';
        $res = model('Ulog')->listData($where,$order,$param['page'],$param['limit']);

        $this->assign('list',$res['list']);
        $this->assign('title','我的下载');
		$pages = mac_page_param($res['total'], $param['limit'], $param['page'], url('user/downs',['page' => 'PAGELINK']));
		$this->assign('__PAGING__', $pages);
		return $this->fetch('user/downs');
    }

    public function favs()
    {
		$param = input();
        $param['page'] = intval($param['page']) <1 ? 1 : intval($param['page']);
        $param['limit'] = intval($param['limit']) <20 ? 20 : intval($param['limit']);

		$where = [];
		$where['user_id'] = $this->_user['user_id'];
		$where['ulog_type'] = 2;
		$order = 'ulog_id desc';
		$res = model('Ulog')->listData($where,$order,$param['page'],$param['limit']);


        $this->assign('list',$res['list']);
        $this->assign('title','我的收藏');
		$pages = mac_page_param($res['total'], $param['limit'], $param['page'], url('user/favs',['page' => 'PAGELINK']));
		$this->assign('__PAGING__', $pages);
		return $this->fetch('user/favs');
    }
	
	public function ulog_del() {
		$param = input();
        $ids = $param['ids'];
        $type = $param['type'];
        $all = $param['all'];

        if (!in_array($type, array('1','2','3','4','5'))) {
            return json(['code'=>1001,'msg'=>'参数错误']);
        }

		if(empty($ids) && empty($all)){
		    return json(['code'=>1001,'msg'=>'参数错误']);
        }

		$arr = [];
		$ids = explode(',', $ids);
		foreach ($ids as $k => $v) {
			$v = intval(abs($v));
			$arr[$v] = $v;
		}

		$where = [];
		$where['user_id'] = $this->_user['user_id'];
        $where['ulog_type'] = $type;
        if($all !='1') {
            $where['ulog_id'] = array('in', implode(',', $arr));
        }
		$return = model('Ulog')->delData($where);
		return json($return);
	}

	public function orders()
    {
        $param = input();
        $param['page'] = intval($param['page']) < 1 ? 1 : intval($param['page']);
        $param['limit'] = intval($param['limit']) <20 ? 20 : intval($param['limit']);

        $where=[];
        $where['o.user_id'] = $this->_user['user_id'];

        $order = 'o.order_id desc';
        $res = model('Order')->listData($where,$order,$param['page'],$param['limit']);

        $pages = mac_page_param($res['total'], $param['limit'], $param['page'], url('user/orders',['page' => 'PAGELINK']));
        $this->assign('__PAGING__', $pages);

        $this->assign('list',$res['list']);
        return $this->fetch('user/orders');
    }

    public function cards()
    {
        $param = input();
        $param['page'] = intval($param['page']) < 1 ? 1 : intval($param['page']);
        $param['limit'] = intval($param['limit']) <20 ? 20 : intval($param['limit']);

        $where=[];
        $where['user_id'] = $this->_user['user_id'];
        $where['card_use_status'] = 1;

        $order = 'card_id desc';
        $res = model('Card')->listData($where,$order,$param['page'],$param['limit']);

        $pages = mac_page_param($res['total'], $param['limit'], $param['page'], url('user/cards',['page' => 'PAGELINK']));
        $this->assign('__PAGING__', $pages);

        $this->assign('list',$res['list']);
        return $this->fetch('user/cards');
    }

}
