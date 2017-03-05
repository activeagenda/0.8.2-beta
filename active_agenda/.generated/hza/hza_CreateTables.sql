CREATE TABLE `hza` (
   HazardID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LocationOrganizationID int,
   HazardTypeID int unsigned not null,
   HazardTitle varchar(128),
   HazardDesc text,
   HazardStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hza_l` (
   _RecordID int unsigned not null auto_increment,
   HazardID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LocationOrganizationID int,
   HazardTypeID int unsigned not null,
   HazardTitle varchar(128),
   HazardDesc text,
   HazardStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardID
   )
) TYPE=InnoDB;
