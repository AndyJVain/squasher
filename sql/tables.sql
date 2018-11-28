
drop table squasher_user;

create table squasher_user(
	USERNAME varchar(50) PRIMARY KEY,
	EMAIL varchar(50) NOT NULL,
	PASSWORD varchar(64) NOT NULL,
	ROLE varchar(50) check(UPPER(ROLE) in ('REPORTER','TESTER','DEVELOPER','MANAGER','DUMMY')) NOT NULL,
	NUM_ASSIGNED NUMBER,
	LATEST_ASSIGNED_BUG NUMBER
 );

insert into squasher_user values('reporter','ccarraher@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','REPORTER',0);
insert into squasher_user values('tester','tester@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','TESTER',0);
insert into squasher_user values('tester1','tester@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','TESTER',0);
insert into squasher_user values('dev','developer@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DEVELOPER',0);
insert into squasher_user values('dev1','developer@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DEVELOPER',0);
insert into squasher_user values('dev2','developer@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DEVELOPER',0);
insert into squasher_user values('manager','manager@scu.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','MANAGER',0);

insert into squasher_user values('assigner','d@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0);
insert into squasher_user values('assigner_fix','d@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0);
insert into squasher_user values('failed','b@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0);
insert into squasher_user values('done','b@junk.edu','a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3','DUMMY',0);

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

--insert into squasher_reports values(0, 'CAMINO', 'NOT LIT', 'SECURITY', 'ALWAYS', 'connor-carraher', 'PENDING BUG VERIFICATION', 'connor-carraher', '29-OCT-18','blablabla');
