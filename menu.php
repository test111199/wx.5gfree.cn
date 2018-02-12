<?php
    define(AppId,"wxfa2139f5f1354467");//定义AppId，需要在微信公众平台申请自定义菜单后会得到
 
    define(AppSecret,"f73319ba90fd50f0f68ea1a135db2dd5");//定义AppSecret，需要在微信公众平台申请自定义菜单后会得到
 
    include("libs/wechat.class.php");//引入微信类
 
    $wechatObj =new Wechat();//实例化微信类
    
    $creatMenu =$wechatObj->creatMenu();//创建菜单

?>