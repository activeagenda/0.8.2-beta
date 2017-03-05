CREATE TABLE `supc` (
   SupplierConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   SupplierConsiderationTitle varchar(128),
   SupplierConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `supc_l` (
   _RecordID int unsigned not null auto_increment,
   SupplierConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   SupplierConsiderationTitle varchar(128),
   SupplierConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupplierConsiderationID
   )
) TYPE=InnoDB;
