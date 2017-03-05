CREATE TABLE `eqp` (
   EquipmentID int unsigned auto_increment not null,
   OrganizationID int,
   EquipmentTypeID int unsigned not null,
   EquipmentName varchar(128),
   EquipmentDesc text,
   ModelNo varchar(50),
   BaseUnitID int,
   ManufacturerID int,
   ManufPartNo varchar(128),
   EquipmentURL varchar(128),
   EmergencyResponse bool default 0,
   Issued bool,
   HazardousEnergy bool default 0,
   SystemComponent bool default 0,
   CriticalControl bool default 0,
   Training bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqp_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentID int unsigned  not null,
   OrganizationID int,
   EquipmentTypeID int unsigned not null,
   EquipmentName varchar(128),
   EquipmentDesc text,
   ModelNo varchar(50),
   BaseUnitID int,
   ManufacturerID int,
   ManufPartNo varchar(128),
   EquipmentURL varchar(128),
   EmergencyResponse bool default 0,
   Issued bool,
   HazardousEnergy bool default 0,
   SystemComponent bool default 0,
   CriticalControl bool default 0,
   Training bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentID
   )
) TYPE=InnoDB;
