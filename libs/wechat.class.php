<?php

    class Wechat
    {
        private function getAccessToken() //获取access_token
        {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".AppId."&secret=".AppSecret;
            $data = getCurl($url);//通过自定义函数getCurl得到https的内容
            $resultArr =json_decode($data,true);//转为数组
            return $resultArr["access_token"];//获取access_token
        }
 
        public function creatMenu()//创建菜单
        {
            $accessToken = $this->getAccessToken();//获取access_token
            $menuPostString = 
            '{//构造POST给微信服务器的菜单结构体
                "button":[
                    {"type":"view", "name":"查流量", "key":"V1000"},
                    {"type":"click", "name":"找客服", "key":"V2000"}
                ]
            }';
/*                "button":[
                {"name":"常用",
                    "sub_button":[
                        {"type":"click", "name":"每日考勤", "key":"1100"},
                        {"type":"click", "name":"领卡申请", "key":"3100"},
                        {"type":"click","name":"短信申请", "key":"3200"},
                        {"type":"click", "name":"商户曝光", "key":"2100"},
                        {"type":"click", "name":"商户质检", "key":"2200"}
                    ]
                },
                {"name":"我的",
                    "sub_button":[
                        {"type":"click", "name":"我的考勤", "key":"1101"},
                        {"type":"click", "name":"我的曝光", "key":"2101"},
                        {"type":"click", "name":"我的质检", "key":"2201"},
                        {"type":"click", "name":"我的锁定", "key":"2001"}
                    ]
                },
                {"name":"数据",
                    "sub_button":[
                        {"type":"click", "name":"消费数据", "key":"6101"},
                        {"type":"click", "name":"激活数据", "key":"6102"},
                        {"type":"click", "name":"POS手册", "key":"4100"},
                        {"type":"click", "name":"微信指令", "key":"0009"}
                    ]
                }
                ]
标准3 x 5菜单结构
*/             
        $menuPostUrl ="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;//POST的url
        $menu =dataPost($menuPostString, $menuPostUrl);//将菜单结构体POST给微信服务器
        }
    }
 
    function getCurl($url)
    {//get https的内容
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);//不输出内容
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
 
    function dataPost($post_string, $url)
    {//POST方式提交数据
        $context = array (
            'http' =>array (
                                    'method' =>"POST",
                                    'header' =>"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*",
                                    'content' => $post_string 
                                    )
        );
        $stream_context = stream_context_create ( $context );
        $data = file_get_contents ($url, FALSE, $stream_context );
        return $data;
    }
?>