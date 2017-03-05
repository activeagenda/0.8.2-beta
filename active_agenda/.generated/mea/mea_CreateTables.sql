CREATE TABLE `mea` (
   MeasurementID int unsigned auto_increment not null,
   MeasurementTitle varchar(128),
   MeasurementPurpose text,
   MeasurementRationale text,
   BenchmarkValue float,
   BenchmarkDescription text,
   OrganizationID int,
   MeasurementOriginDate date,
   MeasurementStatusID int,
   NumeratorTitle varchar(128),
   NumeratorModuleID varchar(5),
   NumeratorOrganizationID int,
   NumeratorTypeID int,
   DenominatorTitle varchar(128),
   DenominatorModuleID varchar(5),
   DenominatorOrganizationID int,
   DenominatorTypeID int,
   StandardBase float,
   ChartTypeID int,
   XIntervalValue float,
   XIntervalID int,
   MeasurementStartDate date,
   MeasurementEndDate date,
   Resultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MeasurementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mea_l` (
   _RecordID int unsigned not null auto_increment,
   MeasurementID int unsigned  not null,
   MeasurementTitle varchar(128),
   MeasurementPurpose text,
   MeasurementRationale text,
   BenchmarkValue float,
   BenchmarkDescription text,
   OrganizationID int,
   MeasurementOriginDate date,
   MeasurementStatusID int,
   NumeratorTitle varchar(128),
   NumeratorModuleID varchar(5),
   NumeratorOrganizationID int,
   NumeratorTypeID int,
   DenominatorTitle varchar(128),
   DenominatorModuleID varchar(5),
   DenominatorOrganizationID int,
   DenominatorTypeID int,
   StandardBase float,
   ChartTypeID int,
   XIntervalValue float,
   XIntervalID int,
   MeasurementStartDate date,
   MeasurementEndDate date,
   Resultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MeasurementID
   )
) TYPE=InnoDB;
