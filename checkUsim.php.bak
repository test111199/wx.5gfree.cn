<?PHP
    error_reporting(E_ALL & ~E_NOTICE); 
    
    require_once  ("libs/mysql.inc.php");
    require_once  ("libs/curlFuctions.php");

/*    
    $fuctionID = $_POST['sndFuction_ID'];
    $userAccount = $_POST['sndUser_Act'];
    $simIccid = $_POST['sndSim_Iccid'];
*/

    $fuctionID = 1;
    $userAccount = '13901161496';
    $simIccid = '89860617040009783269';
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
    
    if($userStatus = 0){
        echo $return_msg [1];
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
    echo '用户当前使用流量为：' .$DataUsage;
//  var_dump(0);
//exit;
    
 // 查询数据库用户是否存在，如果存在返回userID和userOrgID；
    function checkUser($sqlStr)
    {
        $res=mysql_query($sqlStr); 
        if(mysql_num_rows($res)>0){
            $data = mysql_fetch_array($res);
            $res_userID = $data['userID'];
            $res_userOrgID = $data['userOrgID'];
            $res_userLevel = $data['userLevel'];
            $res_status = 1;
        }else{
            $res_status = 0;
        }
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