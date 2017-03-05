CREATE TABLE `tas` (
   TaskID int unsigned auto_increment not null,
   OrganizationID int,
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
      TaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tas_l` (
   _RecordID int unsigned not null auto_increment,
   TaskID int unsigned  not null,
   OrganizationID int,
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
      TaskID
   )
) TYPE=InnoDB;
