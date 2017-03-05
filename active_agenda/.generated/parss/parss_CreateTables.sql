CREATE TABLE `parss` (
   SharedExpectationScoreID int unsigned auto_increment not null,
   SharedPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   PartnershipAuditID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedExpectationScoreID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parss_l` (
   _RecordID int unsigned not null auto_increment,
   SharedExpectationScoreID int unsigned  not null,
   SharedPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   PartnershipAuditID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedExpectationScoreID
   )
) TYPE=InnoDB;
