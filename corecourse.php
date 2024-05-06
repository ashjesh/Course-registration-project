<?php
session_start();
if(isset($_POST['submit']))
{
  header("enroll.php");
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
    

   
    <div class="content-wrapper">
        <div class="container">
              <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-head-line">Compulsory Core Courses selection</h1>
                    </div>
                </div>
                <div class="row" >
                  <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                        <div class="panel-heading">
                          choose sem -  reg
                        </div>
<font color="red" align="center"><?php echo htmlentities($_SESSION['msg']);?><?php echo htmlentities($_SESSION['msg']="");?></font>


                        <div class="panel-body">
                       <form name="pincodeverify" method="post">
   <div class="form-group">
    <label for="Department">Department  </label>
    <input type="text" class="form-control" name="department" readonly value="<?php echo htmlentities($_SESSION['department']);?>" />	  
   </div>

   <div class="form-group">
    <label for="regulation">Regulation  </label>
    <input type="text" class="form-control" name="regulation" readonly value="<?php echo htmlentities($_SESSION['regulation']);?>" />	  
   </div>

	<div class="form-group">
    <label for="semester">Enter semester</label>
    <input type="text" class="form-control" id="semester" name="semester" readonly value="<?php echo htmlentities($_SESSION['semester']);?>" />
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

