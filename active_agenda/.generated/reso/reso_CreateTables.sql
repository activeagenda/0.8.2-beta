CREATE TABLE `reso` (
   ResourceAssignID int unsigned auto_increment not null,
   ResourceID int unsigned not null,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceAssignID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `reso_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceAssignID int unsigned  not null,
   ResourceID int unsigned not null,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceAssignID
   )
) TYPE=InnoDB;
