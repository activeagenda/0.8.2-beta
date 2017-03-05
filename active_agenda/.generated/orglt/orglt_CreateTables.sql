CREATE TABLE `orglt` (
   LocationTypeID int unsigned auto_increment not null,
   LocationCategoryID int,
   LocationTypeTitle varchar(128),
   LocationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orglt_l` (
   _RecordID int unsigned not null auto_increment,
   LocationTypeID int unsigned  not null,
   LocationCategoryID int,
   LocationTypeTitle varchar(128),
   LocationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationTypeID
   )
) TYPE=InnoDB;
