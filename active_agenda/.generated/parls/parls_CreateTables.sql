CREATE TABLE `parls` (
   LocalExpectationScoreID int unsigned auto_increment not null,
   PartnershipAuditID int unsigned not null,
   LocalPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocalExpectationScoreID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parls_l` (
   _RecordID int unsigned not null auto_increment,
   LocalExpectationScoreID int unsigned  not null,
   PartnershipAuditID int unsigned not null,
   LocalPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocalExpectationScoreID
   )
) TYPE=InnoDB;
