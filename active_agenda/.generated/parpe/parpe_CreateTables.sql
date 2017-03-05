CREATE TABLE `parpe` (
   PolicyExpectationID int unsigned auto_increment not null,
   PartnershipPolicyID int,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parpe_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyExpectationID int unsigned  not null,
   PartnershipPolicyID int,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyExpectationID
   )
) TYPE=InnoDB;
