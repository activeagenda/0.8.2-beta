CREATE TABLE `busa` (
   BusinessContID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BusinessContinuationID int unsigned not null,
   OrganizationID int,
   BusRecoveryTitle varchar(128),
   BusRecoveryRespon text,
   CriticalityID int,
   Impact decimal(12,4),
   Budget decimal(12,4),
   TakeActionImmediacy float,
   ImmediacyUnitsID int,
   AcquireWithin varchar(10),
   AcquireWithinUnitsID int,
   ContinuationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessContID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `busa_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessContID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BusinessContinuationID int unsigned not null,
   OrganizationID int,
   BusRecoveryTitle varchar(128),
   BusRecoveryRespon text,
   CriticalityID int,
   Impact decimal(12,4),
   Budget decimal(12,4),
   TakeActionImmediacy float,
   ImmediacyUnitsID int,
   AcquireWithin varchar(10),
   AcquireWithinUnitsID int,
   ContinuationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessContID
   )
) TYPE=InnoDB;
