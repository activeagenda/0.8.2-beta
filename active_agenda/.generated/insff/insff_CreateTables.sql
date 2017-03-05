CREATE TABLE `insff` (
   FinalFactorID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossFactorID int unsigned not null,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FinalFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insff_l` (
   _RecordID int unsigned not null auto_increment,
   FinalFactorID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossFactorID int unsigned not null,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FinalFactorID
   )
) TYPE=InnoDB;
