CREATE TABLE `parse` (
   SharedPartnerExpectationID int unsigned auto_increment not null,
   PolicyExpectationID int unsigned not null,
   PartnershipPolicyID int,
   PartnershipID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedPartnerExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parse_l` (
   _RecordID int unsigned not null auto_increment,
   SharedPartnerExpectationID int unsigned  not null,
   PolicyExpectationID int unsigned not null,
   PartnershipPolicyID int,
   PartnershipID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedPartnerExpectationID
   )
) TYPE=InnoDB;
