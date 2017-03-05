CREATE TABLE `hzerp` (
   RepairID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   RepairOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RepairID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzerp_l` (
   _RecordID int unsigned not null auto_increment,
   RepairID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   RepairOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RepairID
   )
) TYPE=InnoDB;
