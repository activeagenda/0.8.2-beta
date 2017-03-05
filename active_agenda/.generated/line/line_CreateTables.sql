CREATE TABLE `line` (
   EarningsID int unsigned auto_increment not null,
   LossInjuryID int,
   StartDate date,
   EndDate date,
   Amount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EarningsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `line_l` (
   _RecordID int unsigned not null auto_increment,
   EarningsID int unsigned  not null,
   LossInjuryID int,
   StartDate date,
   EndDate date,
   Amount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EarningsID
   )
) TYPE=InnoDB;
