CREATE TABLE `vehml` (
   MileageID int unsigned auto_increment not null,
   VehicleID int,
   Odometer float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MileageID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehml_l` (
   _RecordID int unsigned not null auto_increment,
   MileageID int unsigned  not null,
   VehicleID int,
   Odometer float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MileageID
   )
) TYPE=InnoDB;
