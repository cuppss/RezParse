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
    <script src="https://static.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script id="rendered-js">
        $(document).ready(function () {
            $('form input').change(function () {
                $('form p').text(this.files.length + " file(s) selected");
            });
        });
        //# sourceURL=pen.js
    </script>
    <style>
        form{
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -100px;
            margin-left: -250px;
            width: 500px;
            height: 200px;
            border: 4px dashed #6BB386;
        }
        #title{
            position: absolute;
            top: 40%;
            left: 48%;
            margin-top: -100px;
            margin-left: -250px;
        }
        form p{
            width: 100%;
            height: 100%;
            text-align: center;
            line-height: 170px;
            color: #6BB386;
            font-family: Arial;
        }
        form input{
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
        }
        form button{
            margin: 0;
            color: #fff;
            background: #6BB386;
            border: none;
            width: 508px;
            height: 35px;
            margin-top: -20px;
            margin-left: -4px;
            border-radius: 4px;
            border-bottom: 4px solid #6BB386;
            transition: all .2s ease;
            outline: none;
        }
        form button:hover{
            background: #85D3DE;
            color: #0C5645;
        }
        form button:active{
            border:0;
        }

    </style>
</head>
<body>
<nav class="uk-navbar-container uk-padding-right uk-padding-left" uk-navbar>
    <div class="uk-navbar-left">
        <a href="index.php"><img src="Images/logo.png" alt="logo" style="width: 90px; padding-left: 20px" /></a>
        <a class="uk-navbar-item uk-logo" href="index.php">RezParse</a>
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

<div class="uk-section uk-text-center">
    <div class="uk-container-large uk-align-center">
        <div id="title"><h1>Upload Resume to the Database</h1></div>
        <form action="../uploadProcess.php" method="post" enctype="multipart/form-data" >



            <input type="file" multiple name="fileToUpload" id="fileToUpload">

            <p>Drag your files here or <a>click in this area. </a></p>

            <button type="submit">Upload</button>

        </div>
        </form>

    </div>
</div>
</body>
</html>
