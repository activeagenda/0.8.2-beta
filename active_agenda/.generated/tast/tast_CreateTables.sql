CREATE TABLE `tast` (
   TaskTypeID int unsigned auto_increment not null,
   TaskCategoryID int,
   TaskTypeTitle varchar(128),
   TaskTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TaskTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tast_l` (
   _RecordID int unsigned not null auto_increment,
   TaskTypeID int unsigned  not null,
   TaskCategoryID int,
   TaskTypeTitle varchar(128),
   TaskTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TaskTypeID
   )
) TYPE=InnoDB;
