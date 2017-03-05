CREATE TABLE `ksa` (
   KSAID int unsigned auto_increment not null,
   CapabilityID int unsigned not null,
   KSATitle varchar(30),
   KSADesc text,
   KSAAbbr varchar(5),
   QualificationMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      KSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksa_l` (
   _RecordID int unsigned not null auto_increment,
   KSAID int unsigned  not null,
   CapabilityID int unsigned not null,
   KSATitle varchar(30),
   KSADesc text,
   KSAAbbr varchar(5),
   QualificationMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      KSAID
   )
) TYPE=InnoDB;
