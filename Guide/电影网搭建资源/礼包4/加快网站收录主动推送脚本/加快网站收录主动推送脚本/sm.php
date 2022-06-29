<?php
$str = $_POST['url'];
$urls = explode(" ",$str);
$api = 'http://data.zz.baidu.com/urls?site=www.winseo.cc&token=EbDE9SF2LPwqaiQR';
$ch = curl_init();
$options =  array(
    CURLOPT_URL => $api,
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => implode("\n", $urls),
    CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
);
curl_setopt_array($ch, $options);
$result = curl_exec($ch);
echo $result;
?>