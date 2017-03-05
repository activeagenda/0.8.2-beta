CREATE TABLE `orgjk` (
   KeyRelationshipID int unsigned auto_increment not null,
   OrgJobTitleID int unsigned not null,
   RelatedJobTitleID int,
   RelationshipDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      KeyRelationshipID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjk_l` (
   _RecordID int unsigned not null auto_increment,
   KeyRelationshipID int unsigned  not null,
   OrgJobTitleID int unsigned not null,
   RelatedJobTitleID int,
   RelationshipDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      KeyRelationshipID
   )
) TYPE=InnoDB;
