CREATE TABLE `ksasc` (
   CapabilityID int unsigned auto_increment not null,
   AreaID int unsigned not null,
   CapabilityTitle varchar(128),
   CapabilityDesc text,
   CapabilityAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CapabilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksasc_l` (
   _RecordID int unsigned not null auto_increment,
   CapabilityID int unsigned  not null,
   AreaID int unsigned not null,
   CapabilityTitle varchar(128),
   CapabilityDesc text,
   CapabilityAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CapabilityID
   )
) TYPE=InnoDB;
