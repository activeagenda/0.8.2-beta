CREATE TABLE `aprd` (
   AssetProtectDefaultID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq varchar(10),
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssetProtectDefaultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprd_l` (
   _RecordID int unsigned not null auto_increment,
   AssetProtectDefaultID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq varchar(10),
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssetProtectDefaultID
   )
) TYPE=InnoDB;
