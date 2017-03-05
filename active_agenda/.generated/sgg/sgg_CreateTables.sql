CREATE TABLE `sgg` (
   SuggestionID int unsigned auto_increment not null,
   OrganizationID int,
   SuggestionTitle varchar(128),
   PresentMethod text,
   ProposedImprovement text,
   PotentialOpp text,
   WorkOrderRequired bool default 0,
   ActualBenefit decimal(12,4),
   SuggStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SuggestionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sgg_l` (
   _RecordID int unsigned not null auto_increment,
   SuggestionID int unsigned  not null,
   OrganizationID int,
   SuggestionTitle varchar(128),
   PresentMethod text,
   ProposedImprovement text,
   PotentialOpp text,
   WorkOrderRequired bool default 0,
   ActualBenefit decimal(12,4),
   SuggStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SuggestionID
   )
) TYPE=InnoDB;
