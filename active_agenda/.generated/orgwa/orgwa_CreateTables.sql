CREATE TABLE `orgwa` (
   WorkAvailabilityID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   WorkAvailabilityGroupID int,
   WorkUnavailabilityTypeID int unsigned not null,
   Explanation text,
   Days int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkAvailabilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgwa_l` (
   _RecordID int unsigned not null auto_increment,
   WorkAvailabilityID int unsigned  not null,
   OrganizationID int unsigned,
   WorkAvailabilityGroupID int,
   WorkUnavailabilityTypeID int unsigned not null,
   Explanation text,
   Days int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkAvailabilityID
   )
) TYPE=InnoDB;
