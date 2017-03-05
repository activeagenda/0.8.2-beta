CREATE TABLE `cspt` (
   ConfinedSpaceTypeID int unsigned auto_increment not null,
   ConfinedSpaceCategoryID int,
   ConfinedSpaceTitle varchar(128),
   ConfinedSpaceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ConfinedSpaceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cspt_l` (
   _RecordID int unsigned not null auto_increment,
   ConfinedSpaceTypeID int unsigned  not null,
   ConfinedSpaceCategoryID int,
   ConfinedSpaceTitle varchar(128),
   ConfinedSpaceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ConfinedSpaceTypeID
   )
) TYPE=InnoDB;
