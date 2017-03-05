CREATE TABLE `oppc` (
   PermitConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   PermitConsiderationTitle varchar(128),
   PermitConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppc_l` (
   _RecordID int unsigned not null auto_increment,
   PermitConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   PermitConsiderationTitle varchar(128),
   PermitConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitConsiderationID
   )
) TYPE=InnoDB;
