CREATE TABLE `veh` (
   VehicleID int unsigned auto_increment not null,
   AssignedOrganizationID int,
   VehYear varchar(50),
   VehicleModelID int,
   VehicleTypeID int,
   VehBody varchar(255),
   VehOperationTypeID int,
   VehColorID int,
   VehFuelTypeID int,
   VehDesc varchar(255),
   VehValue decimal(12,4),
   VehLoad float,
   VehLoadUnitsID int,
   VehWeight float,
   VehWeightUnitsID int,
   OwnerID int,
   VehNo varchar(50),
   VehIDNo varchar(50),
   VehLicenseNo varchar(50),
   StateID int,
   Commercial bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veh_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleID int unsigned  not null,
   AssignedOrganizationID int,
   VehYear varchar(50),
   VehicleModelID int,
   VehicleTypeID int,
   VehBody varchar(255),
   VehOperationTypeID int,
   VehColorID int,
   VehFuelTypeID int,
   VehDesc varchar(255),
   VehValue decimal(12,4),
   VehLoad float,
   VehLoadUnitsID int,
   VehWeight float,
   VehWeightUnitsID int,
   OwnerID int,
   VehNo varchar(50),
   VehIDNo varchar(50),
   VehLicenseNo varchar(50),
   StateID int,
   Commercial bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleID
   )
) TYPE=InnoDB;
