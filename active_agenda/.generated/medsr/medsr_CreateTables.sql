CREATE TABLE `medsr` (
   ExamServiceResultID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   MedicalExamServiceID int unsigned not null,
   ScheduledExamID int unsigned not null,
   ScheduledServiceProviderID int,
   ServiceResultsID int,
   ServiceResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExamServiceResultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medsr_l` (
   _RecordID int unsigned not null auto_increment,
   ExamServiceResultID int unsigned  not null,
   MedicalExamID int unsigned not null,
   MedicalExamServiceID int unsigned not null,
   ScheduledExamID int unsigned not null,
   ScheduledServiceProviderID int,
   ServiceResultsID int,
   ServiceResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExamServiceResultID
   )
) TYPE=InnoDB;
