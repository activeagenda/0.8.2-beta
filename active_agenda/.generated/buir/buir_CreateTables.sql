CREATE TABLE `buir` (
   RoomID int unsigned auto_increment not null,
   BuildingID int unsigned,
   RoomName varchar(128),
   RoomNumber varchar(10),
   FloorID int unsigned,
   RoomPurpose text,
   FireProtection bool,
   FireSuppression text,
   FireSuppTypeID int unsigned,
   InventoryValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RoomID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `buir_l` (
   _RecordID int unsigned not null auto_increment,
   RoomID int unsigned  not null,
   BuildingID int unsigned,
   RoomName varchar(128),
   RoomNumber varchar(10),
   FloorID int unsigned,
   RoomPurpose text,
   FireProtection bool,
   FireSuppression text,
   FireSuppTypeID int unsigned,
   InventoryValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RoomID
   )
) TYPE=InnoDB;
