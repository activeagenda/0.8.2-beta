CREATE TABLE `veha` (
   VehAssignmentID int unsigned auto_increment not null,
   VehicleID int,
   OrganizationID int,
   EstReturnDate datetime,
   Returned bool default 0,
   ActReturnDate datetime,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehAssignmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veha_l` (
   _RecordID int unsigned not null auto_increment,
   VehAssignmentID int unsigned  not null,
   VehicleID int,
   OrganizationID int,
   EstReturnDate datetime,
   Returned bool default 0,
   ActReturnDate datetime,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehAssignmentID
   )
) TYPE=InnoDB;
