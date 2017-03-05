CREATE TABLE `trac` (
   CertificationID int unsigned auto_increment not null,
   OrganizationID int,
   CertificationTypeID int,
   CertificationTitle varchar(128),
   CertificationDesc text,
   CertificationDuration float,
   CertificationDurationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trac_l` (
   _RecordID int unsigned not null auto_increment,
   CertificationID int unsigned  not null,
   OrganizationID int,
   CertificationTypeID int,
   CertificationTitle varchar(128),
   CertificationDesc text,
   CertificationDuration float,
   CertificationDurationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertificationID
   )
) TYPE=InnoDB;
