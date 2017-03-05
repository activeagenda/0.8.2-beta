CREATE TABLE `trak` (
   TrainingKSAID int unsigned auto_increment not null,
   CourseID int,
   KSAID int,
   LevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trak_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingKSAID int unsigned  not null,
   CourseID int,
   KSAID int,
   LevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingKSAID
   )
) TYPE=InnoDB;
