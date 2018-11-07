
drop table squasher_user;

create table squasher_user(
	USERNAME varchar(50) PRIMARY KEY,
	EMAIL varchar(50) NOT NULL,
	PASSWORD varchar(50) NOT NULL,
	ROLE varchar(50) check(UPPER(ROLE) in ('REPORTER','TESTER','DEVELOPER','MANAGER')) NOT NULL
 );

insert into squasher_user values('psanch','pedrosanchezm97@gmail.com','lololol','TESTER');
insert into squasher_user values('connor-carraher','ccarraher@scu.edu','cheeks','DEVELOPER');
insert into squasher_user values('andyj','avainauskas@scu.edu','boosted','MANAGER');




drop table squasher_counter;
create table squasher_counter(
	REPORT_NUMBER NUMBER
);

insert into squasher_counter values(1);

drop table squasher_reports;
create table squasher_reports(
	BUG_ID NUMBER,
	PRODUCT VARCHAR(50) check(UPPER(PRODUCT) in ('CAMINO','COURSEAVAIL','ECAMPUS','LIBRARY','SCU WEBSITE')) NOT NULL,
	TITLE VARCHAR(50) NOT NULL,
	BUG_TYPE VARCHAR(50) check(UPPER(BUG_TYPE) in ('SECURITY','CRASH/HANG/DATA LOSS','POWER','PERFORMANCE','UI/USABILITY','SERIOUS BUG','OTHER BUG')) NOT NULL,
	REPRODUCABILITY VARCHAR(50) check(UPPER(REPRODUCABILITY) in ('ALWAYS','SOMETIMES','RARELY','UNABLE','I DID NOT TRY') ) NOT NULL,
	ASSIGNED VARCHAR(50) NOT NULL,
	STATE VARCHAR(50) NOT NULL,
	REPORTER_USERNAME VARCHAR(50) NOT NULL,
	REPORT_DATE VARCHAR(50) NOT NULL,
	DESCRIPTION VARCHAR(3000) NOT NULL
);
insert into squasher_reports values(0, 'CAMINO', 'NOT LIT', 'SECURITY', 'ALWAYS', 'connor-carraher', 'PENDING BUG VERIFICATION', 'connor-carraher', '29-OCT-18','blablabla');
