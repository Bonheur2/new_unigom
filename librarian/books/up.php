<?php

if (isset($_POST['image'])) {
$data = $_POST['image'];
$file_name=$_POST['nameoffile'];
$image_array_1 = explode(";", $data);
$image_array_2 = explode(",", $image_array_1[1]);
$data = base64_decode($image_array_2[1]);

// Specify the directory path where you want to store the image
$directory = 'uploads/';

// Generate a unique name for the image using the current timestamp
$image_name = time() . '.png';

$file_path = $directory . $file_name;

// Write the decoded image data to the file
file_put_contents($file_path, $data);

// Construct the image URL
$image_url = 'books/' . $file_path;

echo $image_url;
     
}
?>