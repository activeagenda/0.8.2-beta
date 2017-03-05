CREATE TABLE `jank` (
   JobKSAID int unsigned auto_increment not null,
   JobAnalysisID int,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jank_l` (
   _RecordID int unsigned not null auto_increment,
   JobKSAID int unsigned  not null,
   JobAnalysisID int,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobKSAID
   )
) TYPE=InnoDB;
