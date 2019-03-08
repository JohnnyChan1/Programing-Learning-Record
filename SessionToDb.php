//封装 session_open函数  连接数据库
function _session_open()
{
    global $con;
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1995";
    $database = "php";
    $con = new mysqli($servername, $username, $password);
    if ($con->connect_error) {
        die("连接失败" . $con->connect_error);
        return (false);
    }
    echo '连接数据库成功<p>';
    if ($con->select_db($database) == false) {
        die("数据库中没有此库名");
        return (false);
    }
    return (true);
}
 
//封装session_close()函数,关闭数据库连接
function _session_close()
{
    global $con;
    $con->close();
    return (true);
}
 
//封装session_read()函数,该函数只能返回字符串
function _session_read($key)
{
    global $con;
    $sql = "select session_key,session_data from  session  where session_key='$key'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = mysqli_fetch_object($result);
        $row = json_encode($row);
        return $row;
    } else {
        return "";
    }
}
 
//根据sessionId判断数据存在,存在修改,否则修改,并且设置超时时间为1分钟
function _session_write($key, $data)
{
    global $con;
    $timeout = time() + 60;
    $sql = "select session_data from session where session_key='$key'";
    $result = $con->query($sql);
    if ($result->num_rows == 0) {
        $sql = "insert into session value('$key','$data','$timeout')";
    } else {
        $sql = "update session set session_data='$data' , timeout='$timeout' where session_key='$key'";
    }
    $con->query($sql);
    return (true);
}
 
//封装session_destroy()函数,根据$key 值将数据库中的session删除
function _session_destroy($key)
{
    global $con;
    $sql = "delete from session where session_key='$key'";
    $con->query($sql);
    return (true);
}
 
//封装session_gc()函数,根据给出的实效时间删除过期Session
function _session_gc()
{
    global $con;
    $timeout = time();
    $sql = "delete from session where  timeout<'$timeout'";
    $con->query($sql);
    return (true);
}
 
//设置session存储到数据库中
session_set_save_handler('_session_open', '_session_close', '_session_read', '_session_write', '_session_destroy', '_session_gc');
session_start();
$_SESSION['user'] = 'ming';
$_SESSION['age'] = 18;