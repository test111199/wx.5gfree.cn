<?PHP

//    echo "This is a test</br>"; 
    $mysql_server_name="115.32.41.98"; //数据库服务器名称
    $mysql_port=6033;
    $mysql_username="root"; // 连接数据库用户名
    $mysql_password="2DP2IoT"; // 连接数据库密码
    $mysql_database="2DPIot"; // 数据库的名字
    
    // 连接到数据库
    $myconn=mysql_connect("$mysql_server_name:$mysql_port", $mysql_username,$mysql_password);
    if (!$myconn)
    {
        die('Could not connect: ' . mysql_error());
    }
    
    mysql_select_db($mysql_database, $myconn);
    
?>
