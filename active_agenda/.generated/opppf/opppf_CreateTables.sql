CREATE TABLE `opppf` (
   PermitFeeID int unsigned auto_increment not null,
   OppPermitID int,
   PermitFeeTypeID int,
   Amount decimal(12,4),
   FeeExplan varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitFeeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opppf_l` (
   _RecordID int unsigned not null auto_increment,
   PermitFeeID int unsigned  not null,
   OppPermitID int,
   PermitFeeTypeID int,
   Amount decimal(12,4),
   FeeExplan varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitFeeID
   )
) TYPE=InnoDB;
