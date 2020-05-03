<?php
if (isset($_POST['alpha'])) {


    $alpha = $_POST['alpha'];
    $beta = $_POST['beta'];

    //this if statement block catches possible mistakes in the entering of data. currently only checking
    //the tag value, not the abbreviation
    if (is_null($alpha)) {
        //use javascript for popup output
        echo '<script>alert("That was a null string, please enter proper data for the certification value")</script>';
        exit;
    } elseif ($alpha == "") {
        //use javascript for popup output
        echo '<script>alert("That was an empty string, please enter proper data for the certification value")</script>';
        exit;
    }

    //insert user input into the database
    $sql = "INSERT INTO certifications_table (certifications, cert_abbreviations) VALUES ('$alpha', '$beta')";

    //use mysqli_query to execute sql string.
    if (mysqli_query($link, $sql)) {
        echo "New record created successfully";
        header('Location: ../certification/certification.php'); //reload the page

    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($link);
    }


}
