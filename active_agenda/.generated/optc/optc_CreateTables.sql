CREATE TABLE `optc` (
   OpportunityConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   Estimate decimal(12,4),
   OpportunityConsiderationTitle varchar(128),
   OpportunityConsiderationDescription text,
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OpportunityConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `optc_l` (
   _RecordID int unsigned not null auto_increment,
   OpportunityConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   Estimate decimal(12,4),
   OpportunityConsiderationTitle varchar(128),
   OpportunityConsiderationDescription text,
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OpportunityConsiderationID
   )
) TYPE=InnoDB;
