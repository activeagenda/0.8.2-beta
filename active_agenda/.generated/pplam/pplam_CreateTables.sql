CREATE TABLE `pplam` (
   AvailabilityModificationID int unsigned auto_increment not null,
   EmployeePersonID int unsigned,
   AvailabilityModificationTypeID int unsigned not null,
   Explanation text,
   DaysNotScheduled float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AvailabilityModificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplam_l` (
   _RecordID int unsigned not null auto_increment,
   AvailabilityModificationID int unsigned  not null,
   EmployeePersonID int unsigned,
   AvailabilityModificationTypeID int unsigned not null,
   Explanation text,
   DaysNotScheduled float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AvailabilityModificationID
   )
) TYPE=InnoDB;
