CREATE TABLE `inslt` (
   LossTriangleValueID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossPeriodID int unsigned not null,
   Months float,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossTriangleValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslt_l` (
   _RecordID int unsigned not null auto_increment,
   LossTriangleValueID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossPeriodID int unsigned not null,
   Months float,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossTriangleValueID
   )
) TYPE=InnoDB;
