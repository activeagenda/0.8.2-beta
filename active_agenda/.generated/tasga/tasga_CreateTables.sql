CREATE TABLE `tasga` (
   ActivityID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GeneralActivityTitle varchar(128),
   FunActivityID int,
   ActivityDuration float,
   TimeUnitID int,
   Frequency float,
   ActivityDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ActivityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasga_l` (
   _RecordID int unsigned not null auto_increment,
   ActivityID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GeneralActivityTitle varchar(128),
   FunActivityID int,
   ActivityDuration float,
   TimeUnitID int,
   Frequency float,
   ActivityDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ActivityID
   )
) TYPE=InnoDB;
