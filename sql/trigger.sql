
--handles updating the number of assigned bugs a user has ON UPDATE
CREATE or REPLACE TRIGGER update_numAssigned
    AFTER UPDATE on squasher_reports
    FOR EACH ROW
DECLARE
    v_num_old NUMBER;
    v_num_new NUMBER;
BEGIN

    select NUM_ASSIGNED into v_num_new from squasher_user where USERNAME = :new.ASSIGNED;
    select NUM_ASSIGNED into v_num_old from squasher_user where USERNAME = :old.ASSIGNED;

    update squasher_user set NUM_ASSIGNED = (v_num_old - 1) where USERNAME = :old.ASSIGNED;
    update squasher_user set NUM_ASSIGNED = (v_num_new + 1) where USERNAME = :new.ASSIGNED;


END;
/
show errors;

--handles updating the number of assigned bugs a user has ON INSERTION
CREATE or REPLACE TRIGGER insert_numAssigned
    AFTER INSERT on squasher_reports
    FOR EACH ROW
DECLARE
    v_num_new NUMBER;
BEGIN


    select NUM_ASSIGNED into v_num_new from squasher_user where USERNAME = :new.ASSIGNED;
    update squasher_user set NUM_ASSIGNED = (v_num_new+1) where USERNAME = :new.ASSIGNED;

END;
/
show errors;

--handles assigning bugs for verification ON INSERT
CREATE or REPLACE TRIGGER bugAssignment_insert
  AFTER INSERT on squasher_reports
  FOR EACH ROW
  WHEN(new.ASSIGNED = 'assigner')
DECLARE
  v_minAssigned NUMBER;
  v_Assignment VARCHAR(50);
BEGIN
  select MIN(NUM_ASSIGNED) into v_minAssigned from squasher_user where ROLE = 'TESTER';

  select username into v_Assignment from squasher_user where ROLE = 'TESTER' and NUM_ASSIGNED = v_minAssigned and ROWNUM <= 1 and USERNAME != 'assigned';

  update squasher_reports set ASSIGNED = v_Assignment where bug_id = :new.bug_id;
END;
/
show errors;
