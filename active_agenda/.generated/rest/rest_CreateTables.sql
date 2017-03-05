CREATE TABLE `rest` (
   ResourceTypeID int auto_increment not null,
   ResourcePurposeID int,
   ResourceTitle varchar(128),
   ResourceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rest_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceTypeID int  not null,
   ResourcePurposeID int,
   ResourceTitle varchar(128),
   ResourceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceTypeID
   )
) TYPE=InnoDB;
