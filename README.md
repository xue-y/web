## 微信公众号开发
 |  [官方文档](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1445241432)
### 目录结构
	old/			微信公众号菜单开发测试
	conf.php                配置文件
	curl.php url            请求函数
	phpqrcode.php           php生成二维码
	wx_token.php            微信服务器验证网站文件
	wx_access_token.php     获取开发者的access_token
	wx_img.php              通过开发者的access_token 生成公众号二维码，用户扫码关注公众号
	web_login.php           浏览器输出二维码，手机扫码跳转微信端
	wx_code.php             用户从微信客户端打开页面获取用户是否授权,必须微信端打开
	wx_callback.php         用户授权后跳转页面获取用户信息
	phpqrcode.zip	官网下载二维码压缩包

### 目录要求
	生成二维码路径必须有创建写入文件权限
	

