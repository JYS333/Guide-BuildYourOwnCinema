<?php
namespace app\admin\controller;
use think\Db;
use think\Config;
use think\Cache;

class System extends Base
{
    
	public function test_email() {
		$post = input();
		$mail = new \phpmailer\src\PHPMailer(); //实例化
	   // $mail->SMTPDebug = 2;                                    // 开启Debug
		$mail->isSMTP();                                        // 使用SMTP
		$mail->Host = $post['host'];                        // 服务器地址
		$mail->SMTPAuth = true;                                    // 开启SMTP验证
		$mail->Username = $post['username'];                // SMTP 用户名（你要使用的邮件发送账号）
		$mail->Password = $post['password'];// QQ邮箱的是 开启后 生成的授权码 不是密码
		$mail->SMTPSecure = 'tls';                                // 开启TLS 可选
		$mail->Port = $post['port'];                                        // 端口
		$mail->setFrom($post['username']);            // 来自
		$mail->addAddress($post['test']);        // 添加一个收件人
		$mail->isHTML(true);                                        // 设置邮件格式为HTML
		$mail->Subject = 'test';
		$mail->Body    = 'test';
		$mail->AltBody = 'test';
		if($mail->send()){
            return json(['code'=>1,'msg'=>'测试成功']);
        }
        return json(['code'=>1001,'msg'=>'测试失败']);
	}

	public function test_cache()
    {
        $param = input();

        if(!isset($param['type']) || empty($param['host']) || empty($param['port'])){
            return $this->error('参数错误!');
        }

        $options = [
            'type' => $param['type'] == 1 ? 'memcache' :'redis',
            'port' => $param['port'],
            'password' => $param['password']
        ];

        Cache::connect($options);
        Cache::set('test','test');

        return json(['code'=>1,'msg'=>'测试成功']);
    }

	public function config()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['site'] = $config['site'];
            $config_new['app'] = $config['app'];
            $config_new['user'] = $config['user'];
            $config_new['gbook'] = $config['gbook'];
            $config_new['comment'] = $config['comment'];
            $config_new['upload'] = $config['upload'];
            $config_new['email'] = $config['email'];

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }


        $templates = glob('./template' . '/*', GLOB_ONLYDIR);
        foreach ($templates as $k => &$v) {
            $v = str_replace('./template/', '', $v);
        }
        $this->assign('templates', $templates);

        $usergroup = Db::name('group')->select();
        $this->assign('usergroup', $usergroup);

        $config = config('maccms');
        $this->assign('config', $config);
        $this->assign('title', '网站参数配置');
        return $this->fetch('admin@system/config');
    }

    public function configurl()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['view'] = $config['view'];
            $config_new['path'] = $config['path'];
            $config_new['rewrite'] = $config['rewrite'];

            //写路由规则文件
            $route=[];
            $rows = explode(chr(13),str_replace(chr(10),'',$config['rewrite']['route']));
            foreach($rows as $r){
                if(strpos($r,'=>')!==false ){
                    $a = explode('=>', $r);
                    $rule=[];
                    if(strpos($a,':id')!==false){
                        $rule['id'] = '\d+';
                    }
                    $route[ trim($a[0]) ] = [ trim($a[1]),[], $rule ];
                }
            }
            $res = mac_arr2file(APP_PATH.'route.php', $route);
            if($res===false){
                return $this->error('保存路由配置失败，请重试!');
            }

            //写扩展配置
            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);
            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存配置文件失败，请重试!');
            }
            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', 'url参数配置');
        return $this->fetch('admin@system/configurl');
    }

    public function configweixin()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['weixin'] = $config['weixin'];

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', '微信对接配置');
        return $this->fetch('admin@system/configweixin');
    }

    public function configpay()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['pay'] = $config['pay'];

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', '在线支付配置');
        return $this->fetch('admin@system/configpay');
    }

    public function configconnect()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['connect'] = $config['connect'];

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res= mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', '整合登录配置');
        return $this->fetch('admin@system/configconnect');
    }

    public function configapi()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['api'] = $config['api'];

            $config_new['api']['vod']['auth'] = mac_replace_text($config_new['api']['vod']['auth'], 2);
            $config_new['api']['art']['auth'] = mac_replace_text($config_new['api']['art']['auth'], 2);

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', '采集接口API配置');
        return $this->fetch('admin@system/configapi');
    }


    public function configinterface()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['interface'] = $config['interface'];
            $config_new['interface']['vodtype'] = mac_replace_text($config_new['interface']['vodtype'], 2);
            $config_new['interface']['arttype'] = mac_replace_text($config_new['interface']['arttype'], 2);

            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }

            //保存缓存
            mac_interface_type();

            return $this->success('保存成功!');
        }

        $this->assign('config', config('maccms'));
        $this->assign('title', '站外入库配置');
        return $this->fetch('admin@system/configinterface');
    }

    public function configcollect()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['collect'] = $config['collect'];
            if (empty($config_new['collect']['vod']['inrule'])) {
                $config_new['collect']['vod']['inrule'] = ['a'];
            }
            if (empty($config_new['collect']['vod']['uprule'])) {
                $config_new['collect']['vod']['uprule'] = [];
            }
            if (empty($config_new['collect']['art']['inrule'])) {
                $config_new['collect']['art']['inrule'] = ['a'];
            }
            if (empty($config_new['collect']['art']['uprule'])) {
                $config_new['collect']['art']['uprule'] = [];
            }

            $config_new['collect']['vod']['inrule'] = ',' . join(',', $config_new['collect']['vod']['inrule']);
            $config_new['collect']['vod']['uprule'] = ',' . join(',', $config_new['collect']['vod']['uprule']);
            $config_new['collect']['art']['inrule'] = ',' . join(',', $config_new['collect']['art']['inrule']);
            $config_new['collect']['art']['uprule'] = ',' . join(',', $config_new['collect']['art']['uprule']);

            $config_new['collect']['vod']['thesaurus'] = mac_replace_text($config_new['collect']['vod']['thesaurus'],2);
            $config_new['collect']['vod']['words'] = mac_replace_text($config_new['collect']['vod']['words'],2);
            $config_new['collect']['art']['thesaurus'] = mac_replace_text($config_new['collect']['art']['thesaurus'],2);
            $config_new['collect']['art']['words'] = mac_replace_text($config_new['collect']['art']['words'],2);


            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功!');
        }


        $this->assign('config', config('maccms'));
        $this->assign('title', '采集参数配置');
        return $this->fetch('admin@system/configcollect');
    }


    public function configplay()
    {
        if (Request()->isPost()) {
            $config = input();
            $config_new['play'] = $config['play'];
            $config_old = config('maccms');
            $config_new = array_merge($config_old, $config_new);

            $res = mac_arr2file(APP_PATH.'extra/maccms.php', $config_new);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }

            $path = './static/js/playerconfig.js';
            if(!file_exists($path)){ $path .= '.bak'; }
            $fc = @file_get_contents( $path );
            $jsb = mac_get_body($fc,'//参数开始','//参数结束');
            $content = 'MacPlayerConfig='.json_encode($config['play']) .';';
            $fc = str_replace($jsb,"\r\n".$content."\r\n",$fc);
            $res = @fwrite(fopen('./static/js/playerconfig.js','wb'),$fc);
            if($res===false){
                return $this->error('保存失败，请重试!');
            }
            return $this->success('保存成功！');
        }

        $fp = './static/js/playerconfig.js';
        if(!file_exists($fp)){ $fp .= '.bak'; }
        $fc = file_get_contents( $fp );
        $jsb = trim(mac_get_body($fc,'//参数开始','//参数结束'));
        $jsb = substr($jsb,16,strlen($jsb)-17);

        $play = json_decode($jsb,true);
        $this->assign('play',$play);
        $this->assign('title', '播放器参数配置');
        return $this->fetch('admin@system/configplay');
    }

}
