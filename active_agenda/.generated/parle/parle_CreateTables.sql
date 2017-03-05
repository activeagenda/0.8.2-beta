CREATE TABLE `parle` (
   LocalPartnerExpectationID int unsigned auto_increment not null,
   PartnershipID int unsigned not null,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocalPartnerExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parle_l` (
   _RecordID int unsigned not null auto_increment,
   LocalPartnerExpectationID int unsigned  not null,
   PartnershipID int unsigned not null,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocalPartnerExpectationID
   )
) TYPE=InnoDB;
