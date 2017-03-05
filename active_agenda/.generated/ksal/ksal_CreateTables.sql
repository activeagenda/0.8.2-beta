CREATE TABLE `ksal` (
   LevelID int unsigned auto_increment not null,
   KSAID int unsigned not null,
   KSALevelID int,
   KSALevelDesc text,
   ReviewCriteria text,
   TrainingQualified bool default 0,
   ExperienceQualified bool default 0,
   MedicallyQualified bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LevelID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksal_l` (
   _RecordID int unsigned not null auto_increment,
   LevelID int unsigned  not null,
   KSAID int unsigned not null,
   KSALevelID int,
   KSALevelDesc text,
   ReviewCriteria text,
   TrainingQualified bool default 0,
   ExperienceQualified bool default 0,
   MedicallyQualified bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LevelID
   )
) TYPE=InnoDB;
