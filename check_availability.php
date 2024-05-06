<?php
	session_start();
	require_once("includes/config.php");
	if(strlen($_SESSION['login'])==null){
		header('location:index.php');
	}
	else{
		if(!empty($_POST["cid"])) {
				$cid= $_POST["cid"];
				echo ("<script>console.log('result: ".$cid."');</script>");
				$result1 =mysqli_query($bd, "SELECT * FROM courseenrolls WHERE studentRegno='".$_SESSION['login']."' and course='$cid'");
				$count1=mysqli_num_rows($result1);
				$result =mysqli_query($bd, "SELECT * FROM 	courseenrolls WHERE course='$cid'");
				$count=mysqli_num_rows($result);
				$result1 =mysqli_query($bd, "SELECT noofSeats FROM course WHERE id='$cid'");
				$row=mysqli_fetch_array($result1);
				$noofseat=$row['noofSeats'];
				if($count1>0)
				{
					echo '<script>alert("Course already registered");</script>';
					echo "<script>$('#submit').prop('disabled',true);</script>";
				} 
				else{
					echo "<script>$('#submit').prop('disabled',false);</script>";
					if($count >= $noofseat){
						echo "<script> alert('Seats are full for this course');</script>";
						echo "<script>$('#submit').prop('disabled',true);</script>";
					}
					else{
						echo "<script>$('#submit').prop('disabled',false);</script>";
					}
				}
		}
	}
?>
