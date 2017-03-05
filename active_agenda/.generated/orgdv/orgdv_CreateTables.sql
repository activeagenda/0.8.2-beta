CREATE TABLE `orgdv` (
   DivisionID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   DivisionName varchar(50),
   DivisionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DivisionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgdv_l` (
   _RecordID int unsigned not null auto_increment,
   DivisionID int unsigned  not null,
   OrganizationID int unsigned,
   DivisionName varchar(50),
   DivisionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DivisionID
   )
) TYPE=InnoDB;
