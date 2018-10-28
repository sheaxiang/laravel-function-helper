<?php
use Intervention\Image\Facades\Image;

if(!function_exists('is_weixin')) {

	/**
	 * 判断是否在微信浏览器
	 * @return bool
	 */
	function is_weixin(){
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
}

if(!function_exists('filter_emoji')) {

	/**
	 * 过滤表情
	 * @param $str
	 * @return null|string|string[]
	 */
	function filter_emoji($str)
	{
		$str = preg_replace_callback(
			'/./u',
			function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},
			$str);

		return $str;
	}
}

if(!function_exists('modify_env')) {

	/**
	 * 修改env配置文件
	 * @param array $data
	 */
	function modify_env(array $data)
	{
		$envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

		$contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

		$contentArray->transform(function ($item) use ($data){
			foreach ($data as $key => $value){
				if(str_contains($item, $key)){
					return $key . '=' . $value;
				}
			}

			return $item;
		});

		$content = implode($contentArray->toArray(), "\n");

		\Illuminate\Support\Facades\File::put($envPath, $content);
	}
}

if(!function_exists('generate_promotion_code')) {

	/**
	 * 生成优惠码
	 * @param int $no_of_codes//定义一个int类型的参数 用来确定生成多少个优惠码
	 * @param array $exclude_codes_array//定义一个exclude_codes_array类型的数组
	 * @param int $code_length //定义一个code_length的参数来确定优惠码的长度
	 * @return array//返回数组
	 */
	function generate_promotion_code($no_of_codes,$exclude_codes_array='',$code_length = 12)
	{
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$promotion_codes = array();//这个数组用来接收生成的优惠码
		for($j = 0 ; $j < $no_of_codes; $j++)
		{
			$code = "";
			for ($i = 0; $i < $code_length; $i++)
			{
				$code .= $characters[mt_rand(0, strlen($characters)-1)];
			}
			//如果生成的4位随机数不再我们定义的$promotion_codes函数里面
			if(!in_array($code,$promotion_codes))
			{
				if(is_array($exclude_codes_array))//
				{
					if(!in_array($code,$exclude_codes_array))//排除已经使用的优惠码
					{
						$promotion_codes[$j] = $code;//将生成的新优惠码赋值给promotion_codes数组
					} else {
						$j--;
					}
				} else {
					$promotion_codes[$j] = $code;//将优惠码赋值给数组
				}
			} else {
				$j--;
			}
		}
		return $promotion_codes;
	}
}

if(!function_exists('get_uid')) {

	/**
	 * 生成唯一码
	 * @param $no_of_codes
	 * @return array
	 */
	function get_uid($no_of_codes) {
		$promotion_codes = array();//这个数组用来接收生成的优惠码
		for($j = 0 ; $j < $no_of_codes; $j++)
		{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$promotion_codes[$j] = substr($charid, 0, 8).$hyphen
				.substr($charid,16, 8);
		}
		return $promotion_codes;
	}
}

if(!function_exists('get_order_no')) {
	/**
	 * 生成订单编号
	 *
	 * @param string $prefix
	 * @return string
	 */
	function get_order_no($prefix = 'Q')
	{
		/* 选择一个随机的方案 */
		mt_srand((double) microtime()*1000000);
		return $order_no = $prefix.date('YmdHis').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}
}

if(!function_exists('is_debug')){

	/**
	 * 检测是否调试模式
	 *
	 * @return bool
	 */
	function is_debug(){

		return Config::get('app.debug');
	}
}

if(!function_exists('upload_image')) {

	/**
	 * 上传图片
	 * @param $file_name
	 * @param $file
	 * @param array $size
	 * @return null|string
	 */
	function upload_image($file_name, $file, array $size = array())
	{
		if(count($file) > 1 || is_array($file)) {//多图上传
			$images = [];
			foreach($file as $item) {
				$path = $file_name.'/'.date('Ym/').md5(time().str_random(20)).'.'.$item->getClientOriginalExtension();
				$image = empty($size) ? Image::make($item)->encode($item->getClientOriginalExtension(), 75) :
					Image::make($item)->fit($size['width'], $size['height'])->encode($item->getClientOriginalExtension(), 75);

				if (Storage::disk('public')->put($path, (string)$image, 'public')) {
					$images[] =  Storage::url($path);
				}
			}
			return count($images) > 1 ? $images : $images[0];
		} else {//单图上传
			$path = $file_name.'/'.date('Ym/').md5(time().str_random(20)).'.'.$file->getClientOriginalExtension();
			$image = empty($size) ? Image::make($file)->encode($file->getClientOriginalExtension(), 75) :
				Image::make($file)->fit($size['width'], $size['height'])->encode($file->getClientOriginalExtension(), 75);

			if (Storage::disk('public')->put($path, (string)$image, 'public')) {
				return Storage::url($path);
			}
		}


		return null;
	}
}

if(!function_exists('del_file')) {

	/**
	 * 删除文件
	 * @param $fileName
	 * @return bool
	 */
	function del_file($fileName) {
		Storage::disk('public')->delete(preg_replace('/\/storage/','',$fileName));
		return true;
	}
}

if(!function_exists('dda')){

	/**
	 * 打印输出数组
	 * @param $model
	 */
	function dda($model)
	{
		if (method_exists($model, 'toArray')) {
			dd($model->toArray());
		} else {
			dd($model);
		}
	}
}

if (!function_exists('is_mobile')) {

	/**
	 * 移动端判断
	 */
	function is_mobile()
	{
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
		{
			return true;
		}
		// 如果via信息含有wap则一定是移动设备
		if (isset ($_SERVER['HTTP_VIA']))
		{
			// 找不到为flase,否则为true
			return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
		}
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高
		if (isset ($_SERVER['HTTP_USER_AGENT']))
		{
			$clientkeywords = array ('nokia',
				'sony',
				'ericsson',
				'mot',
				'samsung',
				'htc',
				'sgh',
				'lg',
				'sharp',
				'sie-',
				'philips',
				'panasonic',
				'alcatel',
				'lenovo',
				'iphone',
				'ipod',
				'blackberry',
				'meizu',
				'android',
				'netfront',
				'symbian',
				'ucweb',
				'windowsce',
				'palm',
				'operamini',
				'operamobi',
				'openwave',
				'nexusone',
				'cldc',
				'midp',
				'wap',
				'mobile'
			);
			// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
			{
				return true;
			}
		}
		// 协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT']))
		{
			// 如果只支持wml并且不支持html那一定是移动设备
			// 如果支持wml和html但是wml在html之前则是移动设备
			if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
			{
				return true;
			}
		}
		return false;
	}
}

/**
 * 自定义 Ajax 返回格式
 *
 * @param $status
 * @param $respond
 * @return \Illuminate\Http\JsonResponse
 */
function respond($status, $respond, $code)
{
	return response()->json(['status' => $status, is_string($respond) ? 'message' : 'data' => $respond], $code);
}

/**
 * 自定义 Ajax 成功返回
 *
 * @param $respond
 * @return \Illuminate\Http\JsonResponse
 */
function succeed($respond = 'Request success!')
{
	return respond(true, $respond, 200);
}

/**
 * 自定义 Ajax 失败返回
 *
 * @param $respond
 * @return \Illuminate\Http\JsonResponse
 */
function failed($respond = 'Request failed!', $code = 400)
{
	return respond(false, $respond,$code);
}




