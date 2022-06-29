<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:60:"D:\PHPTutorial\WWW/application/admin\view\index\welcome.html";i:1525274147;s:58:"D:\PHPTutorial\WWW\application\admin\view\public\head.html";i:1522628860;s:58:"D:\PHPTutorial\WWW\application\admin\view\public\foot.html";i:1522628860;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $title; ?> - 苹果CMS</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
    <link rel="stylesheet" href="/static/css/admin_style.css">
    <script type="text/javascript" src="/static/js/jquery.js"></script>
    <script type="text/javascript" src="/static/layui/layui.js"></script>
    <script>
        var ROOT_PATH="",ADMIN_PATH="<?php echo $_SERVER['SCRIPT_NAME']; ?>", MAC_VERSION='v10';
    </script>
</head>
<body>
<div class="page-container">
    <?php $pass="<strong class='green'>√</strong>";$error="<strong class='red'>×</strong>";?>

    <blockquote class="layui-elem-quote layui-quote-nm mt10">
        <p class="f-20 text-success">欢迎使用苹果CMS龙哥二次开发，一路陪伴，感恩有你！请不要修改系统文件，以免升级出现故障！</p>
        <p>登录次数：<?php echo $info['admin_login_num']; ?>  上次登录IP：<?php echo long2ip($info['admin_last_login_ip']); ?>  上次登录时间：<?php echo mac_day($info['admin_last_login_time']); ?></p>
    </blockquote>

    <table class="layui-table" >
        <thead>
        <tr>
            <th colspan="4" scope="col">服务器信息</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td width="15%">操作系统</td>
            <td width="30%"><?php echo PHP_OS ?></td>
            <td width="15%">脚本解释引擎</td>
            <td width="30%"><?php echo $_SERVER['SERVER_SOFTWARE'] ?></td>
        </tr>
        <tr>
            <td>安装目录</td>
            <td><?php echo $_SERVER['DOCUMENT_ROOT'] ?></td>
            <td>服务器 (IP/端口) </td>
            <td><?php echo $_SERVER['SERVER_NAME'].' ('.$_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'].')' ?></td>
        </tr>
        <tr>
            <td>PHP版本 </td>
            <td><?php echo PHP_VERSION ?></td>
            <td>CURL支持 </td>
            <td><?php $curl = @curl_version();echo $curl['version'] ? $curl['version'] : $error; ?></td>
        </tr>
        <tr>
            <td>允许上传文件最大值 </td>
            <td><?php echo get_cfg_var("file_uploads") ? get_cfg_var("upload_max_filesize") : $error;?></td>
            <td>GD图形处理扩展库版本 </td>
            <td><?php $gd = @gd_info(); echo $gd['GD Version'] ? $gd['GD Version'] : $error;?></td>
        </tr>
        <tr>
            <td>服务器脚本超时时间 </td>
            <td><?php $t = ini_get("max_execution_time"); echo $t;?>秒</td>
            <td>服务器当前日期 </td>
            <td><?php echo date('Y-m-d'); ?></td>
        </tr>
        <tr>
            <td colspan="4">当前版本：<span class="layui-badge"><?php echo $version['code']; ?></span> 授权类型：<span class="layui-badge">龙哥二次开版本</span> 全网免费解析接口：<span class="layui-badge">http://www.rzhyi.com/?url=</span>QQ交流群：<span class="layui-badge">295107929 </span>龙哥QQ<span class="layui-badge">278640273</span>官方网站地址：<span class="layui-badge">http://v.rzhyi.com</span>采集规则代写：<span class="layui-badge">请找龙哥QQ278640273</td>
        </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript" src="/static/js/admin_common.js"></script>





</body>
</html>