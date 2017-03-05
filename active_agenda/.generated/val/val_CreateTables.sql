CREATE TABLE `val` (
   ValueID int unsigned auto_increment not null,
   OrganizationID int,
   ValueTitle varchar(128),
   ValueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `val_l` (
   _RecordID int unsigned not null auto_increment,
   ValueID int unsigned  not null,
   OrganizationID int,
   ValueTitle varchar(128),
   ValueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValueID
   )
) TYPE=InnoDB;
