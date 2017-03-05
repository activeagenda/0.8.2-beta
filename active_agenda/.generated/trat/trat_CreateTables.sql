CREATE TABLE `trat` (
   TrainingTypeID int auto_increment not null,
   TrainingCategoryID int,
   TrainingTypeTitle varchar(128),
   TrainingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trat_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingTypeID int  not null,
   TrainingCategoryID int,
   TrainingTypeTitle varchar(128),
   TrainingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingTypeID
   )
) TYPE=InnoDB;
