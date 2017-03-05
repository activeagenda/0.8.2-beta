CREATE TABLE `pplep` (
   PriorEmployerID int unsigned auto_increment not null,
   PersonID int unsigned,
   EmployerName varchar(128),
   JobTitle varchar(128),
   EmploymentDescription text,
   YearsofService float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PriorEmployerID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplep_l` (
   _RecordID int unsigned not null auto_increment,
   PriorEmployerID int unsigned  not null,
   PersonID int unsigned,
   EmployerName varchar(128),
   JobTitle varchar(128),
   EmploymentDescription text,
   YearsofService float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PriorEmployerID
   )
) TYPE=InnoDB;
