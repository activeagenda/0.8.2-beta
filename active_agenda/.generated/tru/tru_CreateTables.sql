CREATE TABLE `tru` (
   TrustAccountID int unsigned auto_increment not null,
   OrganizationID int,
   AccountName varchar(128),
   TrustAccountNumber varchar(50),
   AccountDesc text,
   TrustAmount decimal(12,4),
   AccountStatusID int,
   AccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustAccountID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tru_l` (
   _RecordID int unsigned not null auto_increment,
   TrustAccountID int unsigned  not null,
   OrganizationID int,
   AccountName varchar(128),
   TrustAccountNumber varchar(50),
   AccountDesc text,
   TrustAmount decimal(12,4),
   AccountStatusID int,
   AccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustAccountID
   )
) TYPE=InnoDB;
