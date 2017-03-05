CREATE TABLE `tracn` (
   TrainingConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   CourseID int unsigned not null,
   TrainingConsiderationTitle varchar(128),
   TrainingConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracn_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   CourseID int unsigned not null,
   TrainingConsiderationTitle varchar(128),
   TrainingConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingConsiderationID
   )
) TYPE=InnoDB;
