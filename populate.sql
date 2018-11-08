







get role
select role from squasher_user where user = $_SESSION['username'];



if theyre a reporter:
  search for any reports that they submitted
  select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where REPORTER_USERNAME = $_SESSION['username'];


els3 theyre not a reporter
  search for any reports assigned to them

  select PRODUCT, TITLE, BUG_ID, STATE, REPORT_DATE from squasher_reports where ASSIGNED = $_SESSION['username'];
