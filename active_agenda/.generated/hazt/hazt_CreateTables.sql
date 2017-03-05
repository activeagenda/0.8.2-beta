CREATE TABLE `hazt` (
   HazardTypeID int unsigned auto_increment not null,
   HazCategoryID int,
   HazType varchar(128),
   HazTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hazt_l` (
   _RecordID int unsigned not null auto_increment,
   HazardTypeID int unsigned  not null,
   HazCategoryID int,
   HazType varchar(128),
   HazTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardTypeID
   )
) TYPE=InnoDB;
