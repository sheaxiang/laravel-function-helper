<?php

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

