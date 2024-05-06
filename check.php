<?php 
  
// Get the image and convert into string
$img = file_get_contents(
'studentphoto/joker.jpg');
  
// Encode the image string data into base64
$data = base64_encode($img);
echo "<img src='data:image/jpeg;base64, $data' hight='50px' width='50px'/>";
?>