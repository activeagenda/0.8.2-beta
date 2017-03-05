CREATE TABLE `medse` (
   ScheduledExamID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ScheduledProviderID int,
   ExamResultsID int,
   ExamResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ScheduledExamID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medse_l` (
   _RecordID int unsigned not null auto_increment,
   ScheduledExamID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ScheduledProviderID int,
   ExamResultsID int,
   ExamResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ScheduledExamID
   )
) TYPE=InnoDB;
