CREATE TABLE `uts` (
   UnitID int unsigned auto_increment not null,
   UnitCategoryID int,
   UnitName varchar(128),
   UnitValue decimal(12,2),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      UnitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `uts_l` (
   _RecordID int unsigned not null auto_increment,
   UnitID int unsigned  not null,
   UnitCategoryID int,
   UnitName varchar(128),
   UnitValue decimal(12,2),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      UnitID
   )
) TYPE=InnoDB;
