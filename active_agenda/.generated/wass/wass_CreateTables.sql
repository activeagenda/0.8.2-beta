CREATE TABLE `wass` (
   WasteStorageID int unsigned auto_increment not null,
   WasteID int,
   StorageOrganizationID int,
   StorageMethodID int,
   Quantity float,
   QuantityUoMID int,
   StorageDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteStorageID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wass_l` (
   _RecordID int unsigned not null auto_increment,
   WasteStorageID int unsigned  not null,
   WasteID int,
   StorageOrganizationID int,
   StorageMethodID int,
   Quantity float,
   QuantityUoMID int,
   StorageDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteStorageID
   )
) TYPE=InnoDB;
