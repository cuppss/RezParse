<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tag Management</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/css/uikit.min.css" />

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.3.1/dist/js/uikit-icons.min.js"></script>
</head>
<body>
<nav class="uk-navbar-container uk-padding-right uk-padding-left" uk-navbar>
    <div class="uk-navbar-left">
        <a href="../index.php"><img src="../Images/logo.png" alt="logo" style="width: 90px; padding-left: 20px" /></a>
        <a class="uk-navbar-item uk-logo" href="../index.php">RezParse</a>
    </div>

    <div class="uk-navbar-right">
        <div class="uk-navbar-item">
            <a href="index.php">MANAGE TAGS</a>
        </div>
        <div class="uk-navbar-nav uk-margin-right uk-margin-top uk-margin-bottom">
            <a class="uk-button uk-button-default uk-button-large" href="../Upload.php" style="background-color: #6bb386; color: white ">
                <span class="uk-icon uk-margin-small-right" uk-icon="icon: upload"></span> UPLOAD RESUME
            </a>
        </div>
    </div>
</nav>

<div class="uk-container uk-position-center uk-container-xlarge uk-margin">
    <h1>Tag Management</h1>
    <div data-uk-button-radio>
        <button class="uk-button"><a href="certification/certification.php">Certification Tags</a></button>
        <button class="uk-button"><a href="language/language.php">Languages Tags</a></button>
        <button class="uk-button"><a href="clearance/clearance.php">Clearance Type Tags</a></button>
        <button class="uk-button"><a href="../index.php">Back</a></button>
    </div>

</div>

</body>
</html>
