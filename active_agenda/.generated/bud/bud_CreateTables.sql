CREATE TABLE `bud` (
   BudgetID int unsigned auto_increment not null,
   OrganizationID int,
   BudgetCategoryID int,
   BudgetNumber varchar(50),
   BudgetTitle varchar(128),
   BudgetDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BudgetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bud_l` (
   _RecordID int unsigned not null auto_increment,
   BudgetID int unsigned  not null,
   OrganizationID int,
   BudgetCategoryID int,
   BudgetNumber varchar(50),
   BudgetTitle varchar(128),
   BudgetDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BudgetID
   )
) TYPE=InnoDB;
