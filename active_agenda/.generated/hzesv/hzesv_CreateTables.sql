CREATE TABLE `hzesv` (
   ServiceID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   ServiceOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzesv_l` (
   _RecordID int unsigned not null auto_increment,
   ServiceID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   ServiceOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ServiceID
   )
) TYPE=InnoDB;
