CREATE TABLE `gloa` (
   GlossaryAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GlossaryID int unsigned not null,
   SpecialInterpretation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GlossaryAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gloa_l` (
   _RecordID int unsigned not null auto_increment,
   GlossaryAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GlossaryID int unsigned not null,
   SpecialInterpretation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GlossaryAssocID
   )
) TYPE=InnoDB;
