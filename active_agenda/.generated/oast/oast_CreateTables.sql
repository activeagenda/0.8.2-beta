CREATE TABLE `oast` (
   OtherAssetTransactionID int unsigned auto_increment not null,
   OtherAssetInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherAssetTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oast_l` (
   _RecordID int unsigned not null auto_increment,
   OtherAssetTransactionID int unsigned  not null,
   OtherAssetInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherAssetTransactionID
   )
) TYPE=InnoDB;
