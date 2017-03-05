CREATE TABLE `hzecl` (
   CleaningID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   CleaningOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CleaningID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzecl_l` (
   _RecordID int unsigned not null auto_increment,
   CleaningID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   CleaningOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CleaningID
   )
) TYPE=InnoDB;
