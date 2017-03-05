CREATE TABLE `inso` (
   InsuredOrganizationID int unsigned auto_increment not null,
   OrganizationID int,
   PolicyID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuredOrganizationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inso_l` (
   _RecordID int unsigned not null auto_increment,
   InsuredOrganizationID int unsigned  not null,
   OrganizationID int,
   PolicyID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuredOrganizationID
   )
) TYPE=InnoDB;
