CREATE TABLE `ksaae` (
   AreaID int unsigned auto_increment not null,
   OrganizationID int,
   KSAAreaID int,
   AreaTitle varchar(30),
   AreaDesc text,
   AreaAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AreaID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksaae_l` (
   _RecordID int unsigned not null auto_increment,
   AreaID int unsigned  not null,
   OrganizationID int,
   KSAAreaID int,
   AreaTitle varchar(30),
   AreaDesc text,
   AreaAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AreaID
   )
) TYPE=InnoDB;
