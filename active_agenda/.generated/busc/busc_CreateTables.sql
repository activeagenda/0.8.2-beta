CREATE TABLE `busc` (
   BusContConsiderID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   BusContConsiderTitle varchar(128),
   BusContConsiderDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusContConsiderID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `busc_l` (
   _RecordID int unsigned not null auto_increment,
   BusContConsiderID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   BusContConsiderTitle varchar(128),
   BusContConsiderDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusContConsiderID
   )
) TYPE=InnoDB;
