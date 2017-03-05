CREATE TABLE `orgj` (
   JobID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   ContractingID int unsigned,
   JobNumber varchar(128),
   JobDesc text,
   JobStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgj_l` (
   _RecordID int unsigned not null auto_increment,
   JobID int unsigned  not null,
   OrganizationID int unsigned,
   ContractingID int unsigned,
   JobNumber varchar(128),
   JobDesc text,
   JobStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobID
   )
) TYPE=InnoDB;
