CREATE TABLE `sysc` (
   ComponentID int unsigned auto_increment not null,
   SubSystemID int,
   SysComponentTypeID int,
   ComponentName varchar(128),
   ComponentNumber varchar(50),
   ComponentDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ComponentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysc_l` (
   _RecordID int unsigned not null auto_increment,
   ComponentID int unsigned  not null,
   SubSystemID int,
   SysComponentTypeID int,
   ComponentName varchar(128),
   ComponentNumber varchar(50),
   ComponentDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ComponentID
   )
) TYPE=InnoDB;
