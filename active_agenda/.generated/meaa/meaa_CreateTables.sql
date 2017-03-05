CREATE TABLE `meaa` (
   AssignedMeasurementID int unsigned auto_increment not null,
   MeasurementID int,
   NumeratorOrganizationID int,
   DenominatorOrganizationID int,
   XIntervalID int,
   AssignedOrganizationID int,
   AssigneeResultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssignedMeasurementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `meaa_l` (
   _RecordID int unsigned not null auto_increment,
   AssignedMeasurementID int unsigned  not null,
   MeasurementID int,
   NumeratorOrganizationID int,
   DenominatorOrganizationID int,
   XIntervalID int,
   AssignedOrganizationID int,
   AssigneeResultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssignedMeasurementID
   )
) TYPE=InnoDB;
