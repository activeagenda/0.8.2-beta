CREATE TABLE `tra` (
   CourseID int unsigned auto_increment not null,
   CourseCode varchar(50),
   CourseTitle varchar(128),
   CourseDesc text,
   CourseObj text,
   TrainingTypeID int unsigned not null,
   OrgLevelID int,
   MinClassSize float,
   MaxClassSize float,
   Duration float,
   TimeUnitID int,
   Validity float,
   ValidityUnitID int,
   CoursePrep text,
   OrganizationID int,
   RegulatoryRequired bool default 0,
   CourseStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CourseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tra_l` (
   _RecordID int unsigned not null auto_increment,
   CourseID int unsigned  not null,
   CourseCode varchar(50),
   CourseTitle varchar(128),
   CourseDesc text,
   CourseObj text,
   TrainingTypeID int unsigned not null,
   OrgLevelID int,
   MinClassSize float,
   MaxClassSize float,
   Duration float,
   TimeUnitID int,
   Validity float,
   ValidityUnitID int,
   CoursePrep text,
   OrganizationID int,
   RegulatoryRequired bool default 0,
   CourseStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CourseID
   )
) TYPE=InnoDB;
