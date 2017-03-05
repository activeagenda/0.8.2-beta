CREATE TABLE `aprch` (
   ChecklistHistoryID int unsigned auto_increment not null,
   ChecklistID int,
   Deficiencies int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistHistoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprch_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistHistoryID int unsigned  not null,
   ChecklistID int,
   Deficiencies int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistHistoryID
   )
) TYPE=InnoDB;
