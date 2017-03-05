CREATE TABLE `agr` (
   AgreementID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   SecondOrganizationID int,
   AgreementTypeID int unsigned not null,
   AgmtTitle varchar(128),
   AgmtSummary text,
   TerminationFactors text,
   AgmtStatusID int,
   AgmtAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agr_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   SecondOrganizationID int,
   AgreementTypeID int unsigned not null,
   AgmtTitle varchar(128),
   AgmtSummary text,
   TerminationFactors text,
   AgmtStatusID int,
   AgmtAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementID
   )
) TYPE=InnoDB;
