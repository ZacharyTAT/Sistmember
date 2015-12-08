<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Welcome</title>
</head>

<body>
	<?php
		require_once 'FetchListUtil.php';
		require_once 'Student.php';
		
		set_time_limit(0);
		echo str_repeat(" ",1024);
		
		$flu = new FetchListUtil();
		$collegeList = $flu->collegeLists();
		$college_count = count($collegeList);
		for ($i = 0; $i < $college_count; $i++) { //学院
			
			echo "<h2>".$collegeList[$i]->college_name."</h2>";
			echo "<ul>";
			
			$college_code = $collegeList[$i]->college_code;
			$classList = $flu->classList($college_code);
			
			$college_members = 0; //记录某学院总人数
			$student_not_exist_count_college = 0; //记录某学院没有学籍人数
			
			foreach ($classList as $grade => $grade_class_list) { //年级
				echo "<span><b>".$grade." : "."</b></span><br/>";
				
				$class_count = count($grade_class_list);
				
				$grade_members = 0; //记录某学院某年级总人数
				$student_not_exist_count_grade = 0; // 记录某学院某年级没有学籍人数
				
				for ($j = 0; $j < $class_count; $j++) { //班级
					$class_code = $grade_class_list[$j]->class_code;
					$class_members = $flu->classMember($college_code, $class_code,$not_exist_student_arr);
					
					$college_members += $class_members;
					$grade_members += $class_members;
					
					echo "<li>".$grade_class_list[$j]->class_name." : ".$class_members;
					
					if (!empty($not_exist_student_arr)) {
						echo ",其中无学籍的有:".count($not_exist_student_arr)."人";
						$not_exist_student_arr_count = count($not_exist_student_arr);
						$student_not_exist_count_college += $not_exist_student_arr_count;
						$student_not_exist_count_grade += $not_exist_student_arr_count;
					}
					echo "</li>";
					ob_flush();
	    			flush();
				}
				echo "<span style='color:blue'>". $grade ."有".$grade_members."人，不包括无学籍，还有".($grade_members - $student_not_exist_count_grade)."人</span><br/>";
				ob_flush();
				flush();
			}
			echo "<span style='color:red'>".$collegeList[$i]->college_name ."有".$college_members."人，不包括无学籍，还有".($college_members - $student_not_exist_count_college)."人</span><br/>";
			echo "</ul>";
			ob_flush();
    		flush();
		}
?>
</body>
</html>