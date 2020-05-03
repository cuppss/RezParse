<!DOCTYPE html>
<html>

<head>
    <title>Homepage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#main-table tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" class="uk-input" placeholder="'+title+'" />' );
            } );

            // DataTable
            var table = $('#main-table').DataTable();

            // Apply the search
            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        } );
    </script>
    <script>
        $(document).ready(function() {
            $('#main-table').DataTable();

            $('#main-table_filter').hide(); // Hide default search datatables where example is the ID of table

            $('#realSearch').on('keyup', function() {
                $('#main-table')
                    .DataTable()
                    .search($('#realSearch').val(), false, true)
                    .draw();
            });
        });
    </script>
    <style type="text/css">
        tfoot input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }

        .hidden {
            visibility: hidden
        }
        tfoot {
            display: table-header-group;
        }
    </style>
</head>

<body>
<nav class="uk-navbar-container uk-padding-right uk-padding-left" uk-navbar>
    <div class="uk-navbar-left">
        <a href="index.php"><img src="Images/logo.png" alt="logo" style="width: 90px; padding-left: 20px" /></a>
        <a class="uk-navbar-item uk-logo" href="index.php">RezParse</a>
    </div>
    <div class="uk-navbar-center">
        <div class="uk-navbar-item">
            <form action="javascript:void(0)">
                <input class="uk-input uk-form-width-large"  id= "realSearch" type="text" placeholder="SEARCH DATABASE">
            </form>
        </div>
    </div>
    <div class="uk-navbar-right">
        <div class="uk-navbar-item">
            <a href="tag_management/index.php">MANAGE TAGS</a>
        </div>
        <div class="uk-navbar-nav uk-margin-right uk-margin-top uk-margin-bottom">
            <a class="uk-button uk-button-default uk-button-large" href="Upload.php" style="background-color: #6BB386; color: white ">
                <span class="uk-icon uk-margin-small-right" uk-icon="icon: upload"></span> UPLOAD RESUME
            </a>
        </div>
    </div>
</nav>
<div class="uk-section uk-section-xsmall" style="background-color: #6BB386;">
    <div class="uk-container uk-container-xlarge">

    </div>
</div>
<?php include "config.php" ?>
<?php $result = mysqli_query($link,"SELECT * FROM `resumes` "); ?>
<div class="uk-container uk-container-xlarge uk-margin">

    <div class="uk-flex uk-flex-column uk-width uk-overflow-auto">
        <table class="uk-table uk-table-striped" id="main-table">
            <tfoot>
            <tr >
                <th>First</th>
                <th>Last</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Languages</th>
                <th>Certification</th>

                <th>Clearance</th>
                <th>Status</th>
                <th>Date</th>
                <th class="hidden"></th>

            </tr>
            </tfoot>
            <thead>
            <tr>

                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Location</th>
                <th>languages</th>
                <th>Certification</th>

                <th>Clearance Type</th>
                <th>Workflow Status</th>
                <th>Date Added</th>
                <th>Actions</th>

            </tr>
            </thead>
            <tbody>


            <?php
            while($row = mysqli_fetch_array($result))
            {
                echo "<tr>";
                echo "<td>" . $row['firstname'] . "</td>";
                echo "<td>" . $row['lastname'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['location'] . "</td>";
                echo "<td>" . $row['languages'] . "</td>";
                echo "<td>" . $row['certifications'] . "</td>";

                echo "<td>" . $row['clearanceType'] . "</td>";
                echo "<td>" . $row['workflow'] . "</td>";
                echo "<td>" . $row['dateAdded'] . "</td>";
                echo "<td><a href=\"https://docs.google.com/viewer?url=$row[link]&embedded=true\" target=\"_blank\">View</a>&nbsp;&nbsp;<a href=\"viewResume2.php?id=".$row['resume_id']."\">Edit</a>&nbsp;&nbsp;<a href=\"deleteRow.php?id=".$row['resume_id']."\" onclick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";
                echo "</tr>";
            }
            ?>


            </tbody>
        </table>

    <?php mysqli_close($link); ?>
    </div>
</div>




</body>

</html>
