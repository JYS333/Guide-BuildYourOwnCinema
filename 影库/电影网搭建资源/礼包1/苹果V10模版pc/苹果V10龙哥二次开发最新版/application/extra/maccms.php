<?php
return array (
  'db' => 
  array (
    'type' => 'mysql',
    'path' => '',
    'server' => '127.0.0.1',
    'port' => '3306',
    'name' => 'maccms10',
    'user' => 'root',
    'pass' => 'root',
    'tablepre' => 'mac_',
    'backup_path' => './application/data/backup/database/',
    'part_size' => 10485760,
    'compress' => 1,
    'compress_level' => 4,
  ),
  'site' => 
  array (
    'site_name' => '免费电影网 - 苹果CMS V10',
    'site_url' => 'localhost',
    'site_wapurl' => 'www.1.cn',
    'site_keywords' => '最新最快免费电影',
    'site_description' => '国内最大的免费电影网站',
    'site_icp' => '备案XXXXXX号',
    'site_qq' => '12346',
    'site_email' => '123456@qq.com',
    'install_dir' => '/',
    'template_dir' => 'default_pc',
    'html_dir' => 'html',
    'mob_status' => '0',
    'mob_template_dir' => 'default_wap',
    'mob_html_dir' => 'html',
    'site_tj' => '',
    'site_status' => '1',
    'site_close_tip' => '网站维护中，请稍后访问。',
  ),
  'app' => 
  array (
    'cache_type' => '0',
    'cache_host' => '',
    'cache_port' => '',
    'cache_password' => '',
    'cache_core' => '0',
    'cache_page' => '0',
    'cache_time' => '30',
    'compress' => '0',
    'search' => '1',
    'search_timespan' => '3',
    'pagesize' => '20',
    'makesize' => '20',
    'suffix' => 'html',
    'search_hot' => '战狼,红海行动,复仇者联盟,变形金刚,奔跑吧,极限挑战',
    'art_extend_class' => '段子手,私房话,八卦精,爱生活,汽车迷,科技咖,美食家,辣妈帮',
    'vod_extend_class' => '爱情,动作,喜剧,战争,科幻,剧情,武侠,冒险,枪战,恐怖,微电影,其它',
    'vod_extend_state' => '正片,预告片,花絮',
    'vod_extend_version' => '高清版,剧场版,抢先版,OVA,TV,影院版',
    'vod_extend_area' => '大陆,香港,台湾,美国,韩国,日本,泰国,新加坡,马来西亚,印度,英国,法国,加拿大,西班牙,俄罗斯,其它',
    'vod_extend_lang' => '国语,英语,粤语,闽南语,韩语,日语,法语,德语,其它',
    'vod_extend_year' => '2018,2017,2016,2015,2014,2013,2012,2011,2010,2009,2008,2007,2006,2005,2004,2003,2002,2001,2000',
    'player_sort' => '1',
    'encrypt' => '0',
    'filter_words' => '',
  ),
  'user' => 
  array (
    'status' => '1',
    'reg_open' => '1',
    'reg_status' => '1',
    'reg_points' => '10',
    'invite_reg_points' => '10',
    'trysee' => '1',
    'portrait_status' => '0',
    'portrait_size' => '',
  ),
  'gbook' => 
  array (
    'status' => '1',
    'audit' => '0',
    'login' => '0',
    'verify' => '1',
    'pagesize' => '20',
    'timespan' => '3',
  ),
  'comment' => 
  array (
    'status' => '1',
    'audit' => '0',
    'login' => '0',
    'verify' => '1',
    'pagesize' => '20',
    'timespan' => '3',
  ),
  'upload' => 
  array (
    'thumb' => '0',
    'thumb_size' => '300x300',
    'thumb_type' => '1',
    'watermark' => '0',
    'watermark_location' => '7',
    'watermark_content' => 'maccms.com',
    'watermark_size' => '25',
    'watermark_color' => '#FF0000',
    'mode' => '0',
    'remoteurl' => 'http://pic.maccms.com/',
    'api' => 
    array (
      'upyun' => 
      array (
        'bucket' => '',
        'username' => '',
        'pwd' => '',
        'url' => '',
      ),
      'qiniu' => 
      array (
        'bucket' => '',
        'accesskey' => '',
        'secretkey' => '',
        'url' => '',
      ),
    ),
  ),
  'interface' => 
  array (
    'status' => '1',
    'pass' => 'QQ278640273',
    'vodtype' => '动作=动作片#爱情=爱情片#喜剧=喜剧片',
    'arttype' => '通知=站内公告#电影资讯=电影资讯',
  ),
  'pay' => 
  array (
    'min' => '10',
    'scale' => '1',
    'card' => 
    array (
      'url' => '',
    ),
    'alipay' => 
    array (
      'account' => '',
      'appid' => '',
      'appkey' => '',
    ),
    'weixin' => 
    array (
      'appid' => '',
      'mchid' => '',
      'appkey' => '',
    ),
    'codepay' => 
    array (
      'appid' => '',
      'appkey' => '',
      'type' => '',
      'act' => '',
    ),
  ),
  'collect' => 
  array (
    'vod' => 
    array (
      'hitsstart' => '1',
      'hitsend' => '1000',
      'status' => '1',
      'score' => '1',
      'pic' => '0',
      'tag' => '1',
      'psernd' => '1',
      'psesyn' => '1',
      'inrule' => ',b',
      'uprule' => ',a,b,n',
      'filter' => '色戒,色即是空',
      'thesaurus' => '11#11',
      'words' => '22#22',
    ),
    'art' => 
    array (
      'hitsstart' => '1',
      'hitsend' => '1000',
      'status' => '0',
      'score' => '0',
      'pic' => '0',
      'tag' => '0',
      'psernd' => '0',
      'psesyn' => '0',
      'uprule' => ',a',
      'filter' => '无奈的人',
      'thesaurus' => '33#33',
      'words' => '44#44',
      'inrule' => ',a',
    ),
  ),
  'api' => 
  array (
    'vod' => 
    array (
      'status' => '0',
      'charge' => '0',
      'pagesize' => '20',
      'imgurl' => 'http://img.maccms.com/',
      'typefilter' => 'and type_status=1',
      'datafilter' => '',
      'from' => '',
      'auth' => 'maccms.com#163.com',
    ),
    'art' => 
    array (
      'status' => '0',
      'charge' => '0',
      'pagesize' => '20',
      'imgurl' => 'http://img2.maccms.com/',
      'typefilter' => 'and type_status=1',
      'datafilter' => '',
      'auth' => 'qq.com#baidu.com',
    ),
  ),
  'connect' => 
  array (
    'qq' => 
    array (
      'status' => '0',
      'id' => '111',
      'key' => '222',
    ),
  ),
  'weixin' => 
  array (
    'status' => '0',
    'duijie' => 'wx.maccms.com',
    'sousuo' => 'wx.maccms.com',
    'token' => 'qweqwe',
    'guanzhu' => '欢迎关注',
    'wuziyuan' => '没找到资源，请更换关键词或等待更新',
    'wuziyuanlink' => 'demo.maccms.com',
    'bofang' => '1',
    'gjc1' => '关键词1',
    'gjcm1' => '长城',
    'gjci1' => 'http://img.aolusb.com/im/201610/2016101222371965996.jpg',
    'gjcl1' => 'http://www.loldytt.com/Dongzuodianying/CC/',
    'gjc2' => '关键词2',
    'gjcm2' => '生化危机6',
    'gjci2' => 'http://img.aolusb.com/im/201702/20172711214866248.jpg',
    'gjcl2' => 'http://www.loldytt.com/Kehuandianying/SHWJ6ZZ/',
    'gjc3' => '关键词3',
    'gjcm3' => '湄公河行动',
    'gjci3' => 'http://img.aolusb.com/im/201608/201681719561972362.jpg',
    'gjcl3' => 'http://www.loldytt.com/Dongzuodianying/GHXD/',
    'gjc4' => '关键词4',
    'gjcm4' => '王牌逗王牌',
    'gjci4' => 'http://img.aolusb.com/im/201601/201612723554344882.jpg',
    'gjcl4' => 'http://www.loldytt.com/Xijudianying/WPDWP/',
  ),
  'view' => 
  array (
    'index' => '0',
    'map' => '0',
    'search' => '0',
    'rss' => '0',
    'label' => '0',
    'vod_type' => '0',
    'vod_show' => '0',
    'art_type' => '0',
    'art_show' => '0',
    'topic_index' => '0',
    'topic_detail' => '0',
    'vod_detail' => '0',
    'vod_play' => '0',
    'vod_down' => '0',
    'art_detail' => '0',
  ),
  'path' => 
  array (
    'topic_index' => 'topic/index',
    'topic_detail' => 'topic/{id}/index',
    'vod_type' => 'vodtypehtml/{id}/index',
    'vod_detail' => '{type_pen}/{type_en}/{en}/detail',
    'vod_play' => 'vodplayhtml/{id}/index',
    'vod_down' => 'voddownhtml/{id}/index',
    'art_type' => 'arttypehtml/{id}/index',
    'art_detail' => 'arthtml/{id}/index',
    'page_sp' => '-',
    'suffix' => 'html',
  ),
  'rewrite' => 
  array (
    'route_status' => '0',
    'status' => '0',
    'vod_id' => '0',
    'art_id' => '0',
    'type_id' => '0',
    'topic_id' => '0',
    'route' => 'map   => map/index
rss   => rss/index
gbook/index   => gbook/index
gbook/index/page/[:page]   => gbook/index
topic/index   => topic/index
topic/index/page/[:page]   => topic/index
topic/detail/id/:id   => topic/detail
vod/type/id/:id   => vod/type
vod/show/id/:id   => vod/show
vod/detail/id/:id   => vod/detail
vod/rss/id/:id   => vod/rss
vod/play/id/:id/sid/:sid/nid/:nid   => vod/play
vod/down/id/:id/sid/:sid/nid/:nid   => vod/down

vod/search/wd/:wd => vod/search
vod/search/tid/:tid => vod/search
vod/search/level/:level => vod/search
vod/search/year/:year => vod/search
vod/search/area/:area => vod/search
vod/search/lang/:lang => vod/search
vod/search/letter/:letter => vod/search
vod/search/actor/:actor => vod/search
vod/search/director/:director => vod/search
vod/search/tag/:tag => vod/search
vod/search/class/:class => vod/search
vod/search/state/:state => vod/search
vod/search/actor/:actor/area/:area/by/:by/class/:class/director/:director/lang/:lang/letter/:letter/level/:level/order/:order/state/:state/tag/:tag/wd/:wd/year/:year   => vod/search
vod/search => vod/search 

art/type/id/:id   => art/type
art/show/id/:id   => art/show
art/detail/id/:id   => art/detail
art/detail/id/:id/page/[:page]   => art/detail
art/rss/id/:id   => art/rss
art/rss/id/:id/page/[:page]   => art/rss
art/search/wd/:wd => art/search
art/search/tid/:tid => art/search
art/search/level/:level => art/search
art/search/letter/:letter => art/search
art/search/tag/:tag => art/search
art/search/class/:class => art/search
art/search/by/:by/class/:class/level/:level/letter/:letter/order/:order/tag/:tag/wd/:wd   => art/search
art/search => art/search',
  ),
  'email' => 
  array (
    'host' => 'aa',
    'port' => '25',
    'username' => 'bb',
    'password' => 'cc',
    'test' => 'dd',
  ),
  'play' => 
  array (
    'width' => '0',
    'height' => '660',
    'widthmob' => '0',
    'heightmob' => '660',
    'widthpop' => '0',
    'heightpop' => '600',
    'second' => '5',
    'prestrain' => 'http://union.maccms.net/html/prestrain.html',
    'buffer' => 'http://union.maccms.net/html/buffer.html',
    'parse' => 'http://www.rzhyi.com/?url=',
    'autofull' => '0',
    'showtop' => '1',
    'showlist' => '1',
    'flag' => '0',
    'colors' => '000000,F6F6F6,F6F6F6,333333,666666,FFFFF,FF0000,2c2c2c,ffffff,a3a3a3,2c2c2c,adadad,adadad,48486c,fcfcfc',
  ),
);
?>