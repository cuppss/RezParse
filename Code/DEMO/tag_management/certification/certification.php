<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certification Tags</title>

    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>

    <!-- Javascript for the form confirmation -->
    <script type="text/javascript">
        //function to confirm submission
        function confSubmit(form) {
            if (confirm("Are you sure you want to enter [data] into the database?")) {
                form.submit();
            } else {
                alert("You decided to not enter the data");
            }
        }
    </script>
</head>
<body>


<nav class="uk-navbar-container uk-padding-right uk-padding-left" uk-navbar>
    <div class="uk-navbar-left">
        <a href="../../index.php"><img src="../../Images/logo.png" alt="logo" style="width: 90px; padding-left: 20px" /></a>
        <a class="uk-navbar-item uk-logo" href="../index.php">RezParse</a>
    </div>

    <div class="uk-navbar-right">
        <div class="uk-navbar-item">
            <a href="../index.php">MANAGE TAGS</a>
        </div>
        <div class="uk-navbar-nav uk-margin-right uk-margin-top uk-margin-bottom">
            <a class="uk-button uk-button-default uk-button-large" href="../../Upload.php" style="background-color: #6bb386; color: white ">
                <span class="uk-icon uk-margin-small-right" uk-icon="icon: upload"></span> UPLOAD RESUME
            </a>
        </div>
    </div>
</nav>


<div class="uk-container uk-container-xlarge uk-margin">
  <a href="../index.php">Back</a>
<h1>Certification Tags</h1>
<!-- initialize the database connection -->
<?php include "../config.php" ?>

<!-- insert tags into the database table using POST form-->
<form action="../certification/certification.php" method="post">
    <!-- text input from user -->
    Input a Tag Value: <input type="text" name="alpha" class="uk-input uk-margin">
    Input an Abbreviation (If Applicable) : <input type="text" name="beta" class="uk-input">
    <!-- for confSubmit() see the script at the top of the page -->
    <input type="button" onClick="confSubmit(this.form);" value="submit" class="uk-button uk-button-default uk-margin">
</form>

    <!-- view the tag table -->
    <?php include "../certification/view_certification.php" ?>

<!-- insert into the language tag table -->
<?php include "../certification/insert_certification.php" ?>

<!-- return to index -->

</div>

</body>
</html>
