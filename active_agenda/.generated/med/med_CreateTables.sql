CREATE TABLE `med` (
   MedicalExamID int unsigned auto_increment not null,
   OrganizationID int,
   MedicalExamTypeID int unsigned not null,
   MedicalExamTitle varchar(128),
   MedicalExamDesc text,
   MedicalExamCriteria text,
   SchedFreq float,
   SchedFreqUnitsID int,
   MedicalProviderID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `med_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamID int unsigned  not null,
   OrganizationID int,
   MedicalExamTypeID int unsigned not null,
   MedicalExamTitle varchar(128),
   MedicalExamDesc text,
   MedicalExamCriteria text,
   SchedFreq float,
   SchedFreqUnitsID int,
   MedicalProviderID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamID
   )
) TYPE=InnoDB;
