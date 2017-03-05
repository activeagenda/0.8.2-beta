CREATE TABLE `parp` (
   PartnershipPolicyID int unsigned auto_increment not null,
   PolicyOrganizationID int,
   PolicyTitleID int,
   PolicyPurpose text,
   PolicyScope text,
   PolicyNumber varchar(25),
   CommitmentStatement text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipPolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parp_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipPolicyID int unsigned  not null,
   PolicyOrganizationID int,
   PolicyTitleID int,
   PolicyPurpose text,
   PolicyScope text,
   PolicyNumber varchar(25),
   CommitmentStatement text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipPolicyID
   )
) TYPE=InnoDB;
