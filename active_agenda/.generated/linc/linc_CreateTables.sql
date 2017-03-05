CREATE TABLE `linc` (
   InjuryIllnessCategoryID int unsigned auto_increment not null,
   InjIllCategoryID int,
   CategoryDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryIllnessCategoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linc_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryIllnessCategoryID int unsigned  not null,
   InjIllCategoryID int,
   CategoryDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryIllnessCategoryID
   )
) TYPE=InnoDB;
