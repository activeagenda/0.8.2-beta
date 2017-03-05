CREATE TABLE `tram` (
   MaterialID int unsigned auto_increment not null,
   CourseMaterialTypeID int,
   MaterialName varchar(128),
   MaterialDesc text,
   MaterialNo varchar(50),
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MaterialID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tram_l` (
   _RecordID int unsigned not null auto_increment,
   MaterialID int unsigned  not null,
   CourseMaterialTypeID int,
   MaterialName varchar(128),
   MaterialDesc text,
   MaterialNo varchar(50),
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MaterialID
   )
) TYPE=InnoDB;
