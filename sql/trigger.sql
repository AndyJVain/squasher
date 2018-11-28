
--handles updating the number of assigned bugs a user has ON UPDATE
CREATE or REPLACE TRIGGER update_numAssigned
    BEFORE UPDATE on squasher_reports
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
    BEFORE INSERT on squasher_reports
    FOR EACH ROW
DECLARE
    v_num_new NUMBER;
BEGIN


    select NUM_ASSIGNED into v_num_new from squasher_user where USERNAME = :new.ASSIGNED;
    update squasher_user set NUM_ASSIGNED = (v_num_new+1) where USERNAME = :new.ASSIGNED;

END;
/
show errors;
