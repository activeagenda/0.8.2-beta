CREATE TABLE `ins` (
   PolicyID int unsigned auto_increment not null,
   PolicyNumber varchar(128),
   IntegrationKey varchar(128),
   OrganizationID int,
   RetentionAcct bool default 0,
   InsuranceTypeID int unsigned not null,
   PolicyName varchar(128),
   PolicyDesc text,
   PolicyStatusID int,
   CarrierNameID int,
   EstimatedPremium decimal(12,4),
   PremiumPaid decimal(12,4),
   PremiumValuationDate date,
   BrokerNameID int,
   BrokerageFee decimal(12,4),
   PercentofPremium float,
   ExcessCarrierID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ins_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyID int unsigned  not null,
   PolicyNumber varchar(128),
   IntegrationKey varchar(128),
   OrganizationID int,
   RetentionAcct bool default 0,
   InsuranceTypeID int unsigned not null,
   PolicyName varchar(128),
   PolicyDesc text,
   PolicyStatusID int,
   CarrierNameID int,
   EstimatedPremium decimal(12,4),
   PremiumPaid decimal(12,4),
   PremiumValuationDate date,
   BrokerNameID int,
   BrokerageFee decimal(12,4),
   PercentofPremium float,
   ExcessCarrierID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyID
   )
) TYPE=InnoDB;
