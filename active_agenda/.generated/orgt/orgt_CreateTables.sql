CREATE TABLE `orgt` (
   OrganizationTypeID int unsigned auto_increment not null,
   OrganizationCategoryID int,
   OrganizationTypeTitle varchar(128),
   OrganizationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrganizationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgt_l` (
   _RecordID int unsigned not null auto_increment,
   OrganizationTypeID int unsigned  not null,
   OrganizationCategoryID int,
   OrganizationTypeTitle varchar(128),
   OrganizationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrganizationTypeID
   )
) TYPE=InnoDB;
