CREATE TABLE `veht` (
   VehicleTypeID int unsigned auto_increment not null,
   VehicleTypeName varchar(128),
   VehicleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veht_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleTypeID int unsigned  not null,
   VehicleTypeName varchar(128),
   VehicleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleTypeID
   )
) TYPE=InnoDB;
