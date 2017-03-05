CREATE TABLE `trut` (
   TrustTransferID int unsigned auto_increment not null,
   FromDistributionID int,
   ToDistributionID int,
   TrustAccountTransferTypeID int,
   TransferAmount decimal(12,4),
   TransferDesc text,
   TransferDocNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustTransferID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trut_l` (
   _RecordID int unsigned not null auto_increment,
   TrustTransferID int unsigned  not null,
   FromDistributionID int,
   ToDistributionID int,
   TrustAccountTransferTypeID int,
   TransferAmount decimal(12,4),
   TransferDesc text,
   TransferDocNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustTransferID
   )
) TYPE=InnoDB;
