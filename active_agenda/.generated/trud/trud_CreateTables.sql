CREATE TABLE `trud` (
   TrustDistributionID int unsigned auto_increment not null,
   TrustAccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustDistributionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trud_l` (
   _RecordID int unsigned not null auto_increment,
   TrustDistributionID int unsigned  not null,
   TrustAccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustDistributionID
   )
) TYPE=InnoDB;
