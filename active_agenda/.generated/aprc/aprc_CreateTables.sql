CREATE TABLE `aprc` (
   ChecklistID int unsigned auto_increment not null,
   ChecklistTitle varchar(128),
   ChecklistDesc text,
   OrganizationID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   ChecklistInstruct text,
   ChecklistSpecialEquip text,
   ChecklistStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprc_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistID int unsigned  not null,
   ChecklistTitle varchar(128),
   ChecklistDesc text,
   OrganizationID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   ChecklistInstruct text,
   ChecklistSpecialEquip text,
   ChecklistStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistID
   )
) TYPE=InnoDB;
