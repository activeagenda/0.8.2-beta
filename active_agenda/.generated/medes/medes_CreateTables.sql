CREATE TABLE `medes` (
   MedicalExamServiceID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ExamServiceID int,
   ExamServiceDetail text,
   SchedFreq float,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medes_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamServiceID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ExamServiceID int,
   ExamServiceDetail text,
   SchedFreq float,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamServiceID
   )
) TYPE=InnoDB;
