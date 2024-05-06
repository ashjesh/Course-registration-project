<?php

function check($bd, $cid, $sid){
	$_SESSION["iterator"]=0;
	$count = 0;
	$off = explode(" ",$sid);
	echo('<script>console.log("'.$off[0].$off[1].'");</script>');
	$query = mysqli_query($bd, "select * from courseenrolls where course = " . $off[1] . " and department = '" . $_SESSION["department"] . "' and batch = '" . $_SESSION["batch"] . "' and semester = " . $_SESSION["semester"] . " ");
	$sql1 = mysqli_num_rows($query);
	if($sql1>0){ 
		echo ("<script>console.log('sql1 result: ".$sql1."');</script>");
	}
	$sql = mysqli_fetch_assoc(mysqli_query($bd, "select * from course where id = " . $off[1] . " "));
	if ($sql["staff1"] != "" && $sql["staff2"] != "" && $sql["staff3"] != "") {
		$count = (int)($sql1 / 3);
		$count += 5;
	} else if ($sql["staff1"] != "" && $sql["staff2"] != "" && $sql["staff3"] == "") {
		$count = (int)$sql1 / 2;
		$count += 5;
	} else if ($sql["staff1"] != "" && $sql["staff2"] == "" && $sql["staff3"] == "") {
		$count = (int)$sql1 / 1;
		$count += 5;
	}
	echo('<script>console.log("count of students:'.$count.'")</script>');
	$sql2 = mysqli_num_rows(mysqli_query($bd, "select * from courseenrolls where course = " . $off[1] . " and staff = '" . $off[0] . "' and department = '" . $_SESSION["department"] . "' and batch = '" . $_SESSION["batch"] . "' and semester = " . $_SESSION["semester"] . " "));
	if($sql2>0){
		echo ("<script>console.log('sql2 success');</script>");
	}
	if (2 < $sql2) {
	// if ($count >= $sql2) {
		echo '<script>console.log("'.$sql2.'staff available");</script>';
		echo "<script>$('#submit').prop('disabled',false);</script>";
		// echo "<script>$('#submit').prop('disabled',true);</script>";
	} else {
		echo '<script>alert("Staff registration limit exceeded. Choose another staff");</script>';
		echo "<script>var a = document.querySelectorAll('select');for( i=0; i<a.length;i++){a[i].selectedIndex = -1;}</script>";
		echo "<script>$('#submit').prop('disabled',true);</script>";
		header("staff.php");
	}
	$_SESSION["iterator"]+=1;
}

session_start();
require_once("includes/config.php");
echo ('<script>alert("hello");</script>');
if (strlen($_SESSION['login']) == null) {
	header('location:index.php');
} else {
	if (!empty($_POST["course"]) && !empty($_POST["sid"])) {
		$cid = $_POST["course"];
		$sid = $_POST["sid"];
		check($bd, $cid, $sid);
		// if($count1>0)
		// {
		// 	echo '<script>alert("Course already registered");</script>';
		// 	echo "<script>$('#submit').prop('disabled',true);</script>";
		// } 
		// else{
		// 	echo "<script>$('#submit').prop('disabled',false);</script>";
		// 	if($count >= $noofseat){
		// 		echo "<script> alert('Seats are full for this course');</script>";
		// 		echo "<script>$('#submit').prop('disabled',true);</script>";
		// 	}
		// 	else{
		// 		echo "<script>$('#submit').prop('disabled',false);</script>";
		// 	}
		// }
	}
}
?>