<?php
/**
 * index.php
 *
 * 请求节点
 *
 * @author     Kslr
 * @copyright  2014 kslrwang@gmail.com
 * @version    0.2
 */

if (isset($_GET['url'])) {
	if (filter_var($_GET['url'], FILTER_VALIDATE_URL)) {
		$context = stream_context_create(array('http' => array('method' => 'GET','timeout' => '10')));
		file_get_contents($_GET['url'], FALSE, $context);
		$i = explode(' ', $http_response_header[0]);
		if ($i['1'] == 200) {
			echo 'TRUE';
		} else {
			echo 'FALSE';
		}
	}
}