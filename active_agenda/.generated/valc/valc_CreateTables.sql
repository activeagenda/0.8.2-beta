CREATE TABLE `valc` (
   ValuesConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   ValueID int,
   ValueConsiderationTitle varchar(128),
   ValueConsiderationDescription text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValuesConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `valc_l` (
   _RecordID int unsigned not null auto_increment,
   ValuesConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   ValueID int,
   ValueConsiderationTitle varchar(128),
   ValueConsiderationDescription text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValuesConsiderationID
   )
) TYPE=InnoDB;
