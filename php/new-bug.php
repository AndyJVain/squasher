<html>
<body>



<?php echo $_POST["product"]; ?><br>
<?php echo $_POST["title"]; ?><br>
<?php echo $_POST["bug-type"]; ?><br>
<?php echo $_POST["rep"]; ?><br>
<?php echo $_POST["description"]; ?><br>

<?php
	//todo:
	//who gets assigned a report by default? could setup a trigger to handle it on DB side?
	//What are the statuses?
	//need to be given: REPORTER_EMAIL
	$reporterEmail = 'ccarraher@scu.edu';
	$defaultAssigned = 'connor-carraher';

	if($_SERVER["REQUEST_METHOD"] == "POST") {

	$conn = oci_connect('psanchez','a47k7S4QOi', '//dbserver.engr.scu.edu/db11g' );
		if(!$conn) {
		  print "<br> connection failed:";
		  exit;
		}
	}

	$getReportNumberQuery = "select REPORT_NUMBER from SQUASHER_COUNTER";
	$getDateQuery = "select SYSDATE from DUAL";

	//Get current ReportNumber
	$query = oci_parse($conn, $getReportNumberQuery);
	oci_execute($query);
	$row_reportNumber = oci_fetch_array($query, OCI_BOTH);
	$reportNumber = row_reportNumber[0] + 1;

	//Get SYSDATE
	$query = oci_parse($conn, $getDateQuery);
	oci_execute($query);
	$row_date = oci_fetch_array($query, OCI_BOTH);
	$sysDate = row_date[0];

	//setup query for new report
	$newReportQuery = "insert into SQUASHER_REPORTS values($reportNumber,$product,$title,$bug-type,$rep,$defaultAssigned,'PENDING BUG VERIFICATION',$reporterEmail,$sysDate)";
	$query = oci_parse($conn, $newReportQuery);
	oci_execute($query);
	OCILogoff($conn);

?>




</body>
</html>
