CREATE TABLE `rskl` (
   LikelihoodID int unsigned auto_increment not null,
   RiskLikelihoodTerm varchar(50),
   RiskLikelihood varchar(128),
   LikelihoodValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LikelihoodID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskl_l` (
   _RecordID int unsigned not null auto_increment,
   LikelihoodID int unsigned  not null,
   RiskLikelihoodTerm varchar(50),
   RiskLikelihood varchar(128),
   LikelihoodValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LikelihoodID
   )
) TYPE=InnoDB;
