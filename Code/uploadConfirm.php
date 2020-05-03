<!DOCTYPE html>
<html>
    <head>
        <title>Upload a Resume</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
       <!-- UIkit CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

        <!-- UIkit JS -->
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>
    </head>
    <body>
        <nav class="uk-navbar-container uk-padding-right uk-padding-left" uk-navbar>
        <div class="uk-navbar-left">
            <a href="DEMO/index.php"><img src="DEMO/Images/logo.png" alt="logo" style="width: 90px; padding-left: 20px" /></a>
            <a class="uk-navbar-item uk-logo" href="DEMO/index.php">RezParse</a>
        </div>
        <div class="uk-navbar-right">
            <div class="uk-navbar-item">
                <a href="DEMO/tag_management/index.php">MANAGE TAGS</a>
            </div>
            <div class="uk-navbar-nav uk-margin-right uk-margin-top uk-margin-bottom">
                <a class="uk-button uk-button-default uk-button-large" href="DEMO/Upload.php" style="background-color: #6BB386; color: white ">
                    <span class="uk-icon uk-margin-small-right" uk-icon="icon: upload"></span> UPLOAD RESUME
                </a>
            </div>
        </div>
    </nav>
    <div class="uk-section uk-text-center">
        <div class="uk-container-large uk-align-center">
<?php
//MYSQL CONNECTION
$connection = mysqli_connect("DATABASE CONNECTION INFORMATION HERE");

//MySQL UPLOAD
//build the query to upload the information into the appropriate fields in the "resumes" table
$query = 'INSERT INTO resumes(firstname, lastname, email, phone, workflow, location, languages, certifications, clearanceType, dateAdded, notes, link, s3_keyName) VALUES ("'.$_POST["fname"].'", "'.$_POST["lname"].'", "'.$_POST["email"].'", "'.$_POST["phone"].'", "'.$_POST["workflow"].'", "'.$_POST["state"].'", "'.$_POST["lang"].'", "'.$_POST["cert"].'", "'.$_POST["clear"].'", CURDATE(), "'.$_POST["notes"].'", "'.$_POST["url"].'", "'.$_POST["key"].'");';
$result = mysqli_query($connection, $query);

//Show messages demonstrating whether the upload to the database was successful or not
if ($result) {
	echo '<img src="success-green.svg"><br><div><h1 style="padding-top: 10px">' . "Hooray! The resume was successfully uploaded." . "</div></h1>";
}
else {
	echo  ' <img src="unsuccessful-green.svg"><br><div><h1 style="padding-top: 10px">' . "Oh dear, the resume could not be uploaded. Please try again." . "</div></h1>";
}


?>
<a href="DEMO/Upload.php" class="four uk-button uk-button-default uk-button-large" style="background-color: #6BB386; color: white">Upload A Resume</a>
        </div>
        </div>
    </body>
</html>