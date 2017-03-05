CREATE TABLE `insld` (
   LossDevelopmentFactorID int unsigned auto_increment not null,
   OrganizationID int,
   DevelopmentFactorsTitle varchar(128),
   DevelopmentTypeID int,
   PolicyTypeID int,
   LDFDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossDevelopmentFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insld_l` (
   _RecordID int unsigned not null auto_increment,
   LossDevelopmentFactorID int unsigned  not null,
   OrganizationID int,
   DevelopmentFactorsTitle varchar(128),
   DevelopmentTypeID int,
   PolicyTypeID int,
   LDFDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossDevelopmentFactorID
   )
) TYPE=InnoDB;
