CREATE TABLE `buda` (
   AccountID int unsigned auto_increment not null,
   BudgetID int unsigned not null,
   AccountOrganizationID int,
   DepartmentID int,
   AccountTypeID int unsigned not null,
   AccountNumber varchar(50),
   AccountName varchar(128),
   AccountDesc text,
   AccountAmount decimal(12,4),
   AccountStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `buda_l` (
   _RecordID int unsigned not null auto_increment,
   AccountID int unsigned  not null,
   BudgetID int unsigned not null,
   AccountOrganizationID int,
   DepartmentID int,
   AccountTypeID int unsigned not null,
   AccountNumber varchar(50),
   AccountName varchar(128),
   AccountDesc text,
   AccountAmount decimal(12,4),
   AccountStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AccountID
   )
) TYPE=InnoDB;
