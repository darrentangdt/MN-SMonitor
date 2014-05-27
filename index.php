<?php
/**
 * index.php
 *
 * 多节点网站在线监控
 *
 * @author     Kslr
 * @copyright  2014 kslrwang@gmail.com
 * @version    0.3
 */

#监控网站
$siteurl = 'https://www.bt-ss.com';

#接受通知者电话号码
$phone = '';

#VOIP
$conf['app_id'] = '';
$conf['soft_version'] = '2013-12-26';
$conf['main_account'] = '';
$conf['main_token'] = '';
$conf['address'] = 'app.cloopen.com:8883';

#请求节点
$nodelist = array(
	'',
);

$status = 0;
foreach ($nodelist as $v) {
	$ch = curl_init($v.'?url='.$siteurl);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$cn = curl_exec($ch);
	if ($cn == FALSE) {
		$status++;
	}
}

if ($status == 0) {
	Send($phone);
} else {
	logs('失败节点数'.$status.';成功节点'.count($nodelist) - $status);
}

function Send($tel)
{

	global $conf;
	$data = "{'appId':'" .$conf['app_id']. "', 'to':'".$tel."', 'mediaName':'1.wav','displayNum':'".$tel."', 'playTimes':'3'}";
	$sig =  strtoupper(md5($conf['main_account'] . $conf['main_token'] . date("YmdHis")));
	$url = 'https://'. $conf['address'] .'/'. $conf['soft_version'] .'/Accounts/'. $conf['main_account']. '/Calls/LandingCalls?sig='.$sig;
	$authen = base64_encode($conf['main_account'] . ":" . date("YmdHis"));
	$header = array("Accept:application/json","Content-Type:application/json;charset=utf-8","Authorization:$authen");

	$ch = curl_init(); 
	$res= curl_setopt($ch, CURLOPT_URL, $url);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_HTTPHEADER, $header);
	$result = curl_exec ($ch);
	if($result == FALSE){
		print curl_error($ch);
	}
	curl_close($ch);
	return $result;

}

function logs($msg)
{
	file_put_contents('log.txt', date("Y-m-d").'-'.$msg."\r\n");
}