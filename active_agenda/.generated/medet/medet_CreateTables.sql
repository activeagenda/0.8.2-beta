CREATE TABLE `medet` (
   MedicalExamTypeID int unsigned auto_increment not null,
   ExamCategoryID int,
   ExamType varchar(128),
   ExamTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medet_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamTypeID int unsigned  not null,
   ExamCategoryID int,
   ExamType varchar(128),
   ExamTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamTypeID
   )
) TYPE=InnoDB;
