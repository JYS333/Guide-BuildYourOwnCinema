<?php
namespace app\common\behavior;

class Init
{
    public function run(&$params)
    {
        $module = '';
        $dispatch = request()->dispatch();
        if (isset($dispatch['module'])) {
            $module = $dispatch['module'][0];
        }

        if( $module =='install'){
            return;
        }

        $config = config('maccms');

        $TMP_ISWAP = 0;
        $TMP_TEMPLATEDIR = $config['site']['template_dir'];
        $TMP_HTMLDIR = $config['site']['html_dir'];

        if($config['site']['mob_status']==1 && $_SERVER['HTTP_HOST']==$config['site']['site_wapurl']) {
            $TMP_ISWAP = 1;
            $TMP_TEMPLATEDIR = $config['site']['mob_template_dir'];
            $TMP_HTMLDIR = $config['site']['mob_html_dir'];
        }

        define('MAC_URL','http://www.maccms.com/');
        define('MAC_NAME','苹果CMS');
        define('MAC_PATH', $config['site']['install_dir'] .'');
        define('MAC_MOB', $TMP_ISWAP);
        define('MAC_ROOT_TEMPLATE', ROOT_PATH .'template/'.$TMP_TEMPLATEDIR.'/'. $TMP_HTMLDIR .'/');
        define('MAC_PATH_TEMPLATE', MAC_PATH.'template/'.$TMP_TEMPLATEDIR.'/');
        define('MAC_PATH_TPL', MAC_PATH_TEMPLATE. $TMP_HTMLDIR  .'/');
        define('MAC_PAGE_SP', $config['path']['page_sp'] .'');
        define('MAC_PLAYER_SORT', $config['app']['player_sort'] );

        define('ADDON_PATH', ROOT_PATH . 'addons' . DS);

        $GLOBALS['config'] = $config;

        config('dispatch_success_tmpl','public/jump');
        config('dispatch_error_tmpl','public/jump');
        config('template.view_path', 'template/' . $TMP_TEMPLATEDIR .'/' . $TMP_HTMLDIR .'/');
        config('url_route_on',$config['rewrite']['route_status']);

        config('cache.expire', $config['app']['cache_time'] * 60);
        if($config['app']['cache_type'] ==0){
            config('cache.type','File');
        }
        else{
            config('cache.type', $config['app']['cache_type'] ==1 ? 'Memcache': 'Redis');
            config('cache.timeout',1000);
            config('cache.host',$config['app']['cache_host']);
            config('cache.port',$config['app']['cache_port']);
            config('cache.password',$config['app']['cache_password']);
        }
    }
}