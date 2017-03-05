CREATE TABLE `hwkt` (
   HotWorkTypeID int unsigned auto_increment not null,
   HotWorkCategoryID int,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwkt_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkTypeID int unsigned  not null,
   HotWorkCategoryID int,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkTypeID
   )
) TYPE=InnoDB;
