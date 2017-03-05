CREATE TABLE `hzead` (
   AdjustmentID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   AdjustmentOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AdjustmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzead_l` (
   _RecordID int unsigned not null auto_increment,
   AdjustmentID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   AdjustmentOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AdjustmentID
   )
) TYPE=InnoDB;
