<?php
	class CurlUtil 
	{
		
		/**
		 * 使用curl发送get请求
		 * @param unknown $url 发送请求的url
		 * @return 请求的结果
		 */
		function get($url){
			// 初始化
			$ch = curl_init ();
			// 设置选项，包括URL
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt($ch, CURLOPT_TIMEOUT,60 * 5);	//设置超时
			// 执行并获取HTML文档内容
			$output = curl_exec ( $ch );
			// 释放curl句柄
			curl_close ( $ch );
				
			return $output;
		}
		
		/**
		 * 使用curl发送post请求
		 * @param unknown $url 发送请求的url
		 * @param unknown $post_fields 需要post的数据
		 * @return 请求的结果
		 */
		function post($url,$post_fields){
			// 初始化
			$ch = curl_init ();
			// 设置选项，包括URL
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_fields );
			curl_setopt($ch, CURLOPT_TIMEOUT,60 * 5);	//设置超时
			// 执行并获取HTML文档内容
			$output = curl_exec ( $ch );
			// 释放curl句柄
			curl_close ( $ch );
			
			return $output;
		}
		
	}
?>