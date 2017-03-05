CREATE TABLE `medep` (
   ExamProcedureStepID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ProcedureStepOrder float,
   ProcedureStepTitle varchar(128),
   ProcedureStepDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExamProcedureStepID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medep_l` (
   _RecordID int unsigned not null auto_increment,
   ExamProcedureStepID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ProcedureStepOrder float,
   ProcedureStepTitle varchar(128),
   ProcedureStepDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExamProcedureStepID
   )
) TYPE=InnoDB;
