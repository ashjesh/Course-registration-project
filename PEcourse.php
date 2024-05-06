<?php
session_start();
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{

if(isset($_POST['submit']))
{
$sql=mysqli_query($bd, "SELECT * FROM  pecourse where department='".$_POST['department']."' && semester='".$_POST['semester']."' && regulation='".$_POST['regulation']."' && batch='".$_POST['batch']."' ");
$num=mysqli_fetch_array($sql);
if($num>0)
{
$_SESSION['depart']=$_POST['department'];
$_SESSION['semes']=$_POST['semester'];
$_SESSION['reg']=$_POST['regulation'];
$_SESSION['bat']=$_POST['batch'];
header("location:PEenroll.php");
}
else
{
$_SESSION['msg']="Error :Wrong info. Please Enter a correctly !!";
}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>dept sem reg selection</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
<?php include('includes/header.php');?>
    
<?php if($_SESSION['login']!="")
{
 include('includes/menubar.php');
}
 ?>
   
    <div class="content-wrapper">
        <div class="container">
              <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Programme Elective Course selection</h1>
                    </div>
                </div>
                <div class="row" >
                  <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                        <div class="panel-heading">
                          choose sem - Reg
                        </div>
<font color="red" align="center"><?php echo htmlentities($_SESSION['msg']);?><?php echo htmlentities($_SESSION['msg']="");?></font>


                        <div class="panel-body">
                       <form name="pincodeverify" method="post">

<?php $sql=mysqli_query($bd, "select department from students where StudentRegno='".$_SESSION['login']."'");
$cnt=1;
while($row=mysqli_fetch_array($sql))
{ 
?>
   <div class="form-group">
    <label for="Department">Department  </label>
    <input type="text" class="form-control" name="department" readonly value="<?php echo htmlentities($row['department']);?>" />	  
   </div>
  <?php } ?>
 
	<div class="form-group">
    <label for="semester">Enter semester</label>
    <input type="text" class="form-control" id="semester" name="semester" placeholder="semester" required />
  </div>

  <div class="form-group">
    <label for="regulation">Enter regulation</label>
    <input type="text" class="form-control" id="regulation" name="regulation" placeholder="regulation" required />
  </div>

  <div class="form-group">
    <label for="batch">Enter batch</label>
    <input type="text" class="form-control" id="batch" name="batch" placeholder="batch" required />
  </div>
 
  <button type="submit" name="submit" class="btn btn-default">Verify</button>
                           <hr />
   



</form>
                            </div>
                            </div>
                    </div>
                  
                </div>
        </div>
    </div>
    
  <?php include('includes/footer.php');?>
   
    <script src="assets/js/jquery-1.11.1.js"></script>

    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
<?php } ?>
