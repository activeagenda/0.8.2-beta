CREATE TABLE `gapoi` (
   GapAnalysisItemID int unsigned auto_increment not null,
   OrgGapAnalysisID int unsigned not null,
   GapAnalysisID int unsigned not null,
   OrganizationDescription text,
   OrganizationResources text,
   OrganizationLocations text,
   OrganizationRisks text,
   AnalysisStartTime datetime,
   AnalysisEndTime datetime,
   ReviewStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GapAnalysisItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gapoi_l` (
   _RecordID int unsigned not null auto_increment,
   GapAnalysisItemID int unsigned  not null,
   OrgGapAnalysisID int unsigned not null,
   GapAnalysisID int unsigned not null,
   OrganizationDescription text,
   OrganizationResources text,
   OrganizationLocations text,
   OrganizationRisks text,
   AnalysisStartTime datetime,
   AnalysisEndTime datetime,
   ReviewStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GapAnalysisItemID
   )
) TYPE=InnoDB;
