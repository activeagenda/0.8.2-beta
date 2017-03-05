CREATE TABLE `ppleq` (
   QualificationID int unsigned auto_increment not null,
   PersonID int unsigned,
   InstitutionName varchar(128),
   LearningLevelID int unsigned,
   DegreeEarnedID int unsigned,
   CredentialName varchar(128),
   YearsAttended float,
   SpecialSkills varchar(128),
   QualificationStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QualificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppleq_l` (
   _RecordID int unsigned not null auto_increment,
   QualificationID int unsigned  not null,
   PersonID int unsigned,
   InstitutionName varchar(128),
   LearningLevelID int unsigned,
   DegreeEarnedID int unsigned,
   CredentialName varchar(128),
   YearsAttended float,
   SpecialSkills varchar(128),
   QualificationStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QualificationID
   )
) TYPE=InnoDB;
