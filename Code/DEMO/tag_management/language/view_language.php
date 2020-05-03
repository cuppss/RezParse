<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tag Management</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css"/>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>
</head>
<body>

<?php
include_once "../config.php";
//sql query to retrieve tag_id and tag_name from the SQL database
$sql = "SELECT * FROM languages_table ORDER BY lang_id DESC";

//store the result of the query in a variable
$result = $link->query($sql);

//set the table headers up
echo "<table class=\"uk-table uk-table-striped\">";
echo "<tr>";
echo "<th>Tag Name</th>";
echo "<th>Actions</th>";
echo "</tr>";

if ($result->num_rows > 0) { //if the number of rows in the array var $result is > 0 then continue
    //output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["languages"] . "</td>";
        echo "<td><a href='../language/delete_language.php?id=" . $row['lang_id'] . "' onclick=\"return confirm('Are you sure you want to delete?')\">delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else { //if the number of rows in the array var $result is <0 then print "This table is empty"
    echo "<div id='empty_db_message'> There are no tags in the database </div>";

}

?>
</body>
</html>
