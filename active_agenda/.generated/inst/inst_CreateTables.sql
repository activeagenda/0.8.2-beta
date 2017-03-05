CREATE TABLE `inst` (
   InsuranceTypeID int unsigned auto_increment not null,
   InsuranceCategoryID int,
   InsuranceTypeTitle varchar(128),
   InsuranceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inst_l` (
   _RecordID int unsigned not null auto_increment,
   InsuranceTypeID int unsigned  not null,
   InsuranceCategoryID int,
   InsuranceTypeTitle varchar(128),
   InsuranceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuranceTypeID
   )
) TYPE=InnoDB;
