CREATE TABLE `jant` (
   JobTaskID int unsigned auto_increment not null,
   JobAnalysisID int,
   SortOrder int,
   TaskID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jant_l` (
   _RecordID int unsigned not null auto_increment,
   JobTaskID int unsigned  not null,
   JobAnalysisID int,
   SortOrder int,
   TaskID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTaskID
   )
) TYPE=InnoDB;
