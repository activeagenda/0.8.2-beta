CREATE TABLE `lppv` (
   LossVehicleID int unsigned auto_increment not null,
   OrganizationID int,
   VehicleID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   VehicleAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossVehicleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppv_l` (
   _RecordID int unsigned not null auto_increment,
   LossVehicleID int unsigned  not null,
   OrganizationID int,
   VehicleID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   VehicleAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossVehicleID
   )
) TYPE=InnoDB;
