CREATE TABLE `sup` (
   SupplierID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SupplierTypeID int unsigned not null,
   SupplierOrgID int,
   PrimarySupplier bool default 0,
   SupplierTitle varchar(128),
   ItemDescription text,
   RelationshipID int,
   SupplierRelationship bool default 0,
   ProductLiabilityCertificate bool default 0,
   LiabilityExpiration date,
   OrganizationID int,
   CatalogID int,
   CatalogPgNo varchar(10),
   PartNo varchar(30),
   ItemURL varchar(128),
   UnitCost decimal(12,4),
   BaseUnitID int,
   MinimumUnitOrder float,
   MinimumOrderUoMID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sup_l` (
   _RecordID int unsigned not null auto_increment,
   SupplierID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SupplierTypeID int unsigned not null,
   SupplierOrgID int,
   PrimarySupplier bool default 0,
   SupplierTitle varchar(128),
   ItemDescription text,
   RelationshipID int,
   SupplierRelationship bool default 0,
   ProductLiabilityCertificate bool default 0,
   LiabilityExpiration date,
   OrganizationID int,
   CatalogID int,
   CatalogPgNo varchar(10),
   PartNo varchar(30),
   ItemURL varchar(128),
   UnitCost decimal(12,4),
   BaseUnitID int,
   MinimumUnitOrder float,
   MinimumOrderUoMID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupplierID
   )
) TYPE=InnoDB;
