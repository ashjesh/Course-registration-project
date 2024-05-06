<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['login']) == "") 
{
  header('location:index.php');
}else{

//$count = mysqli_num_rows(mysqli_query($bd,"select * from students where batch = '".$_SESSION["batch"]."' and semester = ".$_SESSION["semester"]." and department = '".$_SESSION["department"]."' "));

// $sql = mysqli_num_rows(mysqli_query($bd,"select * from students where batch = '".$_SESSION["batch"]."' and semester = ".$_SESSION["semester"]." and department = '".$_SESSION["department"]."' "));
 // $sql2 = mysqli_num_rows(mysqli_query($bd, "select * from courseenrolls where department = '".$_SESSION["department"]."' and batch = '".$_SESSION["batch"]."' and semester = ".$_SESSION["semester"]." group by studentRegno "));
  //if($sql != $sql2){
   // header('location:enroll.php?msg=wait');
//}

function staff($bd, $staffid)
{
  $sql = mysqli_fetch_assoc(mysqli_query($bd, "select tutorname from tutors where username = '" . $staffid . "';"));
  $staffname = $sql["tutorname"];
  return $staffname;
}

function labstaff($bd, $staff, $staff1)
{
      $sql = mysqli_fetch_assoc(mysqli_query($bd, "select tutorname from tutors where username = '".$staff."'; "));
      $labstaff = $sql["tutorname"];

      $sql1 = mysqli_fetch_assoc(mysqli_query($bd, "select tutorname from tutors where username = '".$staff1."'; "));
      $labstaff1 = $sql1["tutorname"];

      
      $staffs = $labstaff." - ".$labstaff1;
      return $staffs;
    }

  //function staffcheck($val){
   // $off = explode("+",$val);
   // echo '<script>alert("its working '.$off[0].' + '.$off[1].'");</script>';	
  //}

if (isset($_POST['submit'])) {
  $staff = $_POST["staffs"];
  foreach ($staff as $a) {
    $s = explode("+", $a);
    $sql = mysqli_query($bd, "update courseenrolls set staff = '" . $s[0] . "' where studentRegno = " . $_SESSION["login"] . " and semester = " . $_SESSION["semester"] . " and course = " . $s[1] . " ");
    if ($sql > 0) {
      $_SESSION["msg"] .= $s[0] . " registered. ";
    } else {
      $_SESSION["msg"] .= $s[0] . " not registered. ";
    }
  }

  // Retrieve the selected staff/course pairs as an array
  $staff = $_POST["labstaffs"];
  
  // Loop through each staff/course pair
  foreach ($staff as $selectedValue) {
    // Split the selected option value into an array of three values
    $values = explode('|', $selectedValue);
  
    // Retrieve the staff name, course name, and staff ID from the array
    $staff = $values[0];
    $course = $values[1];
    $staff1 = $values[2];
  
    // Construct the SQL query to update the staff field in the courseenrolls table
    $query = "UPDATE courseenrolls SET staff = '{$staff}', staff1 = '{$staff1}' WHERE studentRegno = {$_SESSION['login']} AND semester = {$_SESSION['semester']} AND course = '{$course}' ";
  
    // Execute the query and check if it was successful
    if (mysqli_query($bd, $query)) {
      $_SESSION["msg"] .= "{$staff} and {$staff1} registered. ";
    } else {
      $_SESSION["msg"] .= "{$staff} and {$staff1} not registered. ";
    }
  }  

}



?>

  <!DOCTYPE html>
  <html xmlns="http://www.w3.org/1999/xhtml">
  <script>
      function staffAvailability(value) {
       
        $("#loaderIcon").show();
        jQuery.ajax({
          url: "check_availability.php",
          data: 'cid=' + value, 
          type: "POST",
          success: function(data){
            $("#user-availability-status1").html(data);
            $("#loaderIcon").hide();
          },
          error:function (){}
        });
      }
      function labstaffAvailability(value) {
        $("#loaderIcon").show();
        jQuery.ajax({

          url: "labstaff_availability.php",
          data: 'sid=' + value,
          type: "POST",
          success: function(data) {
            $("#user-availability-status1").html(data);
            $("#loaderIcon").hide();
          },
          error:function (){}
        });
      }
    </script>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Staff Selection</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    
  </head>

  <body>
    <?php include('includes/header.php'); ?>

    <?php if ($_SESSION['login'] != "") {
      include('includes/menubar.php');
    }
    ?>

<?php 
              $sql = mysqli_num_rows(mysqli_query($bd, "select * from courseenrolls where studentRegno = " . $_SESSION["login"] . " and semester = " . $_SESSION["semester"] . " and batch = '" . $_SESSION["batch"] . "' "));
              if($sql!=0){ 
               $flag = mysqli_num_rows(mysqli_query($bd, "select * from courseenrolls where studentRegno = " . $_SESSION["login"] . " and  semester = " . $_SESSION["semester"] . " and staff <> ' ' and staff1 <> ' '; "));
           if ($flag == 0){
              ?>

    <div class="content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h1 class="page-head-line">Staff Registration </h1>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Staff Registration
              </div>
              <font color="green" align="center">
                <?php echo htmlentities($_SESSION['msg']); ?>
                <?php echo htmlentities($_SESSION['msg'] = ""); ?>
              </font>

              
              <div class="panel-body">
                <form action="staff.php" name="dept" method="post">
                  <div class="form-group">
                    <?php
                    $sql = mysqli_query($bd, "select * from courseenrolls a inner join course b on a.course = b.id where a.studentRegno = " . $_SESSION['login'] . " and a.department = '".$_SESSION['department']."' and a.batch = '".$_SESSION['batch']."' and a.semester = " . $_SESSION['semester'] . " and b.type != 'CoreLab' ");
                    if (mysqli_num_rows($sql) > 0) {
                      while ($row = mysqli_fetch_assoc($sql)) {

                        ?>
                        <label for="Course">
                          <?php echo $row["courseCode"] . " : " . $row["courseName"];  ?>
                        </label>
                        <select class="form-select" name="staffs[]" id="staffs[]"
                          onchange="staffAvailability(this.value)" required="required">
                          <option value="<?php echo htmlentities($row['course']); ?>"><?php echo staff($bd, $row['staff1']); ?></option>
                          <?php
                          if (!empty($row["staff2"])) { ?>
                            <option value="<?php echo htmlentities($row['course']); ?>"><?php echo staff($bd, $row['staff2']); ?></option>
                          <?php }
                          if (!empty($row["staff3"])) { ?>
                            <option value="<?php echo htmlentities($row['course']); ?>"><?php echo staff($bd, $row['staff3']); ?></option>
                          <?php } ?>
                          <span id="user-availability-status1" style="font-size:12px;">
                        </select>
                      <?php }

                      }
                      $sql = mysqli_query($bd, "select * from courseenrolls a inner join course b on a.course = b.id where a.studentRegno = " . $_SESSION['login'] . " and a.semester = " . $_SESSION['semester'] . " and a.batch = '".$_SESSION['batch']."'  and b.type= 'CoreLab' ");
                      while ($row = mysqli_fetch_assoc($sql)) {
                      ?>
                      <label for="Course">
                          <?php echo $row["courseCode"] . " : " . $row["courseName"]; ?>
                        </label>
                        <select class="form-select" name="labstaffs[]" id="labstaffs[]" onchange="labstaffAvailability(this.value, <?php $row['id']; ?>)" required="required">
                        <option value="<?php echo htmlentities($row['staff1'] . '|' . $row['course'] . '|' . $row['staff2']); ?>"><?php echo labstaff($bd, $row['staff1'] , $row['staff2']); ?></option>
                        <?php if (!empty($row["staff3"]) && !empty($row["staff4"])) { ?>
                        <option value="<?php echo htmlentities($row['staff3'] . '|' . $row['course'] . '|' . $row['staff4']); ?>"><?php echo labstaff($bd, $row['staff3'] , $row['staff4']); ?></option>
                        <?php }
                        if (!empty($row["staff5"]) && !empty($row["staff6"])) { ?>
                        <option value="<?php echo htmlentities($row['staff5'] . '|' . $row['course'] . '|' . $row['staff6']); ?>"><?php echo labstaff($bd, $row['staff5'] , $row['staff6']); ?></option>
                        <?php } ?>
                        </select>

                  <?php } ?>

                  <select name="staff[]" id="staff[]" onchange="staffAvailability(this.value)" required="required">
                          <option value="hi">hello</option>
                          <option value="hi1">hello1</option>
                          <option value="hi2">hello2</option>
                          <option value="hi3">hello3</option>
                </select>
                    </div>

                    <button onload="reset()" type="submit" name="submit" id="submit" class="btn btn-default">Submit</button>
                        </form>
                  <?php 
                    
                        } else{ ?>
                          
                          <div class="content-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Staff Selection Status
              </div>
              
              <font color="red" align="center">
                <?php echo htmlentities($_SESSION['errmsg']);
                echo htmlentities($_SESSION['errmsg'] = ""); ?>
              </font>
              <font color="green" align="center">
                <?php echo htmlentities($_SESSION['msg']);
                echo htmlentities($_SESSION['msg'] = ""); ?>
              </font>
              <div class="panel-body">
                <div class="form-group">
                  <label>Staffs already selected for this semester</label>
                </div>
                <hr />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
                      <?php  
                       }
                      }
                       else {
                      ?>


                    <div class="panel-body">
                      <div class="form-group">
                        <label>Enroll courses to select staffs</label>
                      </div>
                      <hr />
                    </div>

                    
                    <?php
   } ?>
              </div>
            </div>
          </div>

        </div>

      </div>
<script>
    var a = document.querySelectorAll('select');
        for( i=0; i<a.length;i++){
          a[i].selectedIndex = -1;
        }
  </script>
  



    </div>
    </div>



    </div>
    </div>
    <?php include('includes/footer.php'); ?>
    <script src="assets/js/jquery-1.11.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    

  </body>

  </html>

<?php } ?>