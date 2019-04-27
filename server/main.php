   <?php
// 引入PHPMailer的核心文件
require_once("PHPMailer/class.phpmailer.php");
require_once("PHPMailer/class.smtp.php");

$ip = '';
$ipinfo = '';
//strcasecmp 比较两个字符，不区分大小写。返回0，>0，<0。
if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
    $ip = getenv('HTTP_CLIENT_IP');
} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
    $ip = getenv('HTTP_X_FORWARDED_FOR');
} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
    $ip = getenv('REMOTE_ADDR');
} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$ip =  preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
//dump(phpinfo());//所有PHP配置信息

if($ip != ''){
    $url = "http://ip.360.cn/IPQuery/ipquery?ip=".$ip;
    $data=json_decode(file_get_contents($url),true);
    $ipinfo =$data['data'];
}



// 实例化PHPMailer核心类
$mail = new PHPMailer();
// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
$mail->SMTPDebug = 1;
// 使用smtp鉴权方式发送邮件
$mail->isSMTP();
// smtp需要鉴权 这个必须是true
$mail->SMTPAuth = true;
// 链接qq域名邮箱的服务器地址
$mail->Host = 'smtp.qq.com';
// 设置使用ssl加密方式登录鉴权
$mail->SMTPSecure = 'ssl';
// 设置ssl连接smtp服务器的远程服务器端口号
$mail->Port = 465;
// 设置发送的邮件的编码
$mail->CharSet = 'UTF-8';
// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
$mail->FromName = '王志杰';
// smtp登录的账号 QQ邮箱即可
$mail->Username = '745748376@qq.com';
// smtp登录的密码 使用生成的授权码
$mail->Password = 'gxgazqvnyotpbdjd';
// 设置发件人邮箱地址 同登录账号
$mail->From = '745748376@qq.com';
// 邮件正文是否为html编码 注意此处是一个方法
$mail->isHTML(true);
// 设置收件人邮箱地址
$mail->addAddress('xinbangdaLog@163.com');
// 添加多个收件人 则多次调用方法即可
// 添加该邮件的主题
$mail->Subject = '【信邦达-客户询盘】';
// 添加邮件正文

$mailBody = '<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<title>信邦达</title>
</head>
<style>
	.info{
		width:100%;
		background-color:rgb(245,245,245);
		border-color:#fff; border-collapse: collapse;
	}
	.info td{
		padding: 1rem;
	}
</style>

<body style="background-color:#fff;color:#333;">
	<div style="width:90%;margin:0 auto;">
		<table style="width:100%;">
			<tr>
				<td>
						<img src="http://xbd.lmmqxyx.com/images/logo2.jpg" style="width:15rem;height:auto;float:left;">
				</td>
				<td>
						<h2 style="float:right;margin-top:4.5rem;margin-right:2rem;">客户反馈</h2>
				</td>
			</tr>
		</table>

		<table border="1" class="info">
			<tr>
				<td>
				<span style="color:#999;font-size: 12px;">客户信息</span>
				<br/>
				<span style="color:#333;font-size: 15px;">
					'.($_REQUEST['name']!=''?$_REQUEST['name']:'【未填写手机】').'<br/>
					'.($_REQUEST['email']!=''?$_REQUEST['email']:'【未填写邮箱】').'<br/>
					'.($_REQUEST['phone']!=''?$_REQUEST['phone']:'【未填写手机号】').'<br/>
				</span>
				<hr/>
				<span style="color:#333;font-size: 12px;">
					来源：'.$ipinfo.'
				</span>
				</td>
			</tr>
			<tr>
				<td>
					<span style="color:#999;font-size: 12px;">反馈内容</span>
					<br/>
					<span style="color:#333;font-size: 15px;">
					'.($_REQUEST['content']!=''?$_REQUEST['content']:'【未填写咨询信息】').'
					</span>
				</td>
			</tr>
		</table>
	</div>

</body>
</html>';


$mail->Body = $mailBody;//$_REQUEST['contact']."<br>".$_REQUEST['content'];

// 发送邮件 返回状态
$status = $mail->send();  
   ?>
