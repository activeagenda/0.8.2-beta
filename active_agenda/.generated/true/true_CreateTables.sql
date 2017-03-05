CREATE TABLE `true` (
   ExpenditureID int unsigned auto_increment not null,
   TrustDistributionID int unsigned not null,
   ProcessObstacleTypeID int,
   ObstacleDesc text,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentApproachID int,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExpenditureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `true_l` (
   _RecordID int unsigned not null auto_increment,
   ExpenditureID int unsigned  not null,
   TrustDistributionID int unsigned not null,
   ProcessObstacleTypeID int,
   ObstacleDesc text,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentApproachID int,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExpenditureID
   )
) TYPE=InnoDB;
