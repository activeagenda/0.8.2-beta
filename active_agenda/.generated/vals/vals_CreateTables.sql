CREATE TABLE `vals` (
   SharedValueID int unsigned auto_increment not null,
   ValueID int,
   SharingOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vals_l` (
   _RecordID int unsigned not null auto_increment,
   SharedValueID int unsigned  not null,
   ValueID int,
   SharingOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedValueID
   )
) TYPE=InnoDB;
