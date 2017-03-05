CREATE TABLE `aprcd` (
   ChecklistDeficiencyID int unsigned auto_increment not null,
   ChecklistHistoryID int,
   AssetProtectID int unsigned not null,
   DeficiencyTitle varchar(128),
   Deficiency text,
   Remediation text,
   DeficiencyStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistDeficiencyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprcd_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistDeficiencyID int unsigned  not null,
   ChecklistHistoryID int,
   AssetProtectID int unsigned not null,
   DeficiencyTitle varchar(128),
   Deficiency text,
   Remediation text,
   DeficiencyStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistDeficiencyID
   )
) TYPE=InnoDB;
