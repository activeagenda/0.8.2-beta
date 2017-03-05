CREATE TABLE `opdps` (
   ProdServID int unsigned auto_increment not null,
   OrganizationID int,
   ProductServiceTypeID int unsigned not null,
   ProdServName varchar(128),
   ProdServDesc text,
   ProdServOrigDate date,
   ProdServTrackingNo varchar(128),
   SKUNo varchar(128),
   ProductLifeCycleID int,
   ProdServStatusID int,
   ProdServDiscontinued bool default 0,
   DiscontinuedDate date,
   TraceMarkings text,
   TraceMarkingDestructibility bool default 0,
   ProductionRecords text,
   ProcessRecords text,
   TraceMarkingSample bool default 0,
   ProductionRecordSample bool default 0,
   ShippingRecordSample bool default 0,
   SalesRecordSample bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ProdServID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdps_l` (
   _RecordID int unsigned not null auto_increment,
   ProdServID int unsigned  not null,
   OrganizationID int,
   ProductServiceTypeID int unsigned not null,
   ProdServName varchar(128),
   ProdServDesc text,
   ProdServOrigDate date,
   ProdServTrackingNo varchar(128),
   SKUNo varchar(128),
   ProductLifeCycleID int,
   ProdServStatusID int,
   ProdServDiscontinued bool default 0,
   DiscontinuedDate date,
   TraceMarkings text,
   TraceMarkingDestructibility bool default 0,
   ProductionRecords text,
   ProcessRecords text,
   TraceMarkingSample bool default 0,
   ProductionRecordSample bool default 0,
   ShippingRecordSample bool default 0,
   SalesRecordSample bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ProdServID
   )
) TYPE=InnoDB;
