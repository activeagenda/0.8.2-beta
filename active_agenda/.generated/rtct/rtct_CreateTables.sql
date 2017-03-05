CREATE TABLE `rtct` (
   CausationTypeID int unsigned auto_increment not null,
   CauseCategoryID int,
   CauseType varchar(128),
   CauseTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CausationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtct_l` (
   _RecordID int unsigned not null auto_increment,
   CausationTypeID int unsigned  not null,
   CauseCategoryID int,
   CauseType varchar(128),
   CauseTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CausationTypeID
   )
) TYPE=InnoDB;
