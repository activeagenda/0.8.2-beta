CREATE TABLE `hwkp` (
   HotWorkPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   HotWorkShiftID int,
   HotWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwkp_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   HotWorkShiftID int,
   HotWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkPermitID
   )
) TYPE=InnoDB;
