<!DOCTYPE html>
<html>
  <head>
  	<title>2DP IoT USIM service</title>
  	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Manufactory Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
      function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- js -->
    <script src="js/jquery-1.11.1.min.js"></script>
<!-- //js -->
<!-- start-smoth-scrolling -->
    <script type="text/javascript" src="js/move-top.js"></script>
    <script type="text/javascript" src="js/easing.js"></script>
    <script type="text/javascript">
      jQuery(document).ready(function($) {
        $(".scroll").click(function(event){		
          event.preventDefault();
        $('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
        });
      });
    </script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat+Alternates:400,700" rel="stylesheet" type="text/css">
  </head>
  <body>
  	<div class="banner">
		<div class="container">
			<div class="header">
				<div class="banner-left"><!-- <a href="https://www.5gfree.cn/" class="logo"> -->
                    <img src="img/2DP_logo.png" height="30px">  <!--  </a>  -->
                </div>
            </div>
            <div class="banner-info">
                <div class="banner-info-left">
                    <h3>物联网卡信息查询结果</h3>    
                </div>
                
<?PHP
    error_reporting(E_ALL & ~E_NOTICE); 
    
    require_once  ("libs/mysql.inc.php");
    require_once  ("libs/curlFuctions.php");

/*    
    $fuctionID = $_POST['sndFuction_ID'];
    $fuctionID = 1;
    $userAccount = '13901161496';
    $simIccid = '89860617040009783269';    
*/
    $userAccount = $_POST['accMobile'];
    $simIccid = $_POST['chkIccid'];

//echo "<h3>".$userAccount. "+".$simIccid."</h3>";
    $fuctionSelect = 1;
// functionID 0:查询user是否存在，其提交ICCID是否存在，存在则查询流量
//   $simIccid = "143DF290720F";    
    
// echo "功能类型：".$fuctionID."-".$userAccount."-查询ICCID：".$simIccid;
 // res_domy_box_inst 表中，manage_state=1未开通  2开通 sale_status=0 未销售   
//sim_card_inst 表，manage_state 1待开卡 2 已开卡未使用 3 正在使用 4 已销卡
// $return_msg 0/1/2/3/4
    $return_msg = array("ok|0000",
                                    "错误码1-您还不是我们的用户，请联系客服人员！",
                                    "错误码2-您输入的物联网卡ICCID未查询到，请联系客服人员！",
                                    "错误码3-您输入的物联网卡ICCID存在问题，请联系客服人员！",
                                    "JapserData"                                 
                                    );
    
    $strsql = "SELECT userID,userOrgID,userLevel FROM IoT_User  WHERE userAccount = '$userAccount' ";
    list($userStatus,$userID,$userOrgID,$userLevel) = checkUser($strsql);
 
// echo "<h3>得到CheckUser结果 ".$userStatus."+".$userID."+".$userOrgID."+".$userLevel."</h3>";
    
    if($userStatus == '0'){
        echo "<h3>". $return_msg [1]."</h3>";
        mysql_close($myconn);
        exit;
    }

/*
    $strsql = "SELECT simStatus FROM IoT_USIM  WHERE simICCID LIKE '$simIccid%' ";
    list($simStatus,$simICCID,$simUserID,$simOrgID) = checkUsim($strsql);
    
    if($simStatus = 0){
        echo $return_msg [2];
        mysql_close($myconn);
        exit;
    }
    
    if($userLevel != 9){
        if($simUserID != $userID && $simOrgID != $userOrgID){
            echo $return_msg [3];
            mysql_close($myconn);
            exit;
        }
        else{
            
        }
    }
    else{
        $fuctionSelect = $fuctionID;  
    }
 
 */   
  
    switch ($fuctionSelect){
        case "1":
            $restURL = 'https://api.10646.cn/rws/api/v1/devices/'.$simIccid.'/ctdUsages';
            break;
        case "2":
            $restURL = 'https://api.10646.cn/rws/api/v1/devices/'.$simIccid;
            break;
        default:
            echo $return_msg [4];
            exit;
            break;
    }

    mysql_close($myconn);  
    
    $restData = apiCurlGet($restURL,$headerArray); 
//    $restData = json_decode($rest,true);
   
    $DataUsage = $restData['ctdDataUsage'];  
    $usedDataMB =  $DataUsage/1024/1024;
    echo "<h3>用户当前使用流量为：".number_format($usedDataMB,2)."MB</h3>";
//  var_dump(0);
//exit;
    
 // 查询数据库用户是否存在，如果存在返回userID和userOrgID；
    function checkUser($sqlStr)
    {
        $res=mysql_query($sqlStr); 
        if(mysql_num_rows($res)>0)
        {
            $data = mysql_fetch_array($res);
            $res_userID = $data['userID'];
            $res_userOrgID = $data['userOrgID'];
            $res_userLevel = $data['userLevel'];
            $res_status = 1;
        }else
        {
            $res_status = 0;
        }
//echo "进入用户检验程序，得到结果".$res_status."！";
        return array($res_status,$res_userID,$res_userOrgID,$res_userLevel);
    }

//查询用户输入ICCID在数据库中状态, 并返回ICCID状态和所属用户及组织
    function checkUsim($sqlStr)
    {
        $res=mysql_query($sqlStr); 
        if(mysql_num_rows($res)>0){
            $data = mysql_fetch_array($res);
            $res_simStatus = $data['simStatus'];
            $res_simUserID = $data['simUserID'];
            $res_simOrgID = $data['simOrgID'];
            $res_simICCID = $data['simICCID'];
            $res_status = 1;
        }else{
             $res_status = 0;
        }
        return array($res_status,$res_simICCID,$res_simUserID,$res_simOrgID);
    }
?>

                <div class="banner-info-right">
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <div class="container">
            <div class="footer-left">
			    <p>Copyright &copy; 2018. 2DP-IoT Project team  All rights reserved.</p>
			</div>
			<div class="footer-right">
			</div>
        </div>
    </div>
  </body>	
</html>    