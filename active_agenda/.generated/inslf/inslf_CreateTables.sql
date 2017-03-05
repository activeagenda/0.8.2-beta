CREATE TABLE `inslf` (
   LossFactorID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   Months1ID int,
   Months2ID int,
   AverageRate float,
   IndustryStandardRate float,
   SelectedRate float,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslf_l` (
   _RecordID int unsigned not null auto_increment,
   LossFactorID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   Months1ID int,
   Months2ID int,
   AverageRate float,
   IndustryStandardRate float,
   SelectedRate float,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossFactorID
   )
) TYPE=InnoDB;
