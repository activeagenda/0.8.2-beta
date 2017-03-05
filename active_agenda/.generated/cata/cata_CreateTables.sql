CREATE TABLE `cata` (
   CatalogAssocID int unsigned auto_increment not null,
   CatalogID int,
   OrganizationID int,
   ApprovalID int,
   Conditions text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CatalogAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cata_l` (
   _RecordID int unsigned not null auto_increment,
   CatalogAssocID int unsigned  not null,
   CatalogID int,
   OrganizationID int,
   ApprovalID int,
   Conditions text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CatalogAssocID
   )
) TYPE=InnoDB;
