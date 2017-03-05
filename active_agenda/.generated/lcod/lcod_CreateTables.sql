CREATE TABLE `lcod` (
   LossCostDetailID int unsigned auto_increment not null,
   LossCostID int unsigned not null,
   LossCostTypeID int,
   Incurred decimal(12,4),
   Payments decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostDetailID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lcod_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostDetailID int unsigned  not null,
   LossCostID int unsigned not null,
   LossCostTypeID int,
   Incurred decimal(12,4),
   Payments decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostDetailID
   )
) TYPE=InnoDB;
