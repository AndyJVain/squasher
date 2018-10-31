
drop table squasher_user;

create table squasher_user(
	USERNAME varchar(50) PRIMARY KEY,
	EMAIL varchar(50) NOT NULL,
	PASSWORD varchar(50) NOT NULL,
	ROLE varchar(50) check(UPPER(ROLE) in ('REPORTER','TESTER','DEVELOPER','MANAGER')) NOT NULL
 );

drop table squasher_counter;


insert into squasher_user values('psanch','pedrosanchezm97@gmail.com','lololol','TESTER');
insert into squasher_user values('connor-carraher','ccarraher@scu.edu','cheeks','DEVELOPER');
insert into squasher_user values('andyj','avainauskas@scu.edu','boosted','MANAGER');

create table squasher_counter(
	REPORT_NUMBER NUMBER
);

insert into squasher_counter values(0);



