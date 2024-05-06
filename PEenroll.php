<?php
session_start();
include('includes/config.php');

  if (isset($_POST['submit'])) {
    $studentname = $_POST['studentname'];
                       
                      foreach($_POST['course'] as $choice){
                      $sql=mysqli_query($bd,"insert into firstsem(id,studentname,totalcredits) values('','$studentname','$choice')");
                      if($sql){
                      $_SESSION["msg"] = "insert sucessfully";
                      }
                      else{
                        $_SESSION["msg"] = " not inserted";
                      }
                      }
                    }
                      

?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>

</script>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Course Enroll</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
    <?php include('includes/header.php'); ?>
    <!-- LOGO HEADER END-->
    <?php if ($_SESSION['login'] != "") {
      include('includes/menubar.php');
    }
    ?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-head-line">Course Enroll </h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Course Enroll
                        </div>
                        <font color="green" align="center">
                            <?php echo htmlentities($_SESSION['msg']); ?><?php $_SESSION['msg'] = ""; ?>
                        </font>
                        <?php $sql = mysqli_query($bd, "select * from students where StudentRegno='" . $_SESSION['login'] . "'");
              $cnt = 1;
              while ($row = mysqli_fetch_array($sql)) { ?>

                        <div class="panel-body">
                            <form name="dept" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="studentname">Student Name </label>
                                    <input type="text" class="form-control" id="studentname" name="studentname"
                                        value="<?php echo htmlentities($_SESSION['sname']); ?>" readonly />
                                </div>

                                
                                <div class="form-group">
                                    <label for="Course">Course </label>
                                    <br>
                                    <select class="form-select" multiple aria-label="multiple select example"
                                        name="course[]" id="course[]"  multiple required="required">
                                        <?php
                      $sql = mysqli_query($bd, "select * from course where type='Core' and department='" . $_SESSION['department'] . "' and batch = '" . $_SESSION['batch'] . "' and semester= " . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' ");
                      while ($row = mysqli_fetch_array($sql)) {
                      ?>
                                        <option value="<?php echo $row['id']; ?>" >
                                            <?php echo $row['courseName']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span id="course-availability-status1" style="font-size:12px;">
                                </div>


                                <button type="submit" name="submit" id="submit" class="btn btn-default">Enroll</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    </div>


    <?php
} ?>
</body>
</html>