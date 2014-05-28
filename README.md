# MN-SMonitor
多节点网站监控
(Multi-node site monitoring)

####为什么我要做这个东西
现在的免费监控（例：监控宝）免费账户限制太大，最短只能30分钟监测，并且因为短信对我不是很敏感，我喜欢直接打过来电话告诉我，短信会很容易错过掉。
而一些大型的监控又有些杀鸡焉用牛刀的感觉，我只需要HTTP监控即可，所以这个项目就诞生了。

####逻辑
通过外部定时器触发运行，运行后开始收集监测节点和被监测网站连接情况，当所有监测节点报告被监测网站不可访问时，则拨打电话通知，语音内容为“警告，服务器已停止工作，请立刻检查。”，将会重复三次，然后挂机。

####需要准备的
容联云通讯账户一枚
至少两个虚拟主机（主控一个，被控一个）

####为什么需要节点
节点的存在是为了避免单一网络故障造成的误报，多节点通过全球各个位置（你需要放一个cli.php文件，免费主机就可以）进行判断。

## 如何开始
注册容联云通讯账户一枚，进入管理控制台：
![image](http://kslr.qiniudn.com/2014052811013.png)

在index.php文件中把这两个填上
```php
$conf['main_account'] = '';
$conf['main_token'] = '';
```
    
接下来在控制台左侧切换到应用列表->点击应用名(不是编辑按钮)
这里会有一个APP ID，填到index文件里面
```php
$conf['app_id'] = '';
```

然后在上面选项卡中->语音库管理->上传新语音。
[示例WAW文件下载](http://kslr.qiniudn.com/1.wav)，文件名必须为1.wav

###配置节点
```php
$nodelist = array(
	'http://www.xxx.com/jk/',
	'http://22.xx.com/',
);
```
上面的是节点位置，这个链接格式没有限制，能够访问就ok，搞很多虚拟主机神马的，就可以部署很多节点。
默认监控就会请求www.xxx.com/jk/cli.php和22.xx.com/cli.php的文件。
注意：网址最后加上/杠；节点请求超时时间为15秒。

接下来让index.php按照你的监控频率运行即可。


2014 年 05月 27日  
