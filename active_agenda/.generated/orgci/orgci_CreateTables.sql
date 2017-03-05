CREATE TABLE `orgci` (
   CertificateID int unsigned auto_increment not null,
   NamedOrgID int unsigned,
   CarrierID int unsigned,
   PolicyTypeID int unsigned,
   CertificateTitle varchar(128),
   CertificateNo varchar(128),
   CoverageAmount decimal(12,4),
   CertificateAttached bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertificateID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgci_l` (
   _RecordID int unsigned not null auto_increment,
   CertificateID int unsigned  not null,
   NamedOrgID int unsigned,
   CarrierID int unsigned,
   PolicyTypeID int unsigned,
   CertificateTitle varchar(128),
   CertificateNo varchar(128),
   CoverageAmount decimal(12,4),
   CertificateAttached bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertificateID
   )
) TYPE=InnoDB;
