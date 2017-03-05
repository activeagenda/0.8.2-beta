CREATE TABLE `dsbo` (
   DashboardFieldID int unsigned auto_increment not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardFieldID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbo_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardFieldID int unsigned  not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardFieldID
   )
) TYPE=InnoDB;
