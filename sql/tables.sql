/*
  Author: Andy Vainauskas, Connor Carraher, Pedro Sanchez
  Date: 11/29/2018
  Purpose: This file configures the database schema and inserts the initial users into the database.
					 This file will be run once at the beginning of the installation process.
*/

--
-- TABLE DEFINITIONS
--
drop table squasher_user;
create table squasher_user(
	USERNAME varchar(50) PRIMARY KEY,
	EMAIL varchar(50) NOT NULL,
	PASSWORD varchar(64) NOT NULL,
	ROLE varchar(50) check(UPPER(ROLE) in ('REPORTER','TESTER','DEVELOPER','MANAGER','DUMMY')) NOT NULL,
	NUM_ASSIGNED NUMBER,
	LATEST_ASSIGNED_BUG NUMBER
 );

 --insert into squasher_user values('manager','<MANAGER EMAIL>','<SHA256 HASHED PASSWORD>','MANAGER',0,0);
 insert into squasher_user values('manager','manager@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','MANAGER',0,0);

insert into squasher_user values('failed','b@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0,0);
insert into squasher_user values('done','b@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0,0);

drop table squasher_counter;
create table squasher_counter(
	REPORT_NUMBER NUMBER
);

insert into squasher_counter values(0);

drop table squasher_reports;
create table squasher_reports(
	BUG_ID NUMBER,
	PRODUCT VARCHAR(50) check(UPPER(PRODUCT) in ('CAMINO','COURSEAVAIL','ECAMPUS','LIBRARY','SCU WEBSITE')) NOT NULL,
	TITLE VARCHAR(50) NOT NULL,
	BUG_TYPE VARCHAR(50) check(UPPER(BUG_TYPE) in ('SECURITY','CRASH/HANG/DATA LOSS','POWER','PERFORMANCE','UI/USABILITY','SERIOUS BUG','OTHER BUG')) NOT NULL,
	REPRODUCIBILITY VARCHAR(50) check(UPPER(REPRODUCIBILITY) in ('ALWAYS','SOMETIMES','RARELY','UNABLE','I DID NOT TRY') ) NOT NULL,
	ASSIGNED VARCHAR(50) NOT NULL,
	STATE VARCHAR(50) check(UPPER(STATE) in ('PENDING BUG VERIFICATION','BUG VERIFICATION FAILED','PENDING DEVELOPER ASSIGNMENT','IN DEVELOPMENT','PENDING FIX VERIFICATION','DONE')) NOT NULL,
	REPORTER_USERNAME VARCHAR(50) NOT NULL,
	REPORT_DATE VARCHAR(50) NOT NULL,
	DESCRIPTION VARCHAR(3000) NOT NULL
);
--
-- END TABLE DEFINITIONS
--


--
-- TABLE TRIGGERS
--
--Handles updating the number of assigned bugs a user has ON UPDATE
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

--Handles updating the number of assigned bugs a user has ON INSERTION
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
--
-- END TABLE TRIGGERS
--
