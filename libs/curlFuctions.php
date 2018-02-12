<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文//
//echo "加载curl公共函数！<br>";

  $headerArray = array(
                       "Content-type:application/json;",
                       "Accept:application/json",
                       "Authorization: Basic eGlhb3poaWdhbmc6N2IzOTdiNDQtNzZiYy00MjU5LWJhOTYtMDI1MzFhODQ3N2Ni");

function apiCurlGet($url,$header = array())
       {
// echo "显示传入url:  ".$url." <br>";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
//                  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//                  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//print_r($header);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
// echo '准备调用联通接口：<br>';
        $output = curl_exec($curl);
// $info = curl_getinfo($curl);
//        $request_header = curl_getinfo( $curl, CURLINFO_HEADER_OUT);
// print_r($request_header);
// echo "<br>如上为头文件传输内容!<br>";
// echo '调用联通端口返回数据json格式：<br>';
// var_dump($output);  
                curl_close($curl);
                $output = json_decode($output,true);

// echo '调用联通端口返回数据array格式：<br>';
// var_dump($output);  
                  return $output;
          }


function apiCurlPost($url,$data,$header = array()){
                  $data  = json_encode($data);
// echo "这里是apiCurlPost Function，显示json后的的传输数组data: ".$data."<br>";
                  $curl = curl_init();
                  curl_setopt($curl, CURLOPT_URL, $url);
//                  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
//                  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
                  curl_setopt($curl, CURLOPT_POST, 1);
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                  curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                  $output = curl_exec($curl);
$request_header = curl_getinfo( $curl, CURLINFO_HEADER_OUT);
// print_r($request_header);
// echo "<br>如上为头文件传输内容!<br>";

//                  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                  curl_close($curl);
                  return json_decode($output，true);
         }


 function apiCurlPut($url,$data,$header = array()){
                  $data = json_encode($data);
// echo "这里是apiCurlPut Function，显示json后的的传输数组data: ".$data."<br>";
                  $curl = curl_init(); //初始化CURL句柄
                  curl_setopt($curl, CURLOPT_URL, $url); //设置请求的URL
                  curl_setopt ($curl, CURLOPT_HTTPHEADER, $header);
//                  curl_setopt($curl, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
                  curl_setopt($curl, CURLOPT_CUSTOMREQUEST,"PUT"); //设置请求方式
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
curl_setopt($curl, CURLINFO_HEADER_OUT, true);
                  $output = curl_exec($curl);
echo "<br>";
$request_header = curl_getinfo( $curl, CURLINFO_HEADER_OUT);
print_r($request_header);
$info = curl_getinfo($curl);
echo "<br>如上为头文件传输内容!<br>";
print_r($info);
                  curl_close($curl);
                  return json_decode($output,true);
          }

function curlApiDel($url,$data,$header = array()){
                  $data  = json_encode($data);
                  $curl = curl_init();
                  curl_setopt ($curl,CURLOPT_URL,$url);
                  curl_setopt ($curl, CURLOPT_HTTPHEADER, $header);
                  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
                  curl_setopt ($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                  curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
                  $output = curl_exec($curl);
                  curl_close($curl);
                  $output = json_decode($output,true);
          }

?>