CREATE TABLE `wast` (
   WasteTypeID int unsigned auto_increment not null,
   WasteCategoryID int,
   WasteTypeName varchar(128),
   WasteTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wast_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTypeID int unsigned  not null,
   WasteCategoryID int,
   WasteTypeName varchar(128),
   WasteTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTypeID
   )
) TYPE=InnoDB;
