CREATE TABLE `opdh` (
   HoursWorkedID int unsigned auto_increment not null,
   OrganizationID int,
   PayrollClassTypeID int,
   BeginDate date,
   EndDate date,
   Hours float,
   People float,
   Comment text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HoursWorkedID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdh_l` (
   _RecordID int unsigned not null auto_increment,
   HoursWorkedID int unsigned  not null,
   OrganizationID int,
   PayrollClassTypeID int,
   BeginDate date,
   EndDate date,
   Hours float,
   People float,
   Comment text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HoursWorkedID
   )
) TYPE=InnoDB;
