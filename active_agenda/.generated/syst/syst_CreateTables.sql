CREATE TABLE `syst` (
   SystemTypeID int unsigned auto_increment not null,
   SystemCategoryID int,
   SystemTypeName varchar(128),
   SystemTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `syst_l` (
   _RecordID int unsigned not null auto_increment,
   SystemTypeID int unsigned  not null,
   SystemCategoryID int,
   SystemTypeName varchar(128),
   SystemTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SystemTypeID
   )
) TYPE=InnoDB;
