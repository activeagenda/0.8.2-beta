CREATE TABLE `insli` (
   LossIncreaseID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossTriangleValue1ID int unsigned not null,
   LossTriangleValue2ID int unsigned not null,
   RateofIncrease float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossIncreaseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insli_l` (
   _RecordID int unsigned not null auto_increment,
   LossIncreaseID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossTriangleValue1ID int unsigned not null,
   LossTriangleValue2ID int unsigned not null,
   RateofIncrease float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossIncreaseID
   )
) TYPE=InnoDB;
