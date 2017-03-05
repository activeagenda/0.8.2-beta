CREATE TABLE `cat` (
   CatalogID int unsigned auto_increment not null,
   CatalogTypeID int,
   CatalogCompanyID int,
   CatalogTitle varchar(50),
   CatalogVolume varchar(10),
   CatalogDesc text,
   OrganizationID int,
   CatalogStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CatalogID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cat_l` (
   _RecordID int unsigned not null auto_increment,
   CatalogID int unsigned  not null,
   CatalogTypeID int,
   CatalogCompanyID int,
   CatalogTitle varchar(50),
   CatalogVolume varchar(10),
   CatalogDesc text,
   OrganizationID int,
   CatalogStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CatalogID
   )
) TYPE=InnoDB;
