CREATE TABLE `csc` (
   ModuleID varchar(5) not null,
   RecordID int not null,
   SeverityValue int not null,
   TotalCost decimal(12,4) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleID,
      RecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `csc_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SeverityValue int not null,
   TotalCost decimal(12,4) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleID,
      RecordID
   )
) TYPE=InnoDB;
