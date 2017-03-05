CREATE TABLE `mocg` (
   ChangeGuidelineID int unsigned auto_increment not null,
   PolicyOrganizationID int,
   ChangeCategoryID int,
   GuidelineTitle varchar(128),
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   GuidelineStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChangeGuidelineID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mocg_l` (
   _RecordID int unsigned not null auto_increment,
   ChangeGuidelineID int unsigned  not null,
   PolicyOrganizationID int,
   ChangeCategoryID int,
   GuidelineTitle varchar(128),
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   GuidelineStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChangeGuidelineID
   )
) TYPE=InnoDB;
