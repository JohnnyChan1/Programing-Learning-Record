//��װ session_open����  �������ݿ�
function _session_open()
{
    global $con;
    $servername = "127.0.0.1";
    $username = "root";
    $password = "1995";
    $database = "php";
    $con = new mysqli($servername, $username, $password);
    if ($con->connect_error) {
        die("����ʧ��" . $con->connect_error);
        return (false);
    }
    echo '�������ݿ�ɹ�<p>';
    if ($con->select_db($database) == false) {
        die("���ݿ���û�д˿���");
        return (false);
    }
    return (true);
}
 
//��װsession_close()����,�ر����ݿ�����
function _session_close()
{
    global $con;
    $con->close();
    return (true);
}
 
//��װsession_read()����,�ú���ֻ�ܷ����ַ���
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
 
//����sessionId�ж����ݴ���,�����޸�,�����޸�,�������ó�ʱʱ��Ϊ1����
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
 
//��װsession_destroy()����,����$key ֵ�����ݿ��е�sessionɾ��
function _session_destroy($key)
{
    global $con;
    $sql = "delete from session where session_key='$key'";
    $con->query($sql);
    return (true);
}
 
//��װsession_gc()����,���ݸ�����ʵЧʱ��ɾ������Session
function _session_gc()
{
    global $con;
    $timeout = time();
    $sql = "delete from session where  timeout<'$timeout'";
    $con->query($sql);
    return (true);
}
 
//����session�洢�����ݿ���
session_set_save_handler('_session_open', '_session_close', '_session_read', '_session_write', '_session_destroy', '_session_gc');
session_start();
$_SESSION['user'] = 'ming';
$_SESSION['age'] = 18;