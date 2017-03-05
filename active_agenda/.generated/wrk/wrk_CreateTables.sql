CREATE TABLE `wrk` (
   WorkOrderID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   WorkOrderNo varchar(30),
   WorkOrderTypeID int,
   WorkOrderTitle varchar(128),
   WorkOrderDesc text,
   PriorityID int,
   WorkOrderStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkOrderID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wrk_l` (
   _RecordID int unsigned not null auto_increment,
   WorkOrderID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   WorkOrderNo varchar(30),
   WorkOrderTypeID int,
   WorkOrderTitle varchar(128),
   WorkOrderDesc text,
   PriorityID int,
   WorkOrderStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkOrderID
   )
) TYPE=InnoDB;
