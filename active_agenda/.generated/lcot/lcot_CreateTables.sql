CREATE TABLE `lcot` (
   LossCostTypeID int unsigned auto_increment not null,
   CostCategoryID int,
   CostType varchar(128),
   CostCode varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lcot_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostTypeID int unsigned  not null,
   CostCategoryID int,
   CostType varchar(128),
   CostCode varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostTypeID
   )
) TYPE=InnoDB;
