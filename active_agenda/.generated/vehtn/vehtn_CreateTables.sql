CREATE TABLE `vehtn` (
   VehicleTrackingNoID int unsigned auto_increment not null,
   VehicleID int,
   VehicleTrackingNumberTypeID int,
   TrackingNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleTrackingNoID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehtn_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleTrackingNoID int unsigned  not null,
   VehicleID int,
   VehicleTrackingNumberTypeID int,
   TrackingNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleTrackingNoID
   )
) TYPE=InnoDB;
