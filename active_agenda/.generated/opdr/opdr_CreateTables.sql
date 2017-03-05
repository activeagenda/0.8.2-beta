CREATE TABLE `opdr` (
   RevenueID int unsigned auto_increment not null,
   ProdServID int unsigned not null,
   BeginDate date,
   EndDate date,
   RevenueAmount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RevenueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdr_l` (
   _RecordID int unsigned not null auto_increment,
   RevenueID int unsigned  not null,
   ProdServID int unsigned not null,
   BeginDate date,
   EndDate date,
   RevenueAmount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RevenueID
   )
) TYPE=InnoDB;
