CREATE TABLE `cosc` (
   CostConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   CostConsiderationTitle varchar(128),
   CostConsiderationDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cosc_l` (
   _RecordID int unsigned not null auto_increment,
   CostConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   CostConsiderationTitle varchar(128),
   CostConsiderationDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostConsiderationID
   )
) TYPE=InnoDB;
