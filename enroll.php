<?php
session_start();
include('includes/config.php');
if ( isset($_GET['msg'])){
  $_SESSION["msg"] = "wait till all the students completes registration";
}
$rst = mysqli_fetch_assoc(mysqli_query($bd, "select elective from semester where id = " . $_SESSION['semester'] . " and department = '" . $_SESSION['department'] . "' and regulation = '" . $_SESSION['regulation'] . "' "));
$cr = $rst["elective"];
$noncgpa = mysqli_query($bd, "select * from noncgpa where name = " . $_SESSION["login"] . " and type = 'Credit Transfer' and status = 'Approved_by_HOD' ");
$creditcount = mysqli_num_rows($noncgpa);
$flag = 0;
if ($creditcount > 0) {
  $flag = 1;
}
if ($_SESSION['id'] != null) {
  header('location:index.php');
} else {
  //$c = array();
  date_default_timezone_set('Asia/Kolkata');
  $currentTime = date('d-m-Y h:i:s A', time());
  if (isset($_POST['submit'])) {
    $studentregno = $_POST['studentregno'];
    $studentname = $_POST['studentname'];
    $dept = $_POST['department'];
    $sem = $_POST['sem'];
    $batch = $_POST['batch'];
    if ($cr > 0) {
      $e = $_POST['elective'];
    }
    //$c = $_POST['course'];
    /*foreach($c as $a){
      $_SESSION['msg'] .= $a;
    }*/
    $count = 0;
    $sql = mysqli_query($bd, "SELECT creditsum from totalcredits where studentname='" . $_POST['studentname'] . "' && studentRegno='" . $_POST['studentregno'] . "' &&  semester='" . $_POST['sem'] . "' && department='" . $_POST['department'] . "' && batch='" . $_POST['batch'] . "'");
    // if (mysqli_num_rows($sql) > 0) {
    $num = mysqli_fetch_assoc($sql);
    if ($num == null || $num["creditsum"] < 30) {
      //  for ($var = 0; $var < sizeof($c); $var++){ 
      //    $a = $c[$var];
      $sql10 = mysqli_query($bd, "select * from course where (department='" . $_SESSION['department'] . "' and semester=" . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and batch = '".$_SESSION['batch']."' and type='Core') or (department='" . $_SESSION['department'] . "' and semester=" . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and batch = '".$_SESSION['batch']."' and type='CoreLab') or (department='" . $_SESSION['department'] . "' and semester=" . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and batch = '".$_SESSION['batch']."' and type='OneCredit') ");
      while ($rr = mysqli_fetch_assoc($sql10)) {
        $a = $rr["id"];
        //foreach($c as $a){
        $course = $a;
        $ref = mysqli_query($bd, "SELECT * FROM courseenrolls where studentName='" . $_POST['studentname'] . "' && semester=" . $_POST['sem'] . " && course='" . $a . "' && department='" . $_POST['department'] . "' && studentRegno='" . $_POST['studentregno'] . "' && batch='" . $_POST['batch'] . "' ");
        $col = mysqli_num_rows($ref);
        if ($col == 0) {
          $tab = mysqli_query($bd, "SELECT credit from course where id='" . $a . "'");
          $row = mysqli_fetch_assoc($tab);
          $count += $row["credit"];
          //$res = mysqli_query($bd, "UPDATE totalcredits SET creditsum=creditsum+$cdt where studentname='" . $_POST['studentname'] . "' &&  semester='" . $_POST['sem'] . "' && batch='" . $_POST['batch'] . "' && studentRegno='" . $_POST['studentregno'] . "' && department='" . $_POST['department'] . "' ");

          $ret = mysqli_query($bd, "insert into courseenrolls(studentRegno,studentname,department,course,semester,batch) values('$studentregno','$studentname','$dept','$course','$sem','$batch')");
          if ($ret) {
            $_SESSION['msg'] .= $a . "Enroll Successfully !! ";
          } else {
            $_SESSION['msg'] = "Error : Not Enroll";
          }
        }
        else{
          $_SESSION['msg'] .= $a. "Already registered !!";
        }
      }
      if ($cr > 0) {
        if(isset($_POST["credit"])){
          $transfer = $_POST["credit"];
          foreach($transfer as $t){
            $sql =mysqli_fetch_assoc(mysqli_query($bd, "select * from course where type= 'Elective" . $t . "' and department='" . $_SESSION['department'] . "' and semester='" . $_SESSION['semester'] . "' and regulation='" . $_SESSION['regulation'] . "' limit 1"));
            $sql1 = mysqli_query($bd, "update noncgpa set course = ".$sql["id"].", semester = ".$_SESSION["semester"].", status = 'Completed' where name=".$_SESSION["login"]." and status = 'Approved_by_HOD' limit 1; ");
            if($sql1){
              $_SESSION["msg"] .= $sql["id"]." Credit transfer sucessful!! ";
            }else{
              $_SESSION["msg"] .= $sql["id"]." Credit transfer unsucessful!! ";
            }
          }
        }
          if(sizeof($e) > 0){
            foreach ($e as $el) {
              $ref = mysqli_query($bd, "SELECT * FROM courseenrolls where studentName='" . $_POST['studentname'] . "' && semester=" . $_POST['sem'] . " && course='" . $el . "' && department='" . $_POST['department'] . "' && studentRegno='" . $_POST['studentregno'] . "' && batch='" . $_POST['batch'] . "' ");
              $col = mysqli_num_rows($ref);
              if ($col == 0) {
                $cc1 = mysqli_fetch_assoc(mysqli_query($bd, "SELECT credit from course where id='" . $el . "'"));
                $count += $cc1['credit'];
                //$ret = mysqli_query($bd,"UPDATE totalcredits SET creditsum=creditsum+".$cc1['credit']." where studentname='" . $_POST['studentname'] . "' &&  semester='" . $_POST['sem'] . "' && batch='" . $_POST['batch'] . "' && studentRegno='" . $_POST['studentregno'] . "' && department='" . $_POST['department'] . "' ");
                $o = mysqli_query($bd, "insert into courseenrolls(studentRegno,studentname,department,course,semester,batch) values('$studentregno','$studentname','$dept','$el','$sem','$batch')");
                if ($o) {
                  $_SESSION['msg'] .= $el . "Enroll Successfully !! ";
                } else {
                  $_SESSION['msg'] .= $el . "Error : Not Enroll";
                }
              }
            }
        }
      }
      $sql = mysqli_query($bd, "SELECT creditsum from totalcredits where studentname='" . $_POST['studentname'] . "' && studentRegno='" . $_POST['studentregno'] . "' &&  semester='" . $_POST['sem'] . "' && department='" . $_POST['department'] . "' && batch='" . $_POST['batch'] . "'");
      if (mysqli_num_rows($sql) > 0) {
        $res = mysqli_query($bd, "UPDATE totalcredits SET creditsum=creditsum+$count where studentname='" . $_POST['studentname'] . "' &&  semester='" . $_POST['sem'] . "' && batch='" . $_POST['batch'] . "' && studentRegno='" . $_POST['studentregno'] . "' && department='" . $_POST['department'] . "' ");
      } else {
        $res = mysqli_query($bd, "INSERT INTO totalcredits(creditsum,studentname,semester,batch,studentRegno,department) values($count,'$studentname','$sem','$batch','$studentregno','$dept')");
      }
    } else {
      $_SESSION['msg'] = "You have selected course for more than 30 credits. Please register within 30 credits";
    }


    // } else {
    //   foreach($c as $a){
    //     $ref = mysqli_query($bd, "SELECT * FROM courseenrolls where studentname='" . $_POST['studentname'] . "' && semester='" . $_POST['sem'] . "' && course=" . $a . " && department='" . $_POST['department'] . "' && studentRegno='" . $_POST['studentregno'] . "' && batch='" . $_POST['batch'] . "' ");
    //     $col = mysqli_num_rows($ref);
    //     if ($col == 0) {

    //       $tab = mysqli_query($bd, "SELECT credit from course where id='" . $a . "'");
    //       $row = mysqli_fetch_assoc($tab);
    //       $cr = $row["credit"];
    //       $studentregno = $_POST['studentregno'];
    //       $studentname = $_POST['studentname'];
    //       $dept = $_POST['department'];
    //       $course = $a;
    //       $sem = $_POST['sem'];
    //       $batch = $_POST['batch'];
    //       $res = mysqli_query($bd, "INSERT INTO totalcredits(creditsum,studentname,semester,batch,studentRegno,department) values($cr,'$studentname','$sem','$batch','$studentregno','$dept')");
    //       if ($res) {

    //         $ret = mysqli_query($bd, "insert into courseenrolls(studentRegno,studentname,department,course,semester,batch) values('$studentregno','$studentname','$dept','$course','$sem','$batch')");
    //         if ($ret) {
    //           $_SESSION['msg'] .= $a."Enroll Successfully !!";
    //         } else {
    //           $_SESSION['msg'] = "Error : Not Enroll";
    //         }
    //       } else {
    //         $_SESSION['msg'] = "Error in credit insertion process";
    //       }
    //     }
    //     foreach($e as $el){
    //       $cc1 = mysqli_fetch_assoc(mysqli_query($bd,"SELECT credit from course where id='" . $el . "'"));
    //       $cred = isset($cc1['credit']); 
    //       $ret = mysqli_query($bd, "insert into courseenrolls(studentRegno,studentname,department,course,semester,batch) values('$studentregno','$studentname','$dept','$el','$sem','$batch')");
    //       $_SESSION['msg'] .= $el . "Enroll Successfully !! ";
    //       if($ret){
    //         $o = mysqli_query($bd, "UPDATE totalcredits SET creditsum=creditsum+".$cred." where studentname='" . $_POST['studentname'] . "' &&  semester='" . $_POST['sem'] . "' && batch='" . $_POST['batch'] . "' && studentRegno='" . $_POST['studentregno'] . "' && department='" . $_POST['department'] . "' ");
    //         if(!$o){
    //           $_SESSION['msg'] .= "Credit update failed".$el;
    //         }
    //       }

    //     }
    //   }
    // }
  }
}
if (isset($_POST['sendreq'])) {
  $sq = mysqli_num_rows(mysqli_query($bd, "Select * from notification where from_user = '" . $_SESSION['sname'] . "' and status = 'Pending' and semester = '".$_SESSION['semester']."' "));
  if ($sq != 0) {
    $_SESSION['errmsg'] = "only one request can be sent";
  } else {
    $sql = mysqli_query($bd, "Insert into notification(rollno,semester,from_user,to_user,message,status) values(" . $_SESSION["login"] . "," . $_SESSION["semester"] . ",'" . $_SESSION["sname"] . "','admin','request for re-registering course by " . $_SESSION["sname"] . "', 'Pending') ");
    if ($sql != 0) {
      $_SESSION["msg"] = "request sent sucessfully";
    }
  }
}
?>
<?php
$ele = mysqli_num_rows(mysqli_query($bd, "Select * from courseenrolls a inner join course b on a.course=b.id  where  a.semester=".$_SESSION['semester']." and a.studentRegno = " . $_SESSION['login'] . " and b.type='Core' "));
if ($ele == 0) { ?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<script src="assets/js/jquery-1.11.1.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script>
function courseAvailability(value) {
    $("#loaderIcon").show();
    jQuery.ajax({
        url: "check_availability.php",
        data: 'cid=' + value,
        type: "POST",
        success: function(data) {
            $("#course-availability-status1").html(data);
            $("#loaderIcon").hide();
        },
        error: function() {}
    });
}
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
                                    <label for="studentregno">Student Reg No </label>
                                    <input type="text" class="form-control" id="studentregno" name="studentregno"
                                        value="<?php echo htmlentities($_SESSION['login']); ?>"
                                        placeholder="Student Reg no" readonly />

                                </div>

                                <div class="form-group">
                                    <label for="batch">Batch </label>
                                    <input type="text" class="form-control" id="batch" name="batch" readonly
                                        value="<?php echo htmlentities($_SESSION['batch']); ?>" required />
                                </div>


                                <div class="form-group">
                                <label for="Pincode">Student Photo  </label>
                                <?php if($row['studentPhoto']==""){ ?>
                                <img src="studentphoto/noimage.png" width="200" height="200"><?php } else {?>
                                <img src="data:image/jpeg;base64,<?php echo $row['studentPhoto']; ?>" width="200" height="200">
                                <?php } ?>
                                </div>
                                <?php } ?>

                                <?php $sql = mysqli_query($bd, "select department from students where studentRegno='" . $_SESSION['login'] . "'");
                  $cnt = 1;
                  while ($row = mysqli_fetch_array($sql)) {
                  ?>
                                <div class="form-group">
                                    <label for="Department">Department </label>
                                    <input type="text" class="form-control" name="department" readonly
                                        value="<?php echo htmlentities($row['department']); ?>" />
                                </div>

                                <?php } ?>


                                <?php $sql = mysqli_query($bd, "select semester from students where studentRegno='" . $_SESSION['login'] . "'");
                  $cnt = 1;
                  while ($row = mysqli_fetch_array($sql)) {
                  ?>

                                <div class="form-group">
                                    <label for="Semester">Semester </label>
                                    <input type="text" class="form-control" name="sem" readonly
                                        value="<?php echo htmlentities($row['semester']); ?>" />
                                </div>

                                <?php } ?>


                                <div class="form-group">
                                    <label for="Course">Course </label>
                                    <br>
                                    <select class="form-select" multiple aria-label="multiple select example"
                                        name="course[]" id="course[]" onchange="courseAvailability(this.value)"
                                         required="required">
                                        <?php
                      $sql = mysqli_query($bd, "select * from course where (department='" . $_SESSION['department'] . "' and batch = '" . $_SESSION['batch'] . "' and semester= " . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and type='Core') or (department='" . $_SESSION['department'] . "' and batch = '" . $_SESSION['batch'] . "' and semester= " . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and type='CoreLab') or (department='" . $_SESSION['department'] . "' and batch = '" . $_SESSION['batch'] . "' and semester= " . $_SESSION['semester'] . " and regulation='" . $_SESSION['regulation'] . "' and type='OneCredit') ");
                      while ($row = mysqli_fetch_array($sql)) {
                        array_push($c, $row["id"]);
                      ?>
                                        <option value="<?php echo $row['id']; ?>" selected disabled>
                                            <?php echo $row['courseName']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <span id="course-availability-status1" style="font-size:12px;">
                                </div>

                                <div class="form-group">
                                    <label for="Course">Elective </label>
                                    <br>
                                    <?php
                    if ($cr == 0) {
                      echo 'No Electives available for this semester';
                    } else {
                      if ($flag == 1) {
                    ?> <font color="green">you have credit transfer option. Check any <?php echo $creditcount; ?> for
                                        credit transfer</font>
                                    <?php
                      }
                      for ($i = 1; $i <= $cr; $i++) {
                        if ($flag == 1) {
                        ?>
                                    <input type="checkbox" id="credit<?php echo $i; ?>" name="credit[]" value="<?php echo $i; ?>"
                                        onchange="check(this,<?php echo $i; ?>)">
                                    <?php
                        }
                        ?>
                                    <div class="form-group">
                                        <label for="Course">Course </label>
                                        <select class="form-select" aria-label="Default select example"
                                            name="elective[]" id="elective<?php echo $i; ?>"
                                            onchange="courseAvailability(this.value)" required="required">
                                            <?php
                            $sql = mysqli_query($bd, "select * from course where type= 'Elective" . $i . "' and department='" . $_SESSION['department'] . "' and semester='" . $_SESSION['semester'] . "' and regulation='" . $_SESSION['regulation'] . "' and batch = '" . $_SESSION['batch'] . "'  ");
                            while ($row = mysqli_fetch_array($sql)) {
                            ?>
                                            <option id="<?php echo $i; ?>"
                                                value="<?php echo htmlentities($row['id']); ?>">
                                                <?php echo htmlentities($row['courseName']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <?php }
                    }
                    ?>
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
} else {
  ?>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <link href="assets/css/style.css" rel="stylesheet" />
    </head>

    <body>
        <i class="bi bi-bell"></i>
        <?php include('includes/header.php'); ?>
        <!-- LOGO HEADER END-->
        <?php if ($_SESSION['login'] != "") {
        include('includes/menubar.php');
      }
      ?>
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
                                Request permission
                            </div>
                            <?php
                ?>
                            <font color="red" align="center"><?php echo htmlentities($_SESSION['errmsg']);
                                                  echo htmlentities($_SESSION['errmsg'] = ""); ?></font>
                            <font color="green" align="center"><?php echo htmlentities($_SESSION['msg']);
                                                    echo htmlentities($_SESSION['msg'] = ""); ?></font>
                            <div class="panel-body">
                                <form action="enroll.php" method="post">
                                    <div class="form-group">
                                        <label>Request permission from admin to register again</label>
                                    </div>
                                    <button type="submit" name="sendreq" id="sendreq" class="btn btn-default">send
                                        request</button>
                                    <hr />

                                </form>
                            </div>

                            <?php
                if ($sq != 0) {
                  echo "<script>$('#sendreq').prop('disabled',true);</script>";
                }
                ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    </html>
    <?php
}
  ?>
</body>
<script>
var count = 0;
var no = <?php echo $creditcount; ?>;

function check(checkbox, value) {
    if (checkbox.checked == true) {
        if (count < no) {
            count++;
            var elms = document.getElementById("elective" + value);
            elms.disabled = true;
        } else {
            checkbox.checked = false;
            alert("You can only select " + no + " of courses");
        }
    } else {
        count--;
        var elms = document.getElementById("elective" + value);
        elms.disabled = false;

    }
}
</script>

</html>
<?php include('includes/footer.php'); ?>