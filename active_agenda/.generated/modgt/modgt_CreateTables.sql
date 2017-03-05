CREATE TABLE `modgt` (
   RecordID int unsigned auto_increment not null,
   ModuleID varchar(5) not null,
   Duration float unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecordID
   ),
   INDEX modgt_ModuleID (
      ModuleID,
      Duration
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modgt_l` (
   _RecordID int unsigned not null auto_increment,
   RecordID int unsigned  not null,
   ModuleID varchar(5) not null,
   Duration float unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecordID
   ),
   INDEX modgt_l_ModuleID (
      ModuleID,
      Duration
   )
) TYPE=InnoDB;
