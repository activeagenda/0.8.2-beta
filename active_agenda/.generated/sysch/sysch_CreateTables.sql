CREATE TABLE `sysch` (
   SystemChemicalID int unsigned auto_increment not null,
   SystemID int,
   ChemicalInventoryID int,
   MatClassID int,
   DailyVolume float,
   DVWeightVolUnitsID int,
   EmissionTypeID int,
   EmissionConc float,
   ConcentrationUnitsID int,
   EmissionsEstimate float,
   EEWeightVolUnitsID int,
   ReleasePeriod float,
   RelPeriodUnitsID int,
   EeMonitoring bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemChemicalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysch_l` (
   _RecordID int unsigned not null auto_increment,
   SystemChemicalID int unsigned  not null,
   SystemID int,
   ChemicalInventoryID int,
   MatClassID int,
   DailyVolume float,
   DVWeightVolUnitsID int,
   EmissionTypeID int,
   EmissionConc float,
   ConcentrationUnitsID int,
   EmissionsEstimate float,
   EEWeightVolUnitsID int,
   ReleasePeriod float,
   RelPeriodUnitsID int,
   EeMonitoring bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SystemChemicalID
   )
) TYPE=InnoDB;
