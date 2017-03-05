CREATE TABLE `doc` (
   DocumentID int unsigned auto_increment not null,
   OrganizationID int,
   DepartmentID int,
   DocumentTypeID int unsigned not null,
   DocumentTitle varchar(128),
   DocumentVersion varchar(128),
   DocumentDesc text,
   DocumentPurpose text,
   DocumentScope text,
   CompletionImmediacy float,
   ImmediacyUnitsID int,
   ReviewFrequency float,
   FrequencyUnitsID int,
   DocumentStatusID int,
   Attached bool,
   RegulatoryRequired bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doc_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentID int unsigned  not null,
   OrganizationID int,
   DepartmentID int,
   DocumentTypeID int unsigned not null,
   DocumentTitle varchar(128),
   DocumentVersion varchar(128),
   DocumentDesc text,
   DocumentPurpose text,
   DocumentScope text,
   CompletionImmediacy float,
   ImmediacyUnitsID int,
   ReviewFrequency float,
   FrequencyUnitsID int,
   DocumentStatusID int,
   Attached bool,
   RegulatoryRequired bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentID
   )
) TYPE=InnoDB;
