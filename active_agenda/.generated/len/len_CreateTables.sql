CREATE TABLE `len` (
   LossEnvironmentID int unsigned auto_increment not null,
   OrganizationID int,
   ChemicalID int unsigned,
   SystemID int unsigned not null,
   SubSystemID int unsigned not null,
   ComponentID int unsigned not null,
   ReleaseTypeID int,
   ReleaseLossDesc text,
   ShiftID int,
   ReleaseDuration float,
   DurationUnitsID int,
   ConcentrationAmount float,
   ConcentrationUnitsID int,
   ReleaseAmount float,
   WeightVolUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEnvironmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `len_l` (
   _RecordID int unsigned not null auto_increment,
   LossEnvironmentID int unsigned  not null,
   OrganizationID int,
   ChemicalID int unsigned,
   SystemID int unsigned not null,
   SubSystemID int unsigned not null,
   ComponentID int unsigned not null,
   ReleaseTypeID int,
   ReleaseLossDesc text,
   ShiftID int,
   ReleaseDuration float,
   DurationUnitsID int,
   ConcentrationAmount float,
   ConcentrationUnitsID int,
   ReleaseAmount float,
   WeightVolUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEnvironmentID
   )
) TYPE=InnoDB;
