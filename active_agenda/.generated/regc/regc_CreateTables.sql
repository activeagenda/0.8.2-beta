CREATE TABLE `regc` (
   RegulatoryConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RegulationID int,
   RegulatoryConsiderationTitle varchar(128),
   RegulatoryConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulatoryConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `regc_l` (
   _RecordID int unsigned not null auto_increment,
   RegulatoryConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RegulationID int,
   RegulatoryConsiderationTitle varchar(128),
   RegulatoryConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulatoryConsiderationID
   )
) TYPE=InnoDB;
