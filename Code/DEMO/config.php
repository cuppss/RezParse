<?php
//identify the mySQL credentials --> create a variable that uses the mysqli connection method
$link = mysqli_connect("DATABASE CONNECTION INFORMATION HERE");

//if the $link variable is unable to connect then output errors
if (!$link) {
    echo "Error: Unable to connect to MySQL.\n" . PHP_EOL;
    echo "Debugging error:<br/> " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error:<br/> " . mysqli_connect_error() . PHP_EOL;
    exit;
}
//otherwise, if the $link variable is able to connect then output success message
//echo "Success: A proper connection to MySQL was made<br/>" . PHP_EOL;
//echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL . "<br/>";
?>
