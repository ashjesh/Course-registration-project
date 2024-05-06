<?php
session_start();
include('includes/config.php');
if (strlen($_SESSION['login']) == "") {
  header('location:index.php');
}

if (isset($_POST['submit'])) {
  $name = $_POST["name"];
  $title = $_POST["title"];
  $type = $_POST["type"];
  $platform = $_POST["platform"];
  $photo = $_FILES["photo"]["name"];
  move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/" . $_FILES["photo"]["name"]);
  $img = file_get_contents(
    'uploads/' . $photo
  );
  $data = base64_encode($img);
  $ret = mysqli_query($bd, "insert into  noncgpa(name,title,type,platform,proof) values ('" . $_SESSION['login'] . "', '".$title."','".$type."','".$platform."','$data')");
  if ($ret) {
    $_SESSION['msg'] = "Tutor Record updated Successfully !!";
  } else {
    $_SESSION['msg'] = "Error : Tutor Record not update";
  }
}

if (isset($_GET["id"])) {
  $sql = mysqli_query($bd, "delete from noncgpa where id = " . $_GET["id"] . " ");
  if ($sql) {
    $_SESSION["msg"] = "Certificate deleted sucessfully";
  } else {
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
  <title>Non CGPA upload</title>
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

  <div class="content-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-7">
          <h1 class="page-head-line">Certificates</h1>
        </div>
      </div>
      <div class="row">
        
        <div class="col-md-5">
          <div class="panel panel-default" style="position: fixed-top;">
            <div class="panel-heading">
              Add Certificates
            </div>
            <div class="panel-body">
            <font color="green" align="center"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg'] = ""); ?></font>
              <form name="dept" action="non-cgpa.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="batch">Roll No</label>
                  <input type="text" class="form-control" id="name" name="name" readonly value="<?php echo htmlentities($_SESSION['login']); ?>" readonly />
                </div>
                <div class="form-group">
                  <label for="batch">Title</label>
                  <input type="text" class="form-control" id="title" name="title" required />
                </div>
                <div class="form-group">
                  <label for="batch">Platform</label>
                  <input type="text" class="form-control" id="platform" name="platform" required />
                </div>
                <div class="form-group">
                  <label for="batch">Type</label>
                  <select class="form-select" aria-label="Default select example" name="type" id="type" required="required">
                    <option value="Non Cgpa (Technical)">Non Cgpa (Technical)</option>
                    <option value="Non Cgpa (Non Technical)">Non Cgpa (Non Technical)</option>
                    <option value="Credit Transfer">Credit Transfer</option>
                    <option value="others">others</option>
                  </select>
                </div>


                <div class="form-group">
                  <label for="Pincode">Upload New Certificate</label>
                  <input type="file" class="form-control" id="photo" name="photo" value="<?php echo htmlentities($row['proof']); ?>" />
                </div>
                <button type="submit" name="submit" id="submit" class="btn btn-default">Update</button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <div class="panel panel-default">
            <div class="panel-heading">
              My Certificates
            </div>
            <div class="panel-body" style="align-items:center;">
                <?php $sql1 = mysqli_query($bd, "select * from noncgpa where name= '" . $_SESSION['login'] . "' ");
                while ($row = mysqli_fetch_assoc($sql1)) { ?>
                    <div class="panel-body">
                      <div class="form-group">
                        <label for="Pincode"><?php echo $row["type"].":".$row["platform"]." - ".$row["title"];?> - Proof:</label><!-- <a href="non-cgpa.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger">delete</a> -->
                        <label>Status: <?php echo $row["status"]; ?></label>
                        <?php if($row["status"] == "Completed"){
                          $sql2 = mysqli_fetch_assoc(mysqli_query($bd,"Select * from course where id = ".$row["course"]."; "));
                          ?>
                            <label><?php echo "Credit transferred course Course: ".$sql2["courseCode"] .", Semester:".$row["semester"]; ?></label>
                        <?php } ?>
                        <?php if ($row['proof'] == "") { ?>
                          <img src="studentphoto/noimage.png" width="200" height="200"><?php } else { ?>
                          <!-- <img src="data:image/jpeg;base64,<?php echo $row['proof']; ?>" width="200" height="200"> -->
                          <embed src="data:application/pdf;base64,<?php echo $row['proof']; ?>" type="application/pdf" height="300px" width="500">
                        <?php } ?>
                      </div>
                      <hr>
                  <?php } ?>
                  </div>
            </div>
          </div>
        </div>
      </div>

    </div>





  </div>
  </div>
  <?php include('includes/footer.php'); ?>
  <script src="assets/js/jquery-1.11.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>


</body>

</html>