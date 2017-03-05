CREATE TABLE `ewkp` (
   ElevatedWorkPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   ElevatedWorkShiftID int,
   ElevatedWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewkp_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   ElevatedWorkShiftID int,
   ElevatedWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkPermitID
   )
) TYPE=InnoDB;
