CREATE TABLE `chmtr` (
   ChemicalTransactionID int unsigned auto_increment not null,
   ChemicalInventoryID int,
   TransactionTypeID int,
   TransactionAmount int,
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
      ChemicalTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmtr_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTransactionID int unsigned  not null,
   ChemicalInventoryID int,
   TransactionTypeID int,
   TransactionAmount int,
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
      ChemicalTransactionID
   )
) TYPE=InnoDB;
