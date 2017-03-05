CREATE TABLE `oppt` (
   PermitTypeID int unsigned auto_increment not null,
   PermitCategoryID int,
   PermitTypeName varchar(128),
   PermitTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppt_l` (
   _RecordID int unsigned not null auto_increment,
   PermitTypeID int unsigned  not null,
   PermitCategoryID int,
   PermitTypeName varchar(128),
   PermitTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitTypeID
   )
) TYPE=InnoDB;
