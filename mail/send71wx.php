<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');

/**
+----------------------------------------------------------
 * 功能：系统邮件发送函数
+----------------------------------------------------------
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
+----------------------------------------------------------
 * @return boolean
+----------------------------------------------------------
 */
function send_mail($to, $name, $subject = '', $body = '', $attachment = null, $config = '') {
    $config = is_array($config) ? $config : array();
    require_once('PHPMailer/phpmailer.class.php');
    $mail = new PHPMailer();                           //PHPMailer对象
    $mail->CharSet = 'UTF-8';                         //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                                   // 设定使用SMTP服务
    $mail->IsHTML(true);
    $mail->SMTPDebug = 0;                             // 关闭SMTP调试功能 1 = errors and messages2 = messages only
    $mail->SMTPAuth = true;                           // 启用 SMTP 验证功能
    if ($config['smtp_port'] == 465)
        $mail->SMTPSecure = 'ssl';                    // 使用安全协议
    $mail->Host = $config['smtp_host'];                // SMTP 服务器
    $mail->Port = $config['smtp_port'];                // SMTP服务器的端口号
    $mail->Username = $config['smtp_user'];           // SMTP服务器用户名
    $mail->Password = $config['smtp_pass'];           // SMTP服务器密码
    $mail->SetFrom($config['from_email'], $config['from_name']);
    $replyEmail = $config['reply_email'] ? $config['reply_email'] : $config['reply_email'];
    $replyName = $config['reply_name'] ? $config['reply_name'] : $config['reply_name'];
    $mail->addBCC($replyEmail, $replyName);
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
//    if (is_array($attachment)) { // 添加附件
//        foreach ($attachment as $file) {
//            if (is_array($file)) {
//                is_file($file['path']) && $mail->AddAttachment($file['path'], $file['name']);
//            } else {
//                is_file($file) && $mail->AddAttachment($file);
//            }
//        }
//    } else {
//        is_file($attachment) && $mail->AddAttachment($attachment);
//    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

$config = array();
$config['smtp_port']   = '25';
$config['smtp_host']   = 'smtp.ym.163.com';
$config['from_email']  = '71wx@mail85.com';//$_GET['userEmail'];
$config['from_name']   = '微信小程序';//$_GET['userName'];
$config['smtp_user']   = '71wx@mail85.com';
$config['smtp_pass']   = 'xy740102';
$config['reply_email'] = '71wx@mail85.com';//$_GET['userEmail'];
$config['reply_name']  = '微信小程序';//$_GET['userName'];
$config['email_to']    = $_POST['userEmail'];

//$attachment = 'js/phpmailer.js'; //一个附件

//$attachment = array('js/phpmailer.js','js/popover.js');  //多个附件

$rs = send_mail(
    $config['email_to'],
    $_POST['userName'],
    $_POST['userTitle'],
    $_POST['userMsg'],
    '',
    $config
    );

if ($rs) {
    echo json_encode(array('code' => 'ok', 'msg' => 'success'));
//  echo "sendEmailHandler(".json_encode(array('code' => 'ok', 'msg' => 'success')).")";
} else {
    echo json_encode(array('code' => 'no', 'msg' => 'error'));
//  echo "sendEmailHandler(".json_encode(array('code' => 'no', 'msg' => 'error')).")";
}
