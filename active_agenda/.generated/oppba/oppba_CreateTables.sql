CREATE TABLE `oppba` (
   PermitBuildID int unsigned auto_increment not null,
   OppPermitID int,
   OrganizationID int,
   BuildingID int,
   PermitBuildAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitBuildID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppba_l` (
   _RecordID int unsigned not null auto_increment,
   PermitBuildID int unsigned  not null,
   OppPermitID int,
   OrganizationID int,
   BuildingID int,
   PermitBuildAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitBuildID
   )
) TYPE=InnoDB;
