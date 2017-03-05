CREATE TABLE `lppo` (
   LossOtherAssetID int unsigned auto_increment not null,
   OrganizationID int,
   OtherAssetInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   AssetAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossOtherAssetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppo_l` (
   _RecordID int unsigned not null auto_increment,
   LossOtherAssetID int unsigned  not null,
   OrganizationID int,
   OtherAssetInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   AssetAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossOtherAssetID
   )
) TYPE=InnoDB;
