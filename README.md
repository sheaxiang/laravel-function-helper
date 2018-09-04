# laravel扩展包,一些常用的帮助函数

## 函数列表

### is_weixin:判断是否在微信浏览器

### filter_emoji:过滤表情

### modify_env:修改env配置文件

### generate_promotion_code:生成优惠码

### get_uid:生成唯一码

### get_order_no:生成订单编号

### is_debug:检测是否调试模式

### upload_image:上传图片

### del_file:删除文件

##使用

###在config/app.php中providers加入以下代码即可使用

    \SheaXiang\LaravelFunctionHelper\HelperServiceProvider::class

