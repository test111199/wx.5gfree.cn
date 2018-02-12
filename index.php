<?php
define("TOKEN", "2dp_weixin");
require_once("libs/init.php");

$fp = fopen("/tmp/5gfree.cn/wx_post.txt", "a+");
fputs($fp, "录入信息时间：$dateTime \n");

if($_GET["echostr"] != "")
{
    $wechatObj = new wechatCallbackapiTest();
    $wechatObj->valid();
fputs($fp, "建立微信链接失败 \n");    

}
else
{
    $dateStr = date(Ymd);
    $dateTime = date ("Ymd H:i:s")
    $createtime = time();
    $post_data = file_get_contents("php://input");
    $wx_arr = xmltoarray($post_data);
    
    $msgtype = trimstr($wx_arr['MsgType']);
    $event = trimstr($wx_arr['Event']);
    $eventkey = trimstr($wx_arr['EventKey']);
    $openid = trimstr($wx_arr['FromUserName']);
    $content = trimstr($wx_arr['Content']);

//添加微信访问记录    

fputs($fp, $post_data);
fputs($fp, "MsgType: $msgtype \n");
fputs($fp, "Event: $event\n");
fputs($fp, "EventKey: $eventkey\n");
fputs($fp, "FromUserName: $openid\n");
fputs($fp, "Content: $content\n");


echo $event."<br>\n";

    if($event == "subscribe")
    {
/*
        $ek = trimstr($wx_arr['EventKey']);
        $parentid = substr($ek, 8);
        $createtime = $wx_arr['CreateTime'];
        $ucnt = $db->getOne("SELECT COUNT(*) FROM weixin_user WHERE openid = '$openid'");
        if($ucnt == 0)
        {
            $db->query("INSERT INTO weixin_user VALUES (NULL, '$openid', '', '', '$parentid','$createtime')");
        }
*/
        $data = "<xml>
            <ToUserName><![CDATA[$openid]]></ToUserName>
            <FromUserName><![CDATA[$weixinid]]></FromUserName>
            <CreateTime>$createtime</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[欢迎进入鹏博士集客物联网世界！]]></Content>
            </xml>";
            
fputs($fp, "用户关注微信账号 \n");    

        echo $data;
    }
/*
    elseif($event == "unsubscribe")
    {
        //$db->query("DELETE FROM weixin_user WHERE openid = '$openid'");
    }
    elseif($event == "kf_create_session")
    {
    } 
*/
    elseif($event == "CLICK")
    {
        if($eventkey == "CLICK_SERVICE")
        {
            $data = "<xml>
                    <ToUserName><![CDATA[$openid]]></ToUserName>
                    <FromUserName><![CDATA[$weixinid]]></FromUserName>
                    <CreateTime>$createtime</CreateTime>
                    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                    </xml>";
fputs($fp, "用户点击服务按键 \n");      
               
            echo $data;
        }        
/*
        if($eventkey == "CLICK_FRIEND")
        {
            $ctime = time();
            $picurl = "http://www.gwbn.net.cn/data/afficheimg/1405539296740913587.jpg";
//            $picurl = urlencode($picurl);
            $data = "<xml>
		<ToUserName><![CDATA[$openid]]></ToUserName>
		<FromUserName><![CDATA[$weixinid]]></FromUserName>
		<CreateTime>$ctime</CreateTime>
		<MsgType><![CDATA[news]]></MsgType>
		<ArticleCount>1</ArticleCount>
		<Articles>
		<item>
		<Title><![CDATA[收益分析，暂时没有数据]]></Title> 
		<Description><![CDATA[暂时没有数据]]></Description>
		<PicUrl><![CDATA[$picurl]]></PicUrl>
		<Url><![CDATA[$picurl]]></Url>
		</item>
		</Articles>
		</xml>";
            echo $data;
        }
*/
    }
    else /* 用户发消息 */
    {
        if($content == "你好")
        {
//            $db->query("DELETE FROM service_user WHERE openid = '$openid'");
//            $db->query("INSERT INTO service_user VALUES(NULL, '$openid', '$createtime', 0)");
            $data ="<xml>
                    <ToUserName><![CDATA[$openid]]></ToUserName>
                    <FromUserName><![CDATA[$weixinid]]></FromUserName>
                    <CreateTime>$createtime</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[您好，欢迎您使用鹏博士集客物联网查询瓶平台： \n<a href='http://1.gwmk.sinaapp.com/qustion1-1.html'>。$openid - $content
 ]]></Content>
                    </xml>";
fputs($fp, "用户发送指定信息 \n");                     
            echo $data;           
        }
        else
        {
            $data = "<xml>
            <ToUserName><![CDATA[$openid]]></ToUserName>
            <FromUserName><![CDATA[$weixinid]]></FromUserName>
            <CreateTime>$createtime</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[非常欢迎您使用我们的平台]]></Content>
            </xml>";
fputs($fp, "用户发送任意信息 \n");   
            echo $data;
        }
        
    }
/*
    $fp = fopen("/tmp/wx.log", "a+");
    fputs($fp, $post_data."\n");
    fclose($fp);
*/
}
fclose($fp);

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
			</xml>";             
		if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$contentStr = "Welcome to wechat world!";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>