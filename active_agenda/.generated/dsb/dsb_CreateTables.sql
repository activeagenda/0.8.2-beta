CREATE TABLE `dsb` (
   DashboardItemID int unsigned auto_increment not null,
   PersonID int,
   ModuleID varchar(5),
   Title varchar(128),
   DashboardTypeID int,
   CachedSQL text,
   DisplayRows int,
   PageColumn int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsb_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardItemID int unsigned  not null,
   PersonID int,
   ModuleID varchar(5),
   Title varchar(128),
   DashboardTypeID int,
   CachedSQL text,
   DisplayRows int,
   PageColumn int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardItemID
   )
) TYPE=InnoDB;
