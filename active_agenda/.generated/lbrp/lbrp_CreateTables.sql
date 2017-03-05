CREATE TABLE `lbrp` (
   LineBreakPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   LineBreakShiftID int,
   LineBreakID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lbrp_l` (
   _RecordID int unsigned not null auto_increment,
   LineBreakPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   LineBreakShiftID int,
   LineBreakID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LineBreakPermitID
   )
) TYPE=InnoDB;
