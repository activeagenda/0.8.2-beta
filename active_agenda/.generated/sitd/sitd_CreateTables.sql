CREATE TABLE `sitd` (
   SituationDrillID int unsigned auto_increment not null,
   SituationID int unsigned not null,
   DrillTitle varchar(128),
   EndTime datetime,
   DrillScope varchar(255),
   DrillObjective text,
   DrillPlanning text,
   KeyLearning text,
   DrillStatusID int default 2,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationDrillID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitd_l` (
   _RecordID int unsigned not null auto_increment,
   SituationDrillID int unsigned  not null,
   SituationID int unsigned not null,
   DrillTitle varchar(128),
   EndTime datetime,
   DrillScope varchar(255),
   DrillObjective text,
   DrillPlanning text,
   KeyLearning text,
   DrillStatusID int default 2,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationDrillID
   )
) TYPE=InnoDB;
