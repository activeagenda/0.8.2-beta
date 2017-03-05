CREATE TABLE `pplek` (
   EmployeeKSAID int unsigned auto_increment not null,
   PersonID int unsigned,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EmployeeKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplek_l` (
   _RecordID int unsigned not null auto_increment,
   EmployeeKSAID int unsigned  not null,
   PersonID int unsigned,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EmployeeKSAID
   )
) TYPE=InnoDB;
