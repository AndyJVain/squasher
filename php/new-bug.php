<html>
<body>



<?php

	$product = $_POST["product"];
	$title = $_POST["title"];
	$bugType = $_POST["bug-type"];
	$rep = $_POST["rep"];
	$description = $_POST["description"];

	//$username = $_POST["username"];
	//todo:

	//need to be given: REPORTER_EMAIL
	//Needs to be from session.
	$reporterEmail = 'ccarraher@scu.edu';

	//by default, will assign to tester (pedro)
	//Will setup a trigger to handle auto-assignment on DB side
	$defaultAssigned = 'connor-carraher';

	$defaultState = "PENDING BUG VERIFICATION";

	if($_SERVER["REQUEST_METHOD"] == "POST") {

	$conn = oci_connect('psanchez','a47k7S4QOi', '//dbserver.engr.scu.edu/db11g' );
		if(!$conn) {
		  print "<br> connection failed:";
		  exit;
		}

	$getReportNumberQuery = "select MAX(REPORT_NUMBER) from SQUASHER_COUNTER";
	$getDateQuery = "select SYSDATE from DUAL";

	//Get current ReportNumber
	$query = oci_parse($conn, $getReportNumberQuery);
	oci_execute($query);
	$row_reportNumber = oci_fetch_array($query, OCI_BOTH);
	$reportNumber = $row_reportNumber[0];

	//update ReportNumber
	$updateReportNumberQuery = "insert into SQUASHER_COUNTER values($reportNumber+1)";
	$query = oci_parse($conn, $updateReportNumberQuery);
	oci_execute($query);

	//Get SYSDATE
	$query = oci_parse($conn, $getDateQuery);
	oci_execute($query);
	$row_date = oci_fetch_array($query, OCI_BOTH);
	$sysDate = $row_date[0];

	//setup query for new report
	$newReportQuery = "insert into SQUASHER_REPORTS values('$reportNumber','$product','$title','$bugType','$rep','$defaultAssigned','$defaultState','$reporterEmail','$sysDate','$description')";
	echo ($newReportQuery);
	$query = oci_parse($conn, $newReportQuery);
	oci_execute($query);


	OCILogoff($conn);

	header("Location: ../html/home.html");
	}
?>




</body>
</html>
