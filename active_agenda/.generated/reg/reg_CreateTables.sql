CREATE TABLE `reg` (
   RegulationID int unsigned auto_increment not null,
   OrganizationID int,
   CountryID int unsigned not null,
   AgencyLevelID int,
   AgencyID int,
   RegTitle varchar(128),
   RegCode varchar(128),
   RegSection varchar(128),
   RegScope text,
   RegDescription text,
   RegURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `reg_l` (
   _RecordID int unsigned not null auto_increment,
   RegulationID int unsigned  not null,
   OrganizationID int,
   CountryID int unsigned not null,
   AgencyLevelID int,
   AgencyID int,
   RegTitle varchar(128),
   RegCode varchar(128),
   RegSection varchar(128),
   RegScope text,
   RegDescription text,
   RegURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulationID
   )
) TYPE=InnoDB;
