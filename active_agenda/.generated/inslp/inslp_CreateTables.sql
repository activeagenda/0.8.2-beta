CREATE TABLE `inslp` (
   LossPeriodID int unsigned auto_increment not null,
   LossPeriodLabel varchar(5),
   StartDate date,
   EndDate date,
   InflationRate float,
   LossPeriodDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossPeriodID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslp_l` (
   _RecordID int unsigned not null auto_increment,
   LossPeriodID int unsigned  not null,
   LossPeriodLabel varchar(5),
   StartDate date,
   EndDate date,
   InflationRate float,
   LossPeriodDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossPeriodID
   )
) TYPE=InnoDB;
