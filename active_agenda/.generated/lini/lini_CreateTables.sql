CREATE TABLE `lini` (
   InjuryIllnessID int unsigned auto_increment not null,
   LossInjuryID int,
   InjuryNatureID int,
   BodyPartID int,
   BodyPartCategoryID int,
   BodyLocationID int,
   InjuryIllnessDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryIllnessID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lini_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryIllnessID int unsigned  not null,
   LossInjuryID int,
   InjuryNatureID int,
   BodyPartID int,
   BodyPartCategoryID int,
   BodyLocationID int,
   InjuryIllnessDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryIllnessID
   )
) TYPE=InnoDB;
