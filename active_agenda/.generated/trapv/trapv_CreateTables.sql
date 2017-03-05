CREATE TABLE `trapv` (
   TrainingProviderID int unsigned auto_increment not null,
   CourseID int,
   ProviderID int unsigned,
   CoursePrice decimal(12,4),
   CoursePriceUnitsID int,
   ProviderDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingProviderID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trapv_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingProviderID int unsigned  not null,
   CourseID int,
   ProviderID int unsigned,
   CoursePrice decimal(12,4),
   CoursePriceUnitsID int,
   ProviderDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingProviderID
   )
) TYPE=InnoDB;
