CREATE TABLE `ictas` (
   IncentiveAssocID int unsigned auto_increment not null,
   IncentiveID int,
   OrganizationID int,
   IncentiveTypeID int,
   AverageValue decimal(12,4),
   IncentiveStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ictas_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveAssocID int unsigned  not null,
   IncentiveID int,
   OrganizationID int,
   IncentiveTypeID int,
   AverageValue decimal(12,4),
   IncentiveStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveAssocID
   )
) TYPE=InnoDB;
