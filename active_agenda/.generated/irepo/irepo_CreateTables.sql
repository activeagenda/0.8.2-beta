CREATE TABLE `irepo` (
   PolicyID int unsigned auto_increment not null,
   IncidentReportID int,
   PartnershipID int,
   PolicyVariance text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `irepo_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyID int unsigned  not null,
   IncidentReportID int,
   PartnershipID int,
   PolicyVariance text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyID
   )
) TYPE=InnoDB;
