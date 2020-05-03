<!DOCTYPE html>
<html>
    <head>
        <title>Edit Resume</title>
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
    <div class="uk-section">
        <div class="uk-container uk-container-xsmall">
<?php
	//MySQL CONNECTION
	$connection = mysqli_connect("DATABASE CONNECTION INFORMATION HERE");
	
	//include the imports file for information needed to upload to S3
	require_once __DIR__.'/imports.php'; 
	
	//try to upload the resume to S3
	try{ 
		
		$file = $_FILES["fileToUpload"];
		
		$name = $file['name'];
		
		//VALIDATION OF FILE FORMAT
		$extension = end((explode(".", $name)));
		$valid = FALSE;
		if ($extension == "docx" || $extension == "pdf" || $extension == "doc") {
			$valid = TRUE;
		}
		else {
			echo "Error!  File must be PDF, DOC, or DOCX.";
		}
		
		if ($valid) {
			# file to upload 
			$file_path = $_FILES["fileToUpload"]["tmp_name"];
			//file name to be given to the reusme when uploaded to S3.  This is the same file name as the resume file being uploaded.
			$file_name = pathinfo($name)['basename']; 
		 
			# actual uploading 
			$request_status = $s3->putObject([ 
				'Bucket' => $config['s3-access']['bucket'], 
				'Key' => 'from_php_script/'.$file_name, # 'from_php_script' will be our folder on s3 (this would be automatically created) 
				'Body' => fopen($file_path, 'rb'), # reading the file in the 'binary' mode 
				'ContentType' => $_FILES["fileToUpload"]["type"],
				'ACL' => $config['s3-access']['acl'] 
			]); 

			//CONVERSION TO TXT.  Three conditional statements that execute to convert the file to .txt.  The statement that executes depends on the format of the resume uploaded.
			if ($extension == "docx") {
				$target_dir = "/var/www/html/";

				$temp_name = $_FILES['fileToUpload']['tmp_name'];
				$path_filename_ext = $target_dir."word2Convert.docx";
	 
				move_uploaded_file($temp_name,$path_filename_ext);
				
				exec('python3 word2txt.py');
			}
			//pdf2txt.py is a file created by default when installing pdfminer, a package to enable conversion of pdf files to txt
			elseif ($extension == "pdf") {
				exec('python3 pdf2txt.py -o output.txt '.$file_path);
			}
			elseif ($extension == "doc") {
				$target_dir = "/var/www/html/";

				$temp_name = $_FILES['fileToUpload']['tmp_name'];
				$path_filename_ext = $target_dir."word2Convert.doc";
	 
				move_uploaded_file($temp_name,$path_filename_ext);
			//antiword package is used to convert doc to txt
				exec('antiword word2Convert.doc > output.txt');
			}

			//EXTRACTING NAME, EMAIL, LOCATION(STATE), AND PHONE
			//txt version of the file, which will be used for parsing, is stored in output.txt in the ec2 instance.
			$filename = 'output.txt';
			$file = file_get_contents($filename);
			$lines = file('output.txt');
			
			//delete unnecessary whitespace in the txt version of the resume to facilitate parsing
			shell_exec('python3 deleteWhite.py');
			
			//extract the name by executing the python script
			$name = exec('python3 extractName.py');
			
			//if the name cannot be found, set first and last name to "None"
			if ($name == "None") {
				$fname = "None";
				$lname = "None";
			}
			//if the name is found, split the name into first and last name.  See if the name has a middle name, if it does, put the middle name with the first name
			else {
				$name = explode(" ", trim($name));
				if (count($name) > 2) {
					$fname = $name[0]." ".$name[1];
					$lname = "";
					for ($x = 2; $x < count($name); $x++) {
						$lname .= $name[$x];
						if($x + 1 < count($name)) {
							$lname .= " ";
						}
					}
				}
				else {
					$fname = $name[0];
					$lname = $name[1];
				}
			}
			
			//extract the email address from the file by executing the python script
			$email = exec('python3 extractEmail.py');
			
			//extract the phone number from the file by executing the python script
			$phone = exec('python3 extractPhone.py');
			
			//create an array of Workflow Status to populate the dropdown in the form
			$workList = array('Reviewed', 'Contacted', 'Waiting', 'Pending Offer', 'Interviewed', 'Hired', 'Rejected', 'Save for later');
			
			//FIND STATE IN RESUME
			//list of state abbreviations
			$statesList = array('AL', 'AK', 'AS', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'DC', 'FM', 'FL', 'GA', 'GU', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'MP', 'OH', 'OK', 'OR', 'PW', 'PA', 'PR', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VI', 'VA', 'WA', 'WV', 'WI', 'WY');
			//list of state full names
			$statesLongList = array('Alabama', 'Alaska', 'American Samoa', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Federated States of Micronesia', 'Florida', 'Georgia', 'Guam', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Marshall Islands', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Northern Mariana Islands', 'Ohio', 'Oklahoma', 'Oregon', 'Palau', 'Pennsylvania', 'Puerto Rico', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virgin Islands', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming');
			
			//cycle through the state names and abbreviations to find and extract from the resume
			$state = "";
			$stateFound = FALSE;
			$count = 0;
			while (($stateFound == FALSE) && ($count < count($lines))) {
				$num = 0;
				$current = strval($lines[$count]);
				while (($stateFound == FALSE) && ($num < count($statesList))) {
					if(strpos($current, $statesLongList[$num])) {
						$stateFound = TRUE;
						$state = $num;
					}
					elseif (preg_match("/\b$statesList[$num]\b/",$current)) {
						$stateFound = TRUE;
						$state = $num;
					}
					$num++;
				}
				$count++;
			}
			
			//FUNCTION TO POPULATE SEARCH CRITERIA
			//function takes SQL connection, SQL query to pull search criteria from the database, a keyword that specifies what is being searched for, and the appropriate abbreviation for the search criteria category (e.g. keyword = "certifications"; abbr = "cert").
			function populate($connection, $query, $keyword, $abbr = null) {
				$result = mysqli_query($connection, $query);
				$list = array();
				$counter = 0;
				if (mysqli_num_rows($result) > 0) {
					// output data of each row
					while($row = mysqli_fetch_assoc($result)) {
						//if abbreviations are applicable to the search criteria, add them to the array of items to search for, which will be used later to search in the resume.  Regardless of whether abbreviations are applicable, add the full search criteria name to the array.
						if (array_key_exists($abbr.'_abbreviations', $row)) {
							$row_abbr = $abbr.'_abbreviations';
							$list[] = array("abbreviations"=>$row["{$row_abbr}"], "{$keyword}"=>$row["{$keyword}"]);
						}
						else {
							$list[] = $row["{$keyword}"];
						}
						$counter++;
					}
				} else {
					echo "0 results";
				}
				return $list;
			}
			
			//FUNCTION TO SEARCH IN RESUME
			//function takes the contents of the file, the list of search criteria, and the keyword for the category to search for (e.g. keyword = "certifications").  Function is used to find multiple items in the resume.
			function search($file, $list, $keyword = null) {
				$listString = "";
				$first = true;
				//cycle through the list of search criteria to search for from the resume
				for($x = 0; $x < count($list); $x++) {
					//if abbreviations are applicable to the search criteria, search for both the abbreviations and the full keyword.  If abbreviations are not applicable, just search for the full keyword name
					if (array_key_exists('abbreviations', $list[$x])) {
						$searchWord = $list[$x]["{$keyword}"];
						$searchAbbr = $list[$x]["abbreviations"];
						$searchWord = str_replace('+', '\+', $searchWord);
						$cont = FALSE;
						if ((!empty($searchWord)) && (preg_match("/\b$searchWord/i",$file))) {
							$cont = TRUE;
						}
						if ((!empty($searchAbbr)) && (preg_match("/\b$searchAbbr\b/",$file))) {
							$cont = TRUE;
						}
						if($cont) {
							$add = "";
							if($first == false) {
								$listString = $listString.",";
							}
							if (!empty($list[$x]["abbreviations"])) {
								$add .= $list[$x]["abbreviations"].": ";
							}
							$add .= $list[$x]["{$keyword}"];
							$listString = $listString."$add";
							$first = false;
						}
					}
					else {
						if(stripos($file, $list[$x])) {
							if($first == false) {
								$listString = $listString.",";
							}
							$add = $list[$x];
							$listString = $listString."$add";
							$first = false;
						}
					}
				}
				return $listString;
			}
			
			//Extract Language and Certifications
			//First create the list of languages to search for by pulling out desired programming language names from the database and populating the array
			$lang = populate($connection, "SELECT languages FROM languages_table;", "languages");
			//search the resume for the desired programming languages
			$foundLang = search($file, $lang);
			
			//Create the list of certifications to search for by pulling out desired certification names and applicable abbreviations for the certification names from the database and populating the array
			$cert = populate($connection, "SELECT certifications, cert_abbreviations FROM certifications_table;", "certifications", "cert");
			//search the resume for the certifcations
			$foundCert = search($file, $cert, "certifications");
			
			
			//EXTRACT CLEARANCE
			//Create the list of clearances to search for by pulling out desired clearance level names and applicable abbreviations for the clearance level names from the database and populating the array
			$clear = populate($connection, "SELECT clearances, clear_abbreviations FROM clearanceType_table;", "clearances", "clear");
			//separate the clearances by DoD clearances--Top Secret, Secret, and Confidential--and other clearances, such as Public Trust
			$dodClear = array();
			$otherClear = array();
			foreach ($clear as $c) {
				if ((stripos($c["clearances"],"Secret") !== false) || (stripos($c["clearances"],"Confidential") !== false)) {
					$dodClear[] = $c;
				}
				else {
					$otherClear[] = $c;
				}
			}
			function sorting($a,$b){
				return strlen($b["clearances"])-strlen($a["clearances"]);
			}
			usort($dodClear,'sorting');
			
			//FUNCTION TO FIND THE CLEARANCE
			//function takes the content of the resume, the list of desired clearances, and a string of the clearances already found from the resume
			function findClear($lines, $clear, $curClear) {
				$foundClear = $curClear;
				$clearFound = FALSE;
				$ct = 0;
				//Go through the lines of the resume's contant and search them for the clearance by cycling through the list of clearances until one is found
				while (($clearFound == FALSE) && ($ct < count($lines))) {
					$num = 0;
					$current = strval($lines[$ct]);
					while (($clearFound == FALSE) && ($num < count($clear))) {
						$searchAbbr = "";
						$searchName = "";
						//if the clearance level has a full name, as opposed to just an abbrviation, such as TS/SCI, search the resume for the full name
						if (!empty($clear[$num]["clearances"])) {
							$searchName = $clear[$num]["clearances"];
							if (preg_match("/\b$searchName\b/i",$current)) {
								$clearFound = TRUE;
								if (strlen($foundClear) == 0) {
									$foundClear = $clear[$num]["clearances"];
								}
								else {
									$foundClear .= ",".$clear[$num]["clearances"];
								}
							}
						}
						//if the clearance level has an abbreviation, as opposed to just a full name, such as Secret, search the resume for the abbreviation
						if (!empty($clear[$num]["abbreviations"])) {
							$searchAbbr = $clear[$num]["abbreviations"];
							$searchAbbr = str_replace('/', '\/', $searchAbbr);
							if (preg_match("/\b$searchAbbr\b/",$current)) {
								$clearFound = TRUE;
								if (strlen($foundClear) == 0) {
									$foundClear = $clear[$num]["abbreviations"];
								}
								else {
									$foundClear .= ",".$clear[$num]["abbreviations"];
								}
							}
						}
						
						$num++;
					}
					$ct++;
				}
				return $foundClear;
			}
			
			$foundClear = "";
			//search the resume for DoD clearances
			$foundClear = findClear($lines, $dodClear, $foundClear);
			//search the resume for other types of clearances
			$foundClear = findClear($lines, $otherClear, $foundClear);
			
			
			//Display the form and fill the fields with information extracted from the resume
			if ($fname == "None" || $lname == "None" || $email == null || $phone == "None") {
				echo "NOTE: Some fields could not be parsed.";
			}
			?>
            
            

			<form action="uploadConfirm.php" method="post"> 
              <h1>Resume information for <span id="name" style="color: dodgerblue"><?php 
				if ($fname == "None" && $lname == "None") {
					echo "";
				}
				else {
					echo $fname." ".$lname;
				}					?></span></h1>
            <legend class="uk-legend">FIRST NAME</legend>
            <div class="uk-margin">
                <input class="uk-input" type="text" name="fname" value="<?php echo $fname; ?>"> 
            </div>
		    <legend class="uk-legend">LAST NAME</legend>
            <div class="uk-margin">
                <input class="uk-input" type="text" name="lname" value="<?php echo $lname; ?>"> 
            </div>
            <legend class="uk-legend">EMAIL ADDRESS</legend>
            <div class="uk-margin">
			     <input class="uk-input" type="text" name="email" value="<?php echo $email; ?>"> 
            </div>
            <legend class="uk-legend">PHONE</legend>
                <div class="uk-margin">
			     <input class="uk-input" type="text" name="phone" value="<?php echo $phone; ?>"> 
                </div>
			<br> 
            <legend class="uk-legend">WORKFLOW STATUS</legend>
			<select name="workflow" class="uk-select uk-margin">
			<?php
				for($x = 0; $x < count($workList); $x++) {
					echo '<option value="'.$workList[$x].'" selected>'.$workList[$x].'</option>';
				}
			?>
			</select>
            <legend class="uk-legend">STATE</legend>
			<select name="state" class="uk-select uk-margin" multiple>
			<?php
				for($x = 0; $x < count($statesList); $x++) {
					if ($statesList[$x] == $statesList[$state]) {
						echo '<option value="'.$statesList[$x].'" selected>'.$statesList[$x].'</option>';
					}
					else {
						echo '<option value="'.$statesList[$x].'">'.$statesList[$x].'</option>';
					}
				}
			?>
			</select>
			<br> 
            <legend class="uk-legend">PROGRAMMING LANGUAGES</legend>
			<textarea class="uk-textarea" name="lang" rows="4" cols="50"><?php echo $foundLang; ?></textarea>
			<br>
            <legend class="uk-legend">CERTIFICATIONS</legend>
			<textarea class="uk-textarea" name="cert" rows="4" cols="50"><?php echo $foundCert; ?></textarea>
            <legend class="uk-legend">CLEARANCE TYPE</legend>
			<textarea class="uk-textarea" name="clear" rows="4" cols="50"><?php echo $foundClear; ?></textarea>
			<br>
            <legend class="uk-legend">NOTES</legend>
			<textarea class="uk-textarea" name="notes" rows="4" cols="50"></textarea>
			<br>
			<input type="hidden" name="url" value="<?php echo $request_status["ObjectURL"]; ?>">
			<input type="hidden" name="key" value="<?php echo 'from_php_script/'.$file_name; ?>">
			
            <p uk-margin>
                <input class="uk-button uk-button-primary" style="background-color: #6BB386; color: white" type="submit" value="Submit"> 
            </p>
            </form> 

			<?php
			
			
		}

		
	}catch(Exception $ex){ 
		echo "Error Occurred\n", $ex->getMessage(); 
	} 

	
?>
        </div>
        </div>
    </body>
</html>