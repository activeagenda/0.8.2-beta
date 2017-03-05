CREATE TABLE `orgl` (
   LocationID int unsigned auto_increment not null,
   OrganizationID int unsigned not null,
   LocationTypeID int unsigned not null,
   LocationName varchar(128),
   LocationDesc text,
   LocationNumber varchar(50),
   RoomID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgl_l` (
   _RecordID int unsigned not null auto_increment,
   LocationID int unsigned  not null,
   OrganizationID int unsigned not null,
   LocationTypeID int unsigned not null,
   LocationName varchar(128),
   LocationDesc text,
   LocationNumber varchar(50),
   RoomID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationID
   )
) TYPE=InnoDB;
