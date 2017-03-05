CREATE TABLE `cosex` (
   CostExposureID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   CostExposureTitle varchar(128),
   CostExposureDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetExposure bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cosex_l` (
   _RecordID int unsigned not null auto_increment,
   CostExposureID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   CostExposureTitle varchar(128),
   CostExposureDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetExposure bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostExposureID
   )
) TYPE=InnoDB;
