CREATE TABLE `ict` (
   IncentiveID int unsigned auto_increment not null,
   OrganizationID int,
   IncentiveCriteria varchar(128),
   IncentiveDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ict_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveID int unsigned  not null,
   OrganizationID int,
   IncentiveCriteria varchar(128),
   IncentiveDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveID
   )
) TYPE=InnoDB;
