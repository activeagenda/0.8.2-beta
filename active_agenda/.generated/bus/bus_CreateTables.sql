CREATE TABLE `bus` (
   BusinessContinuationID int unsigned auto_increment not null,
   BusinessProcessTypeID int,
   BusinessContinuationTitle varchar(128),
   BusinessContinuationNeed text,
   OperationalConsiderations text,
   FinancialConsiderations text,
   SignificantImpact decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessContinuationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bus_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessContinuationID int unsigned  not null,
   BusinessProcessTypeID int,
   BusinessContinuationTitle varchar(128),
   BusinessContinuationNeed text,
   OperationalConsiderations text,
   FinancialConsiderations text,
   SignificantImpact decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessContinuationID
   )
) TYPE=InnoDB;
