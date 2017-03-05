CREATE TABLE `vehmd` (
   VehicleModelID int unsigned auto_increment not null,
   VehMakeID int,
   VehModel varchar(128),
   VehModelNo varchar(20),
   VehModelDesc text,
   BestPrice decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleModelID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehmd_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleModelID int unsigned  not null,
   VehMakeID int,
   VehModel varchar(128),
   VehModelNo varchar(20),
   VehModelDesc text,
   BestPrice decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleModelID
   )
) TYPE=InnoDB;
