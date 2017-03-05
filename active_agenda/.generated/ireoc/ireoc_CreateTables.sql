CREATE TABLE `ireoc` (
   OutCounselID int unsigned auto_increment not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   LawFirmID int,
   LitigationRelationshipID int,
   RepresentationDetails text,
   CostofRepresentation decimal(12,4),
   RepresentationStatusID int,
   ResolutionOutcomeID int,
   ResolutionCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OutCounselID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ireoc_l` (
   _RecordID int unsigned not null auto_increment,
   OutCounselID int unsigned  not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   LawFirmID int,
   LitigationRelationshipID int,
   RepresentationDetails text,
   CostofRepresentation decimal(12,4),
   RepresentationStatusID int,
   ResolutionOutcomeID int,
   ResolutionCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OutCounselID
   )
) TYPE=InnoDB;
