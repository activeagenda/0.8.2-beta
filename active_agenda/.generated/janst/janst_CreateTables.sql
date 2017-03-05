CREATE TABLE `janst` (
   JobSpecificTaskID int unsigned auto_increment not null,
   JobAnalysisID int unsigned not null,
   SortOrder int,
   TaskOrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobSpecificTaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `janst_l` (
   _RecordID int unsigned not null auto_increment,
   JobSpecificTaskID int unsigned  not null,
   JobAnalysisID int unsigned not null,
   SortOrder int,
   TaskOrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobSpecificTaskID
   )
) TYPE=InnoDB;
