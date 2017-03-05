CREATE TABLE `sysct` (
   SysComponentTypeID int unsigned auto_increment not null,
   ComponentCategoryID int,
   ComponentType varchar(128),
   ComponentDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SysComponentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysct_l` (
   _RecordID int unsigned not null auto_increment,
   SysComponentTypeID int unsigned  not null,
   ComponentCategoryID int,
   ComponentType varchar(128),
   ComponentDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SysComponentTypeID
   )
) TYPE=InnoDB;
