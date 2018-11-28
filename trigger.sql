

CREATE OR REPLACE  TRIGGER trigger_numAssigned
BEFORE
INSERT OR UPDATE
--OF ASSIGNED --colname
ON squasher_reports
REFERENCING OLD AS o NEW AS n
FOR EACH ROW
WHEN (o.ASSIGNED != n.ASSIGNED) --we dont want the cases where person assigned has not changed
DECLARE
   v_num_old NUMBER;
   v_num_new NUMBER;
BEGIN
   --Executable-statements
   --get old.person's number of bugs assigned to them
   select NUM_ASSIGNED into v_num_old from squasher_user where USERNAME = o.ASSIGNED;

   --get new.person's number of bugs assigned to them
   select NUM_ASSIGNED into v_num_new from squasher_user where USERNAME = n.ASSIGNED;

   --old person now has one less bug assigned to them
   v_num_old := v_num_old - 1;
   update squasher_user set NUM_ASSIGNED = (v_num_old) where USERNAME = o.ASSIGNED;

   --new person now has one more bug assigned to them
   v_num_new := v_num_new + 1;
   update squasher_user set NUM_ASSIGNED = (v_num_new) where USERNAME = n.ASSIGNED;

END;
/
show errors;


--
-- CREATE OR REPLACE TRIGGER trigger_assignment
-- {BEFORE | AFTER | INSTEAD OF }
-- {INSERT [OR] | UPDATE [OR] | DELETE}
-- [OF col_name]
-- ON table_name
-- [REFERENCING OLD AS o NEW AS n]
-- [FOR EACH ROW]
-- WHEN (condition)
-- DECLARE
--    Declaration-statements
-- BEGIN
--    Executable-statements
-- EXCEPTION
--    Exception-handling-statements
-- END;
-- /
-- show errors;
