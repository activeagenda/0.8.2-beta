CREATE TABLE `rsks` (
   SeverityID int unsigned auto_increment not null,
   RiskSeverityTerm varchar(50),
   RiskSeverity varchar(128),
   SeverityLowCost decimal(12,4),
   SeverityHighCost decimal(12,4),
   SeverityValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SeverityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsks_l` (
   _RecordID int unsigned not null auto_increment,
   SeverityID int unsigned  not null,
   RiskSeverityTerm varchar(50),
   RiskSeverity varchar(128),
   SeverityLowCost decimal(12,4),
   SeverityHighCost decimal(12,4),
   SeverityValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SeverityID
   )
) TYPE=InnoDB;
