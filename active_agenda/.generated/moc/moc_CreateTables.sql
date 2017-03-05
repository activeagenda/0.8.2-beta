CREATE TABLE `moc` (
   ManagedChangeID int unsigned auto_increment not null,
   ChangeGuidelineID int unsigned not null,
   ManagedChangeTitle text,
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   OrganizationID int,
   ManagedChangeStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ManagedChangeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `moc_l` (
   _RecordID int unsigned not null auto_increment,
   ManagedChangeID int unsigned  not null,
   ChangeGuidelineID int unsigned not null,
   ManagedChangeTitle text,
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   OrganizationID int,
   ManagedChangeStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ManagedChangeID
   )
) TYPE=InnoDB;
