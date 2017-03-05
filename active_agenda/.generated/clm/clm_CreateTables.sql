CREATE TABLE `clm` (
   ClaimID int unsigned auto_increment not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   TimeDeterminable bool default 1,
   PolicyID int,
   ClaimNumber varchar(50),
   OnCoPropID int,
   IncurredLocationDesc varchar(255),
   IncurredAddress1 varchar(50),
   IncurredAddress2 varchar(50),
   IncurredCountyID int unsigned,
   IncurredCity varchar(50),
   IncurredPostalCode varchar(50),
   LossStatusTypeID int,
   SpecificLossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ClaimID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `clm_l` (
   _RecordID int unsigned not null auto_increment,
   ClaimID int unsigned  not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   TimeDeterminable bool default 1,
   PolicyID int,
   ClaimNumber varchar(50),
   OnCoPropID int,
   IncurredLocationDesc varchar(255),
   IncurredAddress1 varchar(50),
   IncurredAddress2 varchar(50),
   IncurredCountyID int unsigned,
   IncurredCity varchar(50),
   IncurredPostalCode varchar(50),
   LossStatusTypeID int,
   SpecificLossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ClaimID
   )
) TYPE=InnoDB;
