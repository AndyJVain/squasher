

CODE TO GET A REPORT AND INFO FROM A BUG_ID:
  select * from squasher_reports where BUG_ID = "GIVE ME THE BUG_ID FROM SOMEHOW"


CODE TO GET STATE FROM BUG ID
  select state from squasher_reports where BUG_ID = "Give me the bug id"

  modals dont change
  but they affect state differently based on past/current state


get role
select role from squasher_user where user = $_SESSION['username'];


if theyre a reporter:
  search for any reports that they submitted
  select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where REPORTER_USERNAME = $_SESSION['username'];


els3 theyre not a reporter
  search for any reports assigned to them

  select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where ASSIGNED = $_SESSION['username'];



-----


--change state to "BUG VERIFICATION FAILED"
$queryString = "update squasher_reports set state = 'BUG VERIFICATION FAILED' where bug_id = $_GET['bug_id']";

--change state to PENDING DEVELOPER ASSIGNMENT
$queryString = "update squasher_reports set state = 'PENDING DEVELOPER ASSIGNMENT' where bug_id = $_GET['bug_id']"

--change state to PENDING FIX VERIFICATION
$queryString = "update squasher_reports set state = 'PENDING FIX VERIFICATION' where bug_id = $_GET['bug_id']"

--change state to DONE
$queryString = "update squasher_reports set state = 'DONE' where bug_id = $_GET['bug_id']"

$query = oci_parse($conn, $queryString);
oci_execute($query);
