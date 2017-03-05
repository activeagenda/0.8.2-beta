CREATE TABLE `lit` (
   LossITID int unsigned auto_increment not null,
   OrganizationID int,
   ITIncidentDesc text,
   ITIncidentStatusID int,
   ITIncidentIndicatorID int,
   ITIndicatorDesc text,
   ITIndicatorStatusID int,
   ShiftID int,
   IncidentDuration float,
   DurationUnitsID int,
   ITLossDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossITID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lit_l` (
   _RecordID int unsigned not null auto_increment,
   LossITID int unsigned  not null,
   OrganizationID int,
   ITIncidentDesc text,
   ITIncidentStatusID int,
   ITIncidentIndicatorID int,
   ITIndicatorDesc text,
   ITIndicatorStatusID int,
   ShiftID int,
   IncidentDuration float,
   DurationUnitsID int,
   ITLossDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossITID
   )
) TYPE=InnoDB;
