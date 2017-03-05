CREATE TABLE `linbt` (
   BodyPartTypeID int unsigned auto_increment not null,
   BodyPartCategoryID int,
   PartType varchar(128),
   BodyPartTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BodyPartTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linbt_l` (
   _RecordID int unsigned not null auto_increment,
   BodyPartTypeID int unsigned  not null,
   BodyPartCategoryID int,
   PartType varchar(128),
   BodyPartTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BodyPartTypeID
   )
) TYPE=InnoDB;
