CREATE TABLE `lppe` (
   LossEquipmentID int unsigned auto_increment not null,
   OrganizationID int,
   EquipmentTypeID int,
   EquipmentInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   EquipmentAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEquipmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppe_l` (
   _RecordID int unsigned not null auto_increment,
   LossEquipmentID int unsigned  not null,
   OrganizationID int,
   EquipmentTypeID int,
   EquipmentInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   EquipmentAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEquipmentID
   )
) TYPE=InnoDB;
