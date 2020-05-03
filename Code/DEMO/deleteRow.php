<?php
include "config.php";
require_once ('/var/www/html/imports.php');

$id = (int)$_GET[id];

$result = mysqli_query($link,"SELECT s3_keyName  FROM `resumes` where resume_id = '$id'");
$row = mysqli_fetch_assoc($result);
$keyName = $row['s3_keyName'];

if (empty($keyName)){
    echo 'true';
    mysqli_query($link,"DELETE FROM resumes WHERE resume_id='".$id."'");
    mysqli_close($link);
    header("Location: index.php");

}


//$URL =  $row['link'];



//$pieces = explode("/", $URL);

//$keyName = $pieces[3]. "/" .$pieces[4];

echo "$keyName";
echo  "$id";

// delete from the s3 bucket

//
//
//
$s3->deleteObject([
    'Bucket' => $config['s3-access']['bucket'],
    'Key'    => "$keyName"
]);

echo "Deleted File";

//echo "$keyName";
//print_r($pieces);
//echo "$URL";


// TESTING - check if the extracted string match


//if($keyName1 == $keyName2)
//{
//    echo 'Strings match.';
//} else {
//    echo 'Strings do not match.';
//}


// DELETE RESUME from the database

mysqli_query($link,"DELETE FROM resumes WHERE resume_id='".$id."'");
mysqli_close($link);
header("Location: index.php");























