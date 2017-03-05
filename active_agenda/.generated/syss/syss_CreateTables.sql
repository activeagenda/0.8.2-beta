CREATE TABLE `syss` (
   SubSystemID int unsigned auto_increment not null,
   SystemID int,
   SubSystemName varchar(128),
   SubSystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SubSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `syss_l` (
   _RecordID int unsigned not null auto_increment,
   SubSystemID int unsigned  not null,
   SystemID int,
   SubSystemName varchar(128),
   SubSystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SubSystemID
   )
) TYPE=InnoDB;
