CREATE TABLE `lstt` (
   LossStatusTypeID int unsigned auto_increment not null,
   LossStatusCategoryID int,
   LossStatus varchar(128),
   LossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossStatusTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lstt_l` (
   _RecordID int unsigned not null auto_increment,
   LossStatusTypeID int unsigned  not null,
   LossStatusCategoryID int,
   LossStatus varchar(128),
   LossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossStatusTypeID
   )
) TYPE=InnoDB;
