CREATE TABLE `supt` (
   SupplierTypeID int unsigned auto_increment not null,
   SupplierCategoryID int,
   SupplierTypeTitle varchar(128),
   SupplierTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `supt_l` (
   _RecordID int unsigned not null auto_increment,
   SupplierTypeID int unsigned  not null,
   SupplierCategoryID int,
   SupplierTypeTitle varchar(128),
   SupplierTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupplierTypeID
   )
) TYPE=InnoDB;
