CREATE TABLE `medee` (
   MedicalExamElementID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ExamElementID int,
   ExamElementDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamElementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medee_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamElementID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ExamElementID int,
   ExamElementDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamElementID
   )
) TYPE=InnoDB;
