CREATE TABLE `chmth` (
   ThresholdID int unsigned auto_increment not null,
   ChemicalID int,
   ThresholdValueTypeID int,
   ThresholdAmount int,
   ConcentrationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ThresholdID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmth_l` (
   _RecordID int unsigned not null auto_increment,
   ThresholdID int unsigned  not null,
   ChemicalID int,
   ThresholdValueTypeID int,
   ThresholdAmount int,
   ConcentrationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ThresholdID
   )
) TYPE=InnoDB;
