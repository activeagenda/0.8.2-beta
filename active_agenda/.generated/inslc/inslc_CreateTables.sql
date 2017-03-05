CREATE TABLE `inslc` (
   PeriodLossCostsID int unsigned auto_increment not null,
   LossTriangleValueID int unsigned not null,
   PercentValue float,
   OrganizationID int,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PeriodLossCostsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslc_l` (
   _RecordID int unsigned not null auto_increment,
   PeriodLossCostsID int unsigned  not null,
   LossTriangleValueID int unsigned not null,
   PercentValue float,
   OrganizationID int,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PeriodLossCostsID
   )
) TYPE=InnoDB;
