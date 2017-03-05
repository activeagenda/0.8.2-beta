CREATE TABLE `hze` (
   HazEnergyControlID int unsigned auto_increment not null,
   EquipmentInventoryID int unsigned,
   ControlProcTitle text,
   ControlProcDesc text,
   HazEnergyControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazEnergyControlID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hze_l` (
   _RecordID int unsigned not null auto_increment,
   HazEnergyControlID int unsigned  not null,
   EquipmentInventoryID int unsigned,
   ControlProcTitle text,
   ControlProcDesc text,
   HazEnergyControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazEnergyControlID
   )
) TYPE=InnoDB;
