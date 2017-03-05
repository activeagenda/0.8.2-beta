CREATE TABLE `apr` (
   AssetProtectID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ReviewOrganizationID int,
   OccurrenceTypeID int,
   ItemStatusID int,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssetProtectID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `apr_l` (
   _RecordID int unsigned not null auto_increment,
   AssetProtectID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ReviewOrganizationID int,
   OccurrenceTypeID int,
   ItemStatusID int,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssetProtectID
   )
) TYPE=InnoDB;
