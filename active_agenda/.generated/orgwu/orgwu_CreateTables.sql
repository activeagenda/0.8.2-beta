CREATE TABLE `orgwu` (
   WorkUnavailabilityTypeID int unsigned auto_increment not null,
   UnavailabilityCategoryID int,
   UnavailabilityType varchar(128),
   UnavailabilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkUnavailabilityTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgwu_l` (
   _RecordID int unsigned not null auto_increment,
   WorkUnavailabilityTypeID int unsigned  not null,
   UnavailabilityCategoryID int,
   UnavailabilityType varchar(128),
   UnavailabilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkUnavailabilityTypeID
   )
) TYPE=InnoDB;
