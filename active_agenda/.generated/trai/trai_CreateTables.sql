CREATE TABLE `trai` (
   TrainingInstructorID int unsigned auto_increment not null,
   CourseID int,
   Qualifications text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingInstructorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trai_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingInstructorID int unsigned  not null,
   CourseID int,
   Qualifications text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingInstructorID
   )
) TYPE=InnoDB;
