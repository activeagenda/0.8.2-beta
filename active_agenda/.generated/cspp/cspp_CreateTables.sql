CREATE TABLE `cspp` (
   EntryPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   EntryShiftID int,
   ConfinedSpaceID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EntryPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cspp_l` (
   _RecordID int unsigned not null auto_increment,
   EntryPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   EntryShiftID int,
   ConfinedSpaceID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EntryPermitID
   )
) TYPE=InnoDB;
