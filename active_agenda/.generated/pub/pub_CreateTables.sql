CREATE TABLE `pub` (
   PublicityExposureID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   ExposureTitle varchar(128),
   ExposureDescription text,
   PublicityTypeID int unsigned not null,
   PublicityScopeID int,
   ExposureCriticalityID int,
   ExposureStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pub_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityExposureID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   ExposureTitle varchar(128),
   ExposureDescription text,
   PublicityTypeID int unsigned not null,
   PublicityScopeID int,
   ExposureCriticalityID int,
   ExposureStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityExposureID
   )
) TYPE=InnoDB;
