CREATE TABLE `aprci` (
   ChecklistItemID int unsigned auto_increment not null,
   ChecklistID int,
   AssetProtectID int,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprci_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistItemID int unsigned  not null,
   ChecklistID int,
   AssetProtectID int,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistItemID
   )
) TYPE=InnoDB;
