CREATE TABLE `par` (
   PartnershipID int unsigned auto_increment not null,
   OrganizationID int,
   PartnershipTitle varchar(128),
   PartnershipNumber varchar(25),
   PartnershipPolicyID int unsigned not null,
   PartnershipPurpose text,
   PartnershipScope text,
   CommitmentStatement text,
   PartnershipStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `par_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipID int unsigned  not null,
   OrganizationID int,
   PartnershipTitle varchar(128),
   PartnershipNumber varchar(25),
   PartnershipPolicyID int unsigned not null,
   PartnershipPurpose text,
   PartnershipScope text,
   CommitmentStatement text,
   PartnershipStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipID
   )
) TYPE=InnoDB;
