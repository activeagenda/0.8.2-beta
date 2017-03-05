CREATE TABLE `gapo` (
   OrgGapAnalysisID int unsigned auto_increment not null,
   OrganizationID int,
   GapTitle varchar(128),
   GapObjective text,
   ScheduledStart datetime,
   ScheduledEnd datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrgGapAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gapo_l` (
   _RecordID int unsigned not null auto_increment,
   OrgGapAnalysisID int unsigned  not null,
   OrganizationID int,
   GapTitle varchar(128),
   GapObjective text,
   ScheduledStart datetime,
   ScheduledEnd datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrgGapAnalysisID
   )
) TYPE=InnoDB;
