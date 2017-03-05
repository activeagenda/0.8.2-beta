CREATE TABLE `rega` (
   RegulationAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RegulationID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulationAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rega_l` (
   _RecordID int unsigned not null auto_increment,
   RegulationAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RegulationID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulationAssocID
   )
) TYPE=InnoDB;
