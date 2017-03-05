CREATE TABLE `orgrg` (
   RegionID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   RegionName varchar(50),
   RegionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgrg_l` (
   _RecordID int unsigned not null auto_increment,
   RegionID int unsigned  not null,
   OrganizationID int unsigned,
   RegionName varchar(50),
   RegionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegionID
   )
) TYPE=InnoDB;
