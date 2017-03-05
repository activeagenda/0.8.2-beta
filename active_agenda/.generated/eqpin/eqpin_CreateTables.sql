CREATE TABLE `eqpin` (
   EquipmentInventoryID int unsigned auto_increment not null,
   EquipmentID int,
   OrganizationID int,
   TrackingNumber varchar(10),
   EquipmentStorageMethodID int,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentInventoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpin_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentInventoryID int unsigned  not null,
   EquipmentID int,
   OrganizationID int,
   TrackingNumber varchar(10),
   EquipmentStorageMethodID int,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentInventoryID
   )
) TYPE=InnoDB;
