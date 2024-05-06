<?php
echo ("<script>console.log('".$_POST['sid']."');</script>"); 
function check($bd, $cid, $sid, $sid1){
	$_SESSION["iterator"]=0;
	$count = 0;
	$off = explode("+",$sid);
	$query = mysqli_query($bd, "select * from students where department = '" . $_SESSION["department"] . "' and batch = '" . $_SESSION["batch"] . "' and semester = " . $_SESSION["semester"] . " ");
	$sql1 = mysqli_num_rows($query);
	if($sql1>0){ 
		echo ("<script>console.log('sql1 result: ".$sql1."');</script>");
	}
	$sql = mysqli_fetch_assoc(mysqli_query($bd, "select * from course where id = " . $off[1] . " "));
	if ($sql["staff1"] != "" && $sql["staff2"] != "" && $sql["staff3"] != "" && $sql["staff4"] != "" && $sql["staff5"] != "" && $sql["staff6"] != "") {
		$count = (int)($sql1 / 3);
		//$count += 5;
	} else if ($sql["staff1"] != "" && $sql["staff2"] != "" && $sql["staff3"] != "" && $sql["staff4"] != "" && $sql["staff5"] == "" && $sql["staff6"] == "" ) {
		$count = (int)$sql1 / 2;
		//$count += 5;
	} else if ($sql["staff1"] != "" && $sql["staff2"] != "" && $sql["staff3"] == "" && $sql["staff4"] == "" && $sql["staff5"] == "" && $sql["staff6"] == "" ) {
		$count = (int)$sql1 / 1;
		//$count += 5;
	}
	echo('<script>console.log("count of students:'.$count.'")</script>');
	$sql2 = mysqli_num_rows(mysqli_query($bd, "select * from courseenrolls where course = " . $off[1] . " and staff = '" . $off[0] . "' and staff1 = '".$off[2]."' and department = '" . $_SESSION["department"] . "' and batch = '" . $_SESSION["batch"] . "' and semester = " . $_SESSION["semester"] . " "));
	if($sql2>0){
		echo ("<script>console.log('sql2 success');</script>");
	}
	if ($count > $sql2) {
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
if (strlen($_SESSION['login']) == null) {
	header('location:index.php');
} else {
	if (!empty($_POST["course"]) && !empty($_POST["sid"]) && !empty($_POST["sid1"])) {
		$cid = $_POST["course"];
		$sid = $_POST["sid"];
        $sid1 = $_POST["sid1"];
		check($bd, $cid, $sid, $sid1);
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