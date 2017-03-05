CREATE TABLE `opt` (
   OpportunityEstimateID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OwnerOrganizationID int,
   OpportunityEstimateTitle varchar(128),
   OpportunityEstimateDescription text,
   Estimate decimal(12,4),
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OpportunityEstimateID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opt_l` (
   _RecordID int unsigned not null auto_increment,
   OpportunityEstimateID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OwnerOrganizationID int,
   OpportunityEstimateTitle varchar(128),
   OpportunityEstimateDescription text,
   Estimate decimal(12,4),
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OpportunityEstimateID
   )
) TYPE=InnoDB;
