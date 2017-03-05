CREATE TABLE `rtcc` (
   RootCauseConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RootCauseConsiderationTitle varchar(128),
   RootCauseConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RootCauseConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtcc_l` (
   _RecordID int unsigned not null auto_increment,
   RootCauseConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RootCauseConsiderationTitle varchar(128),
   RootCauseConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RootCauseConsiderationID
   )
) TYPE=InnoDB;
