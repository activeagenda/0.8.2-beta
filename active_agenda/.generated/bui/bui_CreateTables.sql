CREATE TABLE `bui` (
   BuildingID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   BuildingTypeID int unsigned not null,
   BuildingName varchar(50),
   BuildingDesc text,
   Address1 varchar(128),
   Address2 varchar(128),
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(25),
   BuildingClassID int unsigned,
   BuildingQualityID int unsigned,
   ConstructionTypeID int unsigned,
   ConstructionYear varchar(5),
   BuildingSize int,
   BuildSizeUnitsID int,
   LotSize int,
   LotSizeUnitsID int,
   FireSystem text,
   EstimatedBuildingValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BuildingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bui_l` (
   _RecordID int unsigned not null auto_increment,
   BuildingID int unsigned  not null,
   OrganizationID int unsigned,
   BuildingTypeID int unsigned not null,
   BuildingName varchar(50),
   BuildingDesc text,
   Address1 varchar(128),
   Address2 varchar(128),
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(25),
   BuildingClassID int unsigned,
   BuildingQualityID int unsigned,
   ConstructionTypeID int unsigned,
   ConstructionYear varchar(5),
   BuildingSize int,
   BuildSizeUnitsID int,
   LotSize int,
   LotSizeUnitsID int,
   FireSystem text,
   EstimatedBuildingValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BuildingID
   )
) TYPE=InnoDB;
