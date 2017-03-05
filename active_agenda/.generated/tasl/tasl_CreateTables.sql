CREATE TABLE `tasl` (
   LiftingID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LiftTaskTitle varchar(128),
   LiftWeight float,
   WeightUnitID int,
   LiftHeight float,
   DistanceUnitID int,
   LiftDuration float,
   TimeUnitID int,
   Frequency float,
   LiftingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LiftingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasl_l` (
   _RecordID int unsigned not null auto_increment,
   LiftingID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LiftTaskTitle varchar(128),
   LiftWeight float,
   WeightUnitID int,
   LiftHeight float,
   DistanceUnitID int,
   LiftDuration float,
   TimeUnitID int,
   Frequency float,
   LiftingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LiftingID
   )
) TYPE=InnoDB;
