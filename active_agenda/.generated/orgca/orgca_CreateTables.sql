CREATE TABLE `orgca` (
   AdditionalInsuredID int unsigned auto_increment not null,
   CertificateID int unsigned not null,
   OrganizationID int unsigned,
   Circumstances text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AdditionalInsuredID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgca_l` (
   _RecordID int unsigned not null auto_increment,
   AdditionalInsuredID int unsigned  not null,
   CertificateID int unsigned not null,
   OrganizationID int unsigned,
   Circumstances text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AdditionalInsuredID
   )
) TYPE=InnoDB;
