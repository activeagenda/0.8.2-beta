CREATE TABLE `lint` (
   InjuryIllnessTypeID int unsigned auto_increment not null,
   InjIllTypeID int,
   InjuryIllnessTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryIllnessTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lint_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryIllnessTypeID int unsigned  not null,
   InjIllTypeID int,
   InjuryIllnessTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryIllnessTypeID
   )
) TYPE=InnoDB;
