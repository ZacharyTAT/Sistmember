<?php
require_once 'CurlUtil.php';
require_once 'College.php';
require_once 'ClassModel.php';
require_once 'Student.php';

	class FetchListUtil{
		/**
		 * 获取学院列表(参见学院模型)
		 * @return 存储学院模型的数组
		 */
		function collegeLists()
		{
			$url = "http://dean.swjtu.edu.cn/servlet/AjaxXML?KeyWord=&SelectType=CollegeInfo";
// 			$url = "http://localhost/Sistmember/XmlCreator.php";
			$curl = new CurlUtil();
			$xmlStr = $curl->get($url);	//返回的是一个xml
			
			/** http://jingyan.baidu.com/article/a24b33cd6c61a319fe002bed.html */
			/** http://www.cnblogs.com/likwo/archive/2011/08/24/2151793.html */
			
			$xml = simplexml_load_string($xmlStr);
			
			//代码
			$college_code_arr = (array)($xml->college_code);
			//名称
			$college_name_arr = (array)($xml->college_name);
			//简称(2字)
			$college_abname_arr = (array)($xml->college_abname);
			//简称
			$college_short_name_arr = (array)($xml->college_short_name);
			//英文名
			$college_english_name_arr = (array)($xml->college_english_name);
			//院主任
			$dean_teaching_arr = (array)($xml->dean_teaching);
			//院长
			$teach_manager_arr = (array)($xml->teach_manager);
			
			$count = count($college_code_arr);
			
			$college_arr = array();
			
			for ($i = 0; $i < $count; $i++) {
				$college = new College();
				$college->college_code = (string)($college_code_arr[$i]);
				$college->college_name = (string)($college_name_arr[$i]);
				$college->college_abname = (string)($college_abname_arr[$i]);
				$college->college_short_name = (string)($college_short_name_arr[$i]);
				$college->college_english_name = (string)($college_english_name_arr[$i]);
				$college->dean_teaching = (string)($dean_teaching_arr[$i]);
				$college->teach_manager = (string)($teach_manager_arr[$i]);
				
				$college_arr[$i] = $college;
			}
			
			return $college_arr;
		}
		
		/**
		 * 通过学院代码获取班级名称
		 * @param string $college_code 学院代码
		 * @return 存储班级模型的字典，key是年级<br/>
		 * 如 2012、2013、2014、2015 <br/>
		 * value是一个数组，存储该年级的所有班级
		 */
		function  classList($college_code) 
		{
			$url = "http://dean.swjtu.edu.cn/servlet/AjaxXML?KeyWord=$college_code&SelectType=ClassInfoInStatus";
			$curl = new CurlUtil();
			$xmlStr = $curl->get($url);	//返回的是一个xml
			
			$xml = simplexml_load_string($xmlStr);
			
			//代码
			$class_code_arr = (array)($xml->class_code);
			//名称
			$class_name_arr = (array)($xml->class_name);
			
			$class_arr = array();
			$class_arr["2012"] = array();
			$class_arr["2013"] = array();
			$class_arr["2014"] = array();
			$class_arr["2015"] = array();
			$count_2012 = 0;
			$count_2013 = 0;
			$count_2014 = 0;
			$count_2015 = 0;
			$count = count($class_code_arr);
			
			for ($i = 0; $i < $count; $i++) {
				$classModel = new ClassModel();
				$classModel->class_code = (string)($class_code_arr[$i]);
				$classModel->class_name = (string)($class_name_arr[$i]);
				
				$class_name = $classModel->class_name;
				
				//判断年级
				if (stripos($class_name, "2012") !== false) { //2012
					$class_arr["2012"][$count_2012] = $classModel;
					$count_2012 = $count_2012 + 1;
				}else if (stripos($class_name, "2013") !== false) { // 2013
					$class_arr["2013"][$count_2013] = $classModel;
					$count_2013 = $count_2013 + 1;
					
				}else if (stripos($class_name, "2014") !== false) { // 2014
					$class_arr["2014"][$count_2014] = $classModel;
					$count_2014 = $count_2014 + 1;
					
				}else{ // 2015
					$class_arr["2015"][$count_2015] = $classModel;
					$count_2015 = $count_2015 + 1;			
				}
			}
			return $class_arr;
		}
		
		/**
		 * 查询某一班级的人数
		 * @param string $college_code 学院代码
		 * @param string $class_code 班级代码
		 * @return number 班级的人数
		 */
		function classMember($college_code,$class_code, array &$not_exit_student_arr = null)
		{
			$url = "http://dean.swjtu.edu.cn/public/QueryStudentInfo.jsp";
			$post_field = "query_action=query&query_type=class_code_all&check_type=name&student_id=&college_code=$college_code&class_code=$class_code";
			$curl = new CurlUtil();
			$html = $curl->post($url,$post_field);	//返回的是一个html,需要正则匹配
// 			echo $html;
			$pattern = '/<td height="28" align="center" bgcolor="#FFFFFF">(.*?)<\\/td>/si';
			preg_match_all($pattern, $html, $matches);
			if (empty($matches)) return 0;
			//处理无学籍情况
			$matchescount = count($matches[1]);
			$not_exit_student_arr = array();
			$count = 0;
			for ($i = 0; $i < $matchescount; $i++) {
				$row = $matches[1][$i];
				if (stripos($row, "无学籍") !== false) { //无学籍
					$student = new Student($matches[1][$i-6], $matches[1][$i-5], $matches[1][$i-3], $matches[1][$i-2], $matches[1][$i-1], false);
					$not_exit_student_arr[$count] = $student;
					$count = $count + 1;
				}
				
			}
			
			return count($matches[0]) / 7;
		}
		
		/**
		 * 打印xml
		 * @param string $xmlStr xml的纯文本字符串
		 */
		function printXML($xmlStr)
		{
			$pos = stripos($xmlStr, "encoding");
			if ($pos !== false) { //contains encoding
				$posUTF8 = stripos($xmlStr, "UTF-8");
				if($posUTF8 === false) { // not UTF-8
					$pattern = '/encoding="(.*)"/si';
					preg_match_all($pattern, $xmlStr, $matches);
					if (!empty($matches))
						$encoding = $matches[1][0];
				}
			}
			if (!empty($encoding)) {
				$headerStr = "content-type:text/xml;charset=$encoding";
			}else{
				$headerStr = "content-type:text/xml;charset=UTF-8";
			}
			header($headerStr);
			//echo iconv("GBK", "UTF-8", $xmlStr);
			echo $xmlStr;
		}
	}
?>