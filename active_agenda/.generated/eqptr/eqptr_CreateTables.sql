CREATE TABLE `eqptr` (
   EquipmentTransactionID int unsigned auto_increment not null,
   EquipmentInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqptr_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentTransactionID int unsigned  not null,
   EquipmentInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentTransactionID
   )
) TYPE=InnoDB;
