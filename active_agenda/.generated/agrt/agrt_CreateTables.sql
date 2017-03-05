CREATE TABLE `agrt` (
   AgreementTypeID int unsigned auto_increment not null,
   AgreementCategoryID int,
   AgreementTypeTitle varchar(128),
   AgreementTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agrt_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementTypeID int unsigned  not null,
   AgreementCategoryID int,
   AgreementTypeTitle varchar(128),
   AgreementTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementTypeID
   )
) TYPE=InnoDB;
