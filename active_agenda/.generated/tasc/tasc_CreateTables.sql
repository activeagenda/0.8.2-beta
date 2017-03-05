CREATE TABLE `tasc` (
   CarryingID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CarryTaskTitle varchar(128),
   CarryWeight float,
   WeightUnitID int,
   CarryDistance float,
   DistanceUnitID int,
   CarryDuration float,
   TimeUnitID int,
   Frequency float,
   CarryingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CarryingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasc_l` (
   _RecordID int unsigned not null auto_increment,
   CarryingID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CarryTaskTitle varchar(128),
   CarryWeight float,
   WeightUnitID int,
   CarryDistance float,
   DistanceUnitID int,
   CarryDuration float,
   TimeUnitID int,
   Frequency float,
   CarryingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CarryingID
   )
) TYPE=InnoDB;
