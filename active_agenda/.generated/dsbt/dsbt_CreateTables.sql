CREATE TABLE `dsbt` (
   DashboardTypeID int unsigned auto_increment not null,
   Title varchar(30),
   ClassName varchar(30),
   IncludeFile varchar(30),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbt_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardTypeID int unsigned  not null,
   Title varchar(30),
   ClassName varchar(30),
   IncludeFile varchar(30),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardTypeID
   )
) TYPE=InnoDB;
