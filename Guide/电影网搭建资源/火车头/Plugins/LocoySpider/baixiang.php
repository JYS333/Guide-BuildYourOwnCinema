<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

if(!$LabelArray['面积'])
{
$LabelArray['面积']=mt_rand(50,120);
}
if(!$LabelArray['房龄'])
{
$LabelArray['房龄']=mt_rand(1,10);
}
echo serialize($LabelArray);
?>