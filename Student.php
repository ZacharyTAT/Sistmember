<?php
	/**
	 * 学生对象模型
	 * @author tang
	 */
	class Student
	{
		/** 学号 */
		public $stu_no = "";
		
		/** 姓名 */
		public $stu_name = "";
		
		/** 所在院校 */
		public $stu_college = "";
		
		/** 所在专业 */
		public $stu_major = "";
		
		/** 所在班级 */
		public $stu_class = "";
		
		/** 是否有学籍 */
		public $stu_exist = false;
		
		function __construct($stu_no, $stu_name, $stu_college, $stu_major, $stu_class, $stu_exist) {
			$this->stu_no = $stu_no;
			$this->stu_name = $stu_name;
			$this->stu_college = $stu_college;
			$this->stu_major = $stu_major;
			$this->stu_class = $stu_class;
			$this->stu_exist = $stu_exist;
		}
		
	}

?>