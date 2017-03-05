CREATE TABLE `acc` (
   AccountabilityID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int unsigned not null,
   PersonAccountableID int unsigned,
   AccountabilityDescriptorID int unsigned,
   Details text,
   AccountabilityStatusID int unsigned not null default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountabilityID
   ),
   INDEX acc_SourceModuleIDRecordID (
      SourceModuleID,
      SourceRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `acc_l` (
   _RecordID int unsigned not null auto_increment,
   AccountabilityID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int unsigned not null,
   PersonAccountableID int unsigned,
   AccountabilityDescriptorID int unsigned,
   Details text,
   AccountabilityStatusID int unsigned not null default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AccountabilityID
   ),
   INDEX acc_l_SourceModuleIDRecordID (
      SourceModuleID,
      SourceRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `act` (
   ActionID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title varchar(128),
   ActionRequired text,
   OrganizationID int,
   PersonAccountableID int,
   BeginDate date,
   ActionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ActionID
   ),
   INDEX act_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `act_l` (
   _RecordID int unsigned not null auto_increment,
   ActionID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title varchar(128),
   ActionRequired text,
   OrganizationID int,
   PersonAccountableID int,
   BeginDate date,
   ActionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ActionID
   ),
   INDEX act_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `agr` (
   AgreementID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   SecondOrganizationID int,
   AgreementTypeID int unsigned not null,
   AgmtTitle varchar(128),
   AgmtSummary text,
   TerminationFactors text,
   AgmtStatusID int,
   AgmtAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agr_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   SecondOrganizationID int,
   AgreementTypeID int unsigned not null,
   AgmtTitle varchar(128),
   AgmtSummary text,
   TerminationFactors text,
   AgmtStatusID int,
   AgmtAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementID
   )
) TYPE=InnoDB;

CREATE TABLE `agrc` (
   AgreementConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   AgreementConsiderationTitle varchar(128),
   AgreementConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agrc_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   AgreementConsiderationTitle varchar(128),
   AgreementConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `agrt` (
   AgreementTypeID int unsigned auto_increment not null,
   AgreementCategoryID int,
   AgreementTypeTitle varchar(128),
   AgreementTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AgreementTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `agrt_l` (
   _RecordID int unsigned not null auto_increment,
   AgreementTypeID int unsigned  not null,
   AgreementCategoryID int,
   AgreementTypeTitle varchar(128),
   AgreementTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AgreementTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `apr` (
   AssetProtectID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ReviewOrganizationID int,
   OccurrenceTypeID int,
   ItemStatusID int,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssetProtectID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `apr_l` (
   _RecordID int unsigned not null auto_increment,
   AssetProtectID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ReviewOrganizationID int,
   OccurrenceTypeID int,
   ItemStatusID int,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssetProtectID
   )
) TYPE=InnoDB;

CREATE TABLE `aprc` (
   ChecklistID int unsigned auto_increment not null,
   ChecklistTitle varchar(128),
   ChecklistDesc text,
   OrganizationID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   ChecklistInstruct text,
   ChecklistSpecialEquip text,
   ChecklistStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprc_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistID int unsigned  not null,
   ChecklistTitle varchar(128),
   ChecklistDesc text,
   OrganizationID int,
   SchedFreq float,
   SchedFreqUnitsID int,
   ChecklistInstruct text,
   ChecklistSpecialEquip text,
   ChecklistStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistID
   )
) TYPE=InnoDB;

CREATE TABLE `aprcd` (
   ChecklistDeficiencyID int unsigned auto_increment not null,
   ChecklistHistoryID int,
   AssetProtectID int unsigned not null,
   DeficiencyTitle varchar(128),
   Deficiency text,
   Remediation text,
   DeficiencyStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistDeficiencyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprcd_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistDeficiencyID int unsigned  not null,
   ChecklistHistoryID int,
   AssetProtectID int unsigned not null,
   DeficiencyTitle varchar(128),
   Deficiency text,
   Remediation text,
   DeficiencyStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistDeficiencyID
   )
) TYPE=InnoDB;

CREATE TABLE `aprch` (
   ChecklistHistoryID int unsigned auto_increment not null,
   ChecklistID int,
   Deficiencies int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistHistoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprch_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistHistoryID int unsigned  not null,
   ChecklistID int,
   Deficiencies int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistHistoryID
   )
) TYPE=InnoDB;

CREATE TABLE `aprci` (
   ChecklistItemID int unsigned auto_increment not null,
   ChecklistID int,
   AssetProtectID int,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChecklistItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprci_l` (
   _RecordID int unsigned not null auto_increment,
   ChecklistItemID int unsigned  not null,
   ChecklistID int,
   AssetProtectID int,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChecklistItemID
   )
) TYPE=InnoDB;

CREATE TABLE `aprd` (
   AssetProtectDefaultID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq varchar(10),
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssetProtectDefaultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aprd_l` (
   _RecordID int unsigned not null auto_increment,
   AssetProtectDefaultID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   AssetProtectCategoryID int,
   AssetProtectPurposeID int,
   SchedFreq varchar(10),
   SchedFreqUnitsID int,
   AssetProtectTitle varchar(128),
   AssetProtectInstruct text,
   AcceptableCriteria text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssetProtectDefaultID
   )
) TYPE=InnoDB;

CREATE TABLE `april` (
   OtherItemLocationID int unsigned auto_increment not null,
   OtherItemID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherItemLocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `april_l` (
   _RecordID int unsigned not null auto_increment,
   OtherItemLocationID int unsigned  not null,
   OtherItemID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherItemLocationID
   )
) TYPE=InnoDB;

CREATE TABLE `aproi` (
   OtherItemID int unsigned auto_increment not null,
   OrganizationID int,
   OtherItemTypeID int,
   OtherItemTitle varchar(128),
   OtherItemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `aproi_l` (
   _RecordID int unsigned not null auto_increment,
   OtherItemID int unsigned  not null,
   OrganizationID int,
   OtherItemTypeID int,
   OtherItemTitle varchar(128),
   OtherItemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherItemID
   )
) TYPE=InnoDB;

CREATE TABLE `att` (
   AttachmentID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileName varchar(128),
   Description varchar(128),
   FileSize float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttachmentID
   ),
   INDEX att_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `att_l` (
   _RecordID int unsigned not null auto_increment,
   AttachmentID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileName varchar(128),
   Description varchar(128),
   FileSize float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttachmentID
   ),
   INDEX att_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `bcq` (
   BusinessConsequenceID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   BusinessConsequenceTypeID int,
   NatureofConsequenceID int,
   ConsequenceTitle varchar(128),
   ConsequenceDesc text,
   ConsequenceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcq_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   BusinessConsequenceTypeID int,
   NatureofConsequenceID int,
   ConsequenceTitle varchar(128),
   ConsequenceDesc text,
   ConsequenceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceID
   )
) TYPE=InnoDB;

CREATE TABLE `bcqc` (
   BusinessConsequenceConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   BusinessConsequenceConsiderationTitle varchar(128),
   BusinessConsequenceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcqc_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   BusinessConsequenceConsiderationTitle varchar(128),
   BusinessConsequenceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `bcqt` (
   BusinessConsequenceTypeID int unsigned auto_increment not null,
   ConsequenceCategoryID int,
   ConsequenceType varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcqt_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceTypeID int unsigned  not null,
   ConsequenceCategoryID int,
   ConsequenceType varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `bpc` (
   BestPracticeID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BestPracticeCategoryID int,
   BestPracticeTitle varchar(128),
   BestPracticeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BestPracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bpc_l` (
   _RecordID int unsigned not null auto_increment,
   BestPracticeID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BestPracticeCategoryID int,
   BestPracticeTitle varchar(128),
   BestPracticeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BestPracticeID
   )
) TYPE=InnoDB;

CREATE TABLE `bud` (
   BudgetID int unsigned auto_increment not null,
   OrganizationID int,
   BudgetCategoryID int,
   BudgetNumber varchar(50),
   BudgetTitle varchar(128),
   BudgetDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BudgetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bud_l` (
   _RecordID int unsigned not null auto_increment,
   BudgetID int unsigned  not null,
   OrganizationID int,
   BudgetCategoryID int,
   BudgetNumber varchar(50),
   BudgetTitle varchar(128),
   BudgetDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BudgetID
   )
) TYPE=InnoDB;

CREATE TABLE `buda` (
   AccountID int unsigned auto_increment not null,
   BudgetID int unsigned not null,
   AccountOrganizationID int,
   DepartmentID int,
   AccountTypeID int unsigned not null,
   AccountNumber varchar(50),
   AccountName varchar(128),
   AccountDesc text,
   AccountAmount decimal(12,4),
   AccountStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `buda_l` (
   _RecordID int unsigned not null auto_increment,
   AccountID int unsigned  not null,
   BudgetID int unsigned not null,
   AccountOrganizationID int,
   DepartmentID int,
   AccountTypeID int unsigned not null,
   AccountNumber varchar(50),
   AccountName varchar(128),
   AccountDesc text,
   AccountAmount decimal(12,4),
   AccountStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AccountID
   )
) TYPE=InnoDB;

CREATE TABLE `budat` (
   AccountTypeID int unsigned auto_increment not null,
   AccountCategoryID int,
   AccountTypeTitle varchar(128),
   AccountDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `budat_l` (
   _RecordID int unsigned not null auto_increment,
   AccountTypeID int unsigned  not null,
   AccountCategoryID int,
   AccountTypeTitle varchar(128),
   AccountDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AccountTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `bui` (
   BuildingID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   BuildingTypeID int unsigned not null,
   BuildingName varchar(50),
   BuildingDesc text,
   Address1 varchar(128),
   Address2 varchar(128),
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(25),
   BuildingClassID int unsigned,
   BuildingQualityID int unsigned,
   ConstructionTypeID int unsigned,
   ConstructionYear varchar(5),
   BuildingSize int,
   BuildSizeUnitsID int,
   LotSize int,
   LotSizeUnitsID int,
   FireSystem text,
   EstimatedBuildingValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BuildingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bui_l` (
   _RecordID int unsigned not null auto_increment,
   BuildingID int unsigned  not null,
   OrganizationID int unsigned,
   BuildingTypeID int unsigned not null,
   BuildingName varchar(50),
   BuildingDesc text,
   Address1 varchar(128),
   Address2 varchar(128),
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(25),
   BuildingClassID int unsigned,
   BuildingQualityID int unsigned,
   ConstructionTypeID int unsigned,
   ConstructionYear varchar(5),
   BuildingSize int,
   BuildSizeUnitsID int,
   LotSize int,
   LotSizeUnitsID int,
   FireSystem text,
   EstimatedBuildingValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BuildingID
   )
) TYPE=InnoDB;

CREATE TABLE `buir` (
   RoomID int unsigned auto_increment not null,
   BuildingID int unsigned,
   RoomName varchar(128),
   RoomNumber varchar(10),
   FloorID int unsigned,
   RoomPurpose text,
   FireProtection bool,
   FireSuppression text,
   FireSuppTypeID int unsigned,
   InventoryValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RoomID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `buir_l` (
   _RecordID int unsigned not null auto_increment,
   RoomID int unsigned  not null,
   BuildingID int unsigned,
   RoomName varchar(128),
   RoomNumber varchar(10),
   FloorID int unsigned,
   RoomPurpose text,
   FireProtection bool,
   FireSuppression text,
   FireSuppTypeID int unsigned,
   InventoryValue decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RoomID
   )
) TYPE=InnoDB;

CREATE TABLE `buit` (
   BuildingTypeID int unsigned auto_increment not null,
   BuildingCategoryID int,
   BuildingTypeTitle varchar(128),
   BuildingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BuildingTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `buit_l` (
   _RecordID int unsigned not null auto_increment,
   BuildingTypeID int unsigned  not null,
   BuildingCategoryID int,
   BuildingTypeTitle varchar(128),
   BuildingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BuildingTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `bus` (
   BusinessContinuationID int unsigned auto_increment not null,
   BusinessProcessTypeID int,
   BusinessContinuationTitle varchar(128),
   BusinessContinuationNeed text,
   OperationalConsiderations text,
   FinancialConsiderations text,
   SignificantImpact decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessContinuationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bus_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessContinuationID int unsigned  not null,
   BusinessProcessTypeID int,
   BusinessContinuationTitle varchar(128),
   BusinessContinuationNeed text,
   OperationalConsiderations text,
   FinancialConsiderations text,
   SignificantImpact decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessContinuationID
   )
) TYPE=InnoDB;

CREATE TABLE `busa` (
   BusinessContID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BusinessContinuationID int unsigned not null,
   OrganizationID int,
   BusRecoveryTitle varchar(128),
   BusRecoveryRespon text,
   CriticalityID int,
   Impact decimal(12,4),
   Budget decimal(12,4),
   TakeActionImmediacy float,
   ImmediacyUnitsID int,
   AcquireWithin varchar(10),
   AcquireWithinUnitsID int,
   ContinuationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessContID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `busa_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessContID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   BusinessContinuationID int unsigned not null,
   OrganizationID int,
   BusRecoveryTitle varchar(128),
   BusRecoveryRespon text,
   CriticalityID int,
   Impact decimal(12,4),
   Budget decimal(12,4),
   TakeActionImmediacy float,
   ImmediacyUnitsID int,
   AcquireWithin varchar(10),
   AcquireWithinUnitsID int,
   ContinuationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessContID
   )
) TYPE=InnoDB;

CREATE TABLE `busc` (
   BusContConsiderID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   BusContConsiderTitle varchar(128),
   BusContConsiderDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusContConsiderID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `busc_l` (
   _RecordID int unsigned not null auto_increment,
   BusContConsiderID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   BusContConsiderTitle varchar(128),
   BusContConsiderDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusContConsiderID
   )
) TYPE=InnoDB;

CREATE TABLE `cat` (
   CatalogID int unsigned auto_increment not null,
   CatalogTypeID int,
   CatalogCompanyID int,
   CatalogTitle varchar(50),
   CatalogVolume varchar(10),
   CatalogDesc text,
   OrganizationID int,
   CatalogStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CatalogID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cat_l` (
   _RecordID int unsigned not null auto_increment,
   CatalogID int unsigned  not null,
   CatalogTypeID int,
   CatalogCompanyID int,
   CatalogTitle varchar(50),
   CatalogVolume varchar(10),
   CatalogDesc text,
   OrganizationID int,
   CatalogStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CatalogID
   )
) TYPE=InnoDB;

CREATE TABLE `cata` (
   CatalogAssocID int unsigned auto_increment not null,
   CatalogID int,
   OrganizationID int,
   ApprovalID int,
   Conditions text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CatalogAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cata_l` (
   _RecordID int unsigned not null auto_increment,
   CatalogAssocID int unsigned  not null,
   CatalogID int,
   OrganizationID int,
   ApprovalID int,
   Conditions text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CatalogAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `chm` (
   ChemicalID int unsigned auto_increment not null,
   OrganizationID int,
   ProductName varchar(128),
   CommonName varchar(128),
   ChemicalName varchar(128),
   CASNumber varchar(50),
   ChemicalStatusID int,
   ManufacturerID int,
   MSDSAvailable bool default 0,
   MSDSURL varchar(128),
   RTECSNumber varchar(15),
   RTECSURL varchar(128),
   ICSCNumber varchar(15),
   ICSCURL varchar(128),
   Processed bool default 0,
   TradeSecret bool default 0,
   ExtremelyHazardous bool default 0,
   PotVolatile bool default 0,
   ExceedCoLimits bool default 0,
   ProductIngred bool default 0,
   ContGuarOnFile bool default 0,
   ActiveIngredient varchar(50),
   HealthID int,
   FireID int,
   ReactivityID int,
   SpecialHazardID int,
   SpecialHand text,
   FlashPoint int,
   TempUnitsID int,
   pH int,
   ReportableQuantity int,
   WeightVolUnitsID int,
   FederalWasteID varchar(128),
   Solid bool,
   Liquid bool,
   Gas bool,
   Responsibilitiesdf text,
   Verificationdf text,
   Preparationdf text,
   Applicationdf text,
   PostApplicationdf text,
   Precautionsdf text,
   FirstAiddf text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chm_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalID int unsigned  not null,
   OrganizationID int,
   ProductName varchar(128),
   CommonName varchar(128),
   ChemicalName varchar(128),
   CASNumber varchar(50),
   ChemicalStatusID int,
   ManufacturerID int,
   MSDSAvailable bool default 0,
   MSDSURL varchar(128),
   RTECSNumber varchar(15),
   RTECSURL varchar(128),
   ICSCNumber varchar(15),
   ICSCURL varchar(128),
   Processed bool default 0,
   TradeSecret bool default 0,
   ExtremelyHazardous bool default 0,
   PotVolatile bool default 0,
   ExceedCoLimits bool default 0,
   ProductIngred bool default 0,
   ContGuarOnFile bool default 0,
   ActiveIngredient varchar(50),
   HealthID int,
   FireID int,
   ReactivityID int,
   SpecialHazardID int,
   SpecialHand text,
   FlashPoint int,
   TempUnitsID int,
   pH int,
   ReportableQuantity int,
   WeightVolUnitsID int,
   FederalWasteID varchar(128),
   Solid bool,
   Liquid bool,
   Gas bool,
   Responsibilitiesdf text,
   Verificationdf text,
   Preparationdf text,
   Applicationdf text,
   PostApplicationdf text,
   Precautionsdf text,
   FirstAiddf text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalID
   )
) TYPE=InnoDB;

CREATE TABLE `chma` (
   ChemicalAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ChemicalID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chma_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ChemicalID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `chmca` (
   ChemicalHazardClassificationAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalHazardClassificationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalHazardClassificationAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmca_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalHazardClassificationAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalHazardClassificationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalHazardClassificationAssociationID
   )
) TYPE=InnoDB;

CREATE TABLE `chmco` (
   ChemCompID int unsigned auto_increment not null,
   ChemicalID int,
   ChemCompName varchar(128),
   ComponentCASNumber varchar(50),
   PercentByWeight int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemCompID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmco_l` (
   _RecordID int unsigned not null auto_increment,
   ChemCompID int unsigned  not null,
   ChemicalID int,
   ChemCompName varchar(128),
   ComponentCASNumber varchar(50),
   PercentByWeight int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemCompID
   )
) TYPE=InnoDB;

CREATE TABLE `chmer` (
   ExposureRouteID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalExposureRouteID int,
   ExposureDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExposureRouteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmer_l` (
   _RecordID int unsigned not null auto_increment,
   ExposureRouteID int unsigned  not null,
   ChemicalID int,
   ChemicalExposureRouteID int,
   ExposureDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExposureRouteID
   )
) TYPE=InnoDB;

CREATE TABLE `chmh` (
   ChemHandProcID int unsigned auto_increment not null,
   ChemicalID int,
   OrganizationID int,
   HandlingProcName varchar(128),
   ChemicalUseTypeID int,
   HandlingProcStatusID int,
   Responsibilities text,
   Verification text,
   Preparation text,
   Application text,
   PostApplication text,
   Precautions text,
   FirstAid text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemHandProcID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmh_l` (
   _RecordID int unsigned not null auto_increment,
   ChemHandProcID int unsigned  not null,
   ChemicalID int,
   OrganizationID int,
   HandlingProcName varchar(128),
   ChemicalUseTypeID int,
   HandlingProcStatusID int,
   Responsibilities text,
   Verification text,
   Preparation text,
   Application text,
   PostApplication text,
   Precautions text,
   FirstAid text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemHandProcID
   )
) TYPE=InnoDB;

CREATE TABLE `chmhc` (
   ChemicalHazardClassificationID int unsigned auto_increment not null,
   ClassificationTypeID int,
   ClassificationTitle varchar(128),
   ClassificationNumber varchar(10),
   ClassificationDesc text,
   ClassificationOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalHazardClassificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmhc_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalHazardClassificationID int unsigned  not null,
   ClassificationTypeID int,
   ClassificationTitle varchar(128),
   ClassificationNumber varchar(10),
   ClassificationDesc text,
   ClassificationOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalHazardClassificationID
   )
) TYPE=InnoDB;

CREATE TABLE `chmin` (
   ChemicalInventoryID int unsigned auto_increment not null,
   ChemicalID int,
   OrganizationID int,
   ChemicalStorageMethodID int,
   TrackingNumber varchar(50),
   StorageDesc text,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   MaxAmountAllowed float,
   MaxAmtUoMID int,
   AmountPerYear float,
   AmtPerYrUoMID int,
   AmountPerDay float,
   AmtPerDayUoMID int,
   DaysOnSite float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalInventoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmin_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalInventoryID int unsigned  not null,
   ChemicalID int,
   OrganizationID int,
   ChemicalStorageMethodID int,
   TrackingNumber varchar(50),
   StorageDesc text,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   MaxAmountAllowed float,
   MaxAmtUoMID int,
   AmountPerYear float,
   AmtPerYrUoMID int,
   AmountPerDay float,
   AmtPerDayUoMID int,
   DaysOnSite float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalInventoryID
   )
) TYPE=InnoDB;

CREATE TABLE `chmpa` (
   ChemicalPhraseAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalPhraseID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalPhraseAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmpa_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalPhraseAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalPhraseID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalPhraseAssociationID
   )
) TYPE=InnoDB;

CREATE TABLE `chmph` (
   ChemicalPhraseID int unsigned auto_increment not null,
   ChemicalPhraseTypeID int unsigned not null default 0,
   ChemicalPhraseNumber varchar(10),
   Phrase text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalPhraseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmph_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalPhraseID int unsigned  not null,
   ChemicalPhraseTypeID int unsigned not null default 0,
   ChemicalPhraseNumber varchar(10),
   Phrase text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalPhraseID
   )
) TYPE=InnoDB;

CREATE TABLE `chmt` (
   ChemicalTypeID int unsigned auto_increment not null,
   ChemicalCategoryID int,
   ChemicalTypeName varchar(128),
   ChemicalTypeDesc text,
   ChemicalTypeColor varchar(128),
   ApplyColorToLabel bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmt_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTypeID int unsigned  not null,
   ChemicalCategoryID int,
   ChemicalTypeName varchar(128),
   ChemicalTypeDesc text,
   ChemicalTypeColor varchar(128),
   ApplyColorToLabel bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `chmta` (
   ChemicalTypeAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTypeAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmta_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTypeAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalTypeAssociationID
   )
) TYPE=InnoDB;

CREATE TABLE `chmth` (
   ThresholdID int unsigned auto_increment not null,
   ChemicalID int,
   ThresholdValueTypeID int,
   ThresholdAmount int,
   ConcentrationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ThresholdID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmth_l` (
   _RecordID int unsigned not null auto_increment,
   ThresholdID int unsigned  not null,
   ChemicalID int,
   ThresholdValueTypeID int,
   ThresholdAmount int,
   ConcentrationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ThresholdID
   )
) TYPE=InnoDB;

CREATE TABLE `chmtr` (
   ChemicalTransactionID int unsigned auto_increment not null,
   ChemicalInventoryID int,
   TransactionTypeID int,
   TransactionAmount int,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmtr_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTransactionID int unsigned  not null,
   ChemicalInventoryID int,
   TransactionTypeID int,
   TransactionAmount int,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalTransactionID
   )
) TYPE=InnoDB;

CREATE TABLE `clm` (
   ClaimID int unsigned auto_increment not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   TimeDeterminable bool default 1,
   PolicyID int,
   ClaimNumber varchar(50),
   OnCoPropID int,
   IncurredLocationDesc varchar(255),
   IncurredAddress1 varchar(50),
   IncurredAddress2 varchar(50),
   IncurredCountyID int unsigned,
   IncurredCity varchar(50),
   IncurredPostalCode varchar(50),
   LossStatusTypeID int,
   SpecificLossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ClaimID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `clm_l` (
   _RecordID int unsigned not null auto_increment,
   ClaimID int unsigned  not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   TimeDeterminable bool default 1,
   PolicyID int,
   ClaimNumber varchar(50),
   OnCoPropID int,
   IncurredLocationDesc varchar(255),
   IncurredAddress1 varchar(50),
   IncurredAddress2 varchar(50),
   IncurredCountyID int unsigned,
   IncurredCity varchar(50),
   IncurredPostalCode varchar(50),
   LossStatusTypeID int,
   SpecificLossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ClaimID
   )
) TYPE=InnoDB;

CREATE TABLE `cnt` (
   ControlID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AssignedOrganizationID int,
   ControlCategoryID int,
   ControlTitle varchar(128),
   ControlDesc text,
   ControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ControlID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cnt_l` (
   _RecordID int unsigned not null auto_increment,
   ControlID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AssignedOrganizationID int,
   ControlCategoryID int,
   ControlTitle varchar(128),
   ControlDesc text,
   ControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ControlID
   )
) TYPE=InnoDB;

CREATE TABLE `cod` (
   CodeID int not null,
   CodeTypeID int not null,
   SortOrder int,
   Value varchar(25),
   Description varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID,
      CodeID
   ),
   INDEX cod_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cod_l` (
   _RecordID int unsigned not null auto_increment,
   CodeID int not null,
   CodeTypeID int not null,
   SortOrder int,
   Value varchar(25),
   Description varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CodeTypeID,
      CodeID
   ),
   INDEX cod_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

CREATE TABLE `codt` (
   CodeTypeID int not null auto_increment,
   Name varchar(128),
   Description varchar(255),
   UseValue bool default 0 not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID
   ),
   INDEX codt_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `codt_l` (
   _RecordID int unsigned not null auto_increment,
   CodeTypeID int not null ,
   Name varchar(128),
   Description varchar(255),
   UseValue bool default 0 not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CodeTypeID
   ),
   INDEX codt_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

CREATE TABLE `codtd` (
   CodeTypeDependencyID int unsigned auto_increment not null,
   CodeTypeID int not null,
   DependencyID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CodeTypeDependencyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `codtd_l` (
   _RecordID int unsigned not null auto_increment,
   CodeTypeDependencyID int unsigned  not null,
   CodeTypeID int not null,
   DependencyID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CodeTypeDependencyID
   )
) TYPE=InnoDB;

CREATE TABLE `cor` (
   CorrActID int unsigned auto_increment not null,
   CorrectiveSituationTypeID int unsigned not null,
   OrganizationID int unsigned not null,
   SituationDesc text,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   CorrActDesc text,
   CorrActStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrActID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cor_l` (
   _RecordID int unsigned not null auto_increment,
   CorrActID int unsigned  not null,
   CorrectiveSituationTypeID int unsigned not null,
   OrganizationID int unsigned not null,
   SituationDesc text,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   CorrActDesc text,
   CorrActStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrActID
   )
) TYPE=InnoDB;

CREATE TABLE `corcp` (
   CorrectivePracticeID int unsigned auto_increment not null,
   CorrectiveSituationTypeID int unsigned not null,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrectivePracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corcp_l` (
   _RecordID int unsigned not null auto_increment,
   CorrectivePracticeID int unsigned  not null,
   CorrectiveSituationTypeID int unsigned not null,
   OccurNoID int,
   CorrectivePracticeCategoryID int,
   CorrectivePracticeDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrectivePracticeID
   )
) TYPE=InnoDB;

CREATE TABLE `corp` (
   PracticeID int unsigned auto_increment not null,
   OrganizationID int,
   SituationID int unsigned not null,
   OrganizationDesc text,
   WorkRuleNo varchar(50),
   OccurNoID int,
   CorrActTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corp_l` (
   _RecordID int unsigned not null auto_increment,
   PracticeID int unsigned  not null,
   OrganizationID int,
   SituationID int unsigned not null,
   OrganizationDesc text,
   WorkRuleNo varchar(50),
   OccurNoID int,
   CorrActTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PracticeID
   )
) TYPE=InnoDB;

CREATE TABLE `cors` (
   SituationID int unsigned auto_increment not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   GeneralDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cors_l` (
   _RecordID int unsigned not null auto_increment,
   SituationID int unsigned  not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   GeneralDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationID
   )
) TYPE=InnoDB;

CREATE TABLE `corst` (
   CorrectiveSituationTypeID int unsigned auto_increment not null,
   PolicyOrganizationID int unsigned not null,
   CorrectiveSituationCategoryID int,
   CorrectiveSituationTitle varchar(128),
   CorrectiveSituationDescription text,
   WorkRuleNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrectiveSituationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `corst_l` (
   _RecordID int unsigned not null auto_increment,
   CorrectiveSituationTypeID int unsigned  not null,
   PolicyOrganizationID int unsigned not null,
   CorrectiveSituationCategoryID int,
   CorrectiveSituationTitle varchar(128),
   CorrectiveSituationDescription text,
   WorkRuleNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrectiveSituationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `cort` (
   CorrActTypeID int auto_increment not null,
   ActionOrganizationID int unsigned not null,
   CorrectiveActionCategoryID int,
   CorrectiveActionTitle varchar(128),
   CorrectiveActionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CorrActTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cort_l` (
   _RecordID int unsigned not null auto_increment,
   CorrActTypeID int  not null,
   ActionOrganizationID int unsigned not null,
   CorrectiveActionCategoryID int,
   CorrectiveActionTitle varchar(128),
   CorrectiveActionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CorrActTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `cos` (
   CostID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   Incurred decimal(12,4),
   Title varchar(128),
   CostDesc text,
   PONumber varchar(50),
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostID
   ),
   INDEX cos_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cos_l` (
   _RecordID int unsigned not null auto_increment,
   CostID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   Incurred decimal(12,4),
   Title varchar(128),
   CostDesc text,
   PONumber varchar(50),
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostID
   ),
   INDEX cos_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `cosc` (
   CostConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   CostConsiderationTitle varchar(128),
   CostConsiderationDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cosc_l` (
   _RecordID int unsigned not null auto_increment,
   CostConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   CostConsiderationTitle varchar(128),
   CostConsiderationDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetConsideration bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `cose` (
   ExpenditureID int unsigned auto_increment not null,
   CostID int unsigned not null,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   AccountID int unsigned not null,
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExpenditureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cose_l` (
   _RecordID int unsigned not null auto_increment,
   ExpenditureID int unsigned  not null,
   CostID int unsigned not null,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   AccountID int unsigned not null,
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExpenditureID
   )
) TYPE=InnoDB;

CREATE TABLE `cosex` (
   CostExposureID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   CostExposureTitle varchar(128),
   CostExposureDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetExposure bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cosex_l` (
   _RecordID int unsigned not null auto_increment,
   CostExposureID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   CostTypeID int,
   CostExposureTitle varchar(128),
   CostExposureDescription text,
   Estimate decimal(12,4),
   FinancialDetrimentFrequency float,
   FinancialDetrimentFrequencyUnitID int,
   BudgetExposure bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostExposureID
   )
) TYPE=InnoDB;

CREATE TABLE `cost` (
   CostTypeID int auto_increment not null,
   CostCategoryID int,
   CostTitle varchar(128),
   CostTypeDesc text,
   Expenditure bool default 1,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CostTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cost_l` (
   _RecordID int unsigned not null auto_increment,
   CostTypeID int  not null,
   CostCategoryID int,
   CostTitle varchar(128),
   CostTypeDesc text,
   Expenditure bool default 1,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CostTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `csc` (
   ModuleID varchar(5) not null,
   RecordID int not null,
   SeverityValue int not null,
   TotalCost decimal(12,4) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleID,
      RecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `csc_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SeverityValue int not null,
   TotalCost decimal(12,4) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleID,
      RecordID
   )
) TYPE=InnoDB;

CREATE TABLE `csp` (
   ConfinedSpaceID int unsigned auto_increment not null,
   OrganizationID int,
   ConfinedSpaceTypeID int unsigned not null,
   ConfinedSpaceTitle varchar(128),
   ConfinedSpaceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ConfinedSpaceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `csp_l` (
   _RecordID int unsigned not null auto_increment,
   ConfinedSpaceID int unsigned  not null,
   OrganizationID int,
   ConfinedSpaceTypeID int unsigned not null,
   ConfinedSpaceTitle varchar(128),
   ConfinedSpaceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ConfinedSpaceID
   )
) TYPE=InnoDB;

CREATE TABLE `cspp` (
   EntryPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   EntryShiftID int,
   ConfinedSpaceID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EntryPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cspp_l` (
   _RecordID int unsigned not null auto_increment,
   EntryPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   EntryShiftID int,
   ConfinedSpaceID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EntryPermitID
   )
) TYPE=InnoDB;

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

CREATE TABLE `cti` (
   CountyID int unsigned auto_increment not null,
   StateID int unsigned,
   CountyName varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CountyID
   ),
   INDEX cti_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cti_l` (
   _RecordID int unsigned not null auto_increment,
   CountyID int unsigned  not null,
   StateID int unsigned,
   CountyName varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CountyID
   ),
   INDEX cti_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

CREATE TABLE `ctr` (
   CountryID int unsigned auto_increment not null,
   CountryName varchar(50),
   NativeCountryName varchar(50),
   CountryAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CountryID
   ),
   INDEX ctr_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ctr_l` (
   _RecordID int unsigned not null auto_increment,
   CountryID int unsigned  not null,
   CountryName varchar(50),
   NativeCountryName varchar(50),
   CountryAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CountryID
   ),
   INDEX ctr_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

CREATE TABLE `dat` (
   DateID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   DateDescriptorID int,
   RelatedDate datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DateID
   ),
   INDEX dat_SourceRef (
      SourceModuleID,
      SourceRecordID,
      DateDescriptorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dat_l` (
   _RecordID int unsigned not null auto_increment,
   DateID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   DateDescriptorID int,
   RelatedDate datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DateID
   ),
   INDEX dat_l_SourceRef (
      SourceModuleID,
      SourceRecordID,
      DateDescriptorID
   )
) TYPE=InnoDB;

CREATE TABLE `dld` (
   DownloadID int not null,
   FileTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DownloadID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dld_l` (
   _RecordID int unsigned not null auto_increment,
   DownloadID int not null,
   FileTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DownloadID
   )
) TYPE=InnoDB;

CREATE TABLE `doc` (
   DocumentID int unsigned auto_increment not null,
   OrganizationID int,
   DepartmentID int,
   DocumentTypeID int unsigned not null,
   DocumentTitle varchar(128),
   DocumentVersion varchar(128),
   DocumentDesc text,
   DocumentPurpose text,
   DocumentScope text,
   CompletionImmediacy float,
   ImmediacyUnitsID int,
   ReviewFrequency float,
   FrequencyUnitsID int,
   DocumentStatusID int,
   Attached bool,
   RegulatoryRequired bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doc_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentID int unsigned  not null,
   OrganizationID int,
   DepartmentID int,
   DocumentTypeID int unsigned not null,
   DocumentTitle varchar(128),
   DocumentVersion varchar(128),
   DocumentDesc text,
   DocumentPurpose text,
   DocumentScope text,
   CompletionImmediacy float,
   ImmediacyUnitsID int,
   ReviewFrequency float,
   FrequencyUnitsID int,
   DocumentStatusID int,
   Attached bool,
   RegulatoryRequired bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentID
   )
) TYPE=InnoDB;

CREATE TABLE `doca` (
   DocumentAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DocumentID int unsigned not null,
   MannerAssociated text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doca_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DocumentID int unsigned not null,
   MannerAssociated text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `docc` (
   DocumentConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   DocumentConsiderationTitle varchar(128),
   DocumentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docc_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   DocumentConsiderationTitle varchar(128),
   DocumentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `docm` (
   DocumentationModuleID int unsigned auto_increment not null,
   DocumentID int,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docm_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationModuleID int unsigned  not null,
   DocumentID int,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationModuleID
   )
) TYPE=InnoDB;

CREATE TABLE `docr` (
   DocumentationRecID int unsigned auto_increment not null,
   DocumentID int,
   OrganizationID int,
   ReceivingPreferenceID int,
   SubImmediacyValue float,
   SubImmediacyID int,
   ImmediacyDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationRecID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docr_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationRecID int unsigned  not null,
   DocumentID int,
   OrganizationID int,
   ReceivingPreferenceID int,
   SubImmediacyValue float,
   SubImmediacyID int,
   ImmediacyDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationRecID
   )
) TYPE=InnoDB;

CREATE TABLE `docs` (
   DocumentStatusID int unsigned auto_increment not null,
   DocumentationRecID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   NotificationDetails text,
   RecipientStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentStatusID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docs_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentStatusID int unsigned  not null,
   DocumentationRecID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   NotificationDetails text,
   RecipientStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentStatusID
   )
) TYPE=InnoDB;

CREATE TABLE `docsi` (
   DocumentationSituationID int unsigned auto_increment not null,
   DocumentID int,
   SituationTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationSituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docsi_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationSituationID int unsigned  not null,
   DocumentID int,
   SituationTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationSituationID
   )
) TYPE=InnoDB;

CREATE TABLE `doct` (
   DocumentTypeID int unsigned auto_increment not null,
   DocumentCategoryID int,
   DocumentTypeName varchar(128),
   DocumentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doct_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentTypeID int unsigned  not null,
   DocumentCategoryID int,
   DocumentTypeName varchar(128),
   DocumentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `dsb` (
   DashboardItemID int unsigned auto_increment not null,
   PersonID int,
   ModuleID varchar(5),
   Title varchar(128),
   DashboardTypeID int,
   CachedSQL text,
   DisplayRows int,
   PageColumn int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsb_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardItemID int unsigned  not null,
   PersonID int,
   ModuleID varchar(5),
   Title varchar(128),
   DashboardTypeID int,
   CachedSQL text,
   DisplayRows int,
   PageColumn int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardItemID
   )
) TYPE=InnoDB;

CREATE TABLE `dsbc` (
   DashboardChartID int auto_increment not null,
   UserID int not null,
   ModuleID varchar(5) not null,
   ChartName varchar(10) not null,
   SortOrder int,
   ConditionPhrases text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardChartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbc_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardChartID int  not null,
   UserID int not null,
   ModuleID varchar(5) not null,
   ChartName varchar(10) not null,
   SortOrder int,
   ConditionPhrases text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardChartID
   )
) TYPE=InnoDB;

CREATE TABLE `dsbcc` (
   ConditionID int auto_increment not null,
   ModuleID varchar(5) not null,
   UserID int not null,
   DashboardChartID int not null,
   ConditionField varchar(50) not null,
   ConditionValue varchar(50) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ConditionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbcc_l` (
   _RecordID int unsigned not null auto_increment,
   ConditionID int  not null,
   ModuleID varchar(5) not null,
   UserID int not null,
   DashboardChartID int not null,
   ConditionField varchar(50) not null,
   ConditionValue varchar(50) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ConditionID
   )
) TYPE=InnoDB;

CREATE TABLE `dsbd` (
   DashboardFieldID int unsigned auto_increment not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardFieldID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbd_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardFieldID int unsigned  not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardFieldID
   )
) TYPE=InnoDB;

CREATE TABLE `dsbo` (
   DashboardFieldID int unsigned auto_increment not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardFieldID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbo_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardFieldID int unsigned  not null,
   DashboardItemID int unsigned not null,
   Name varchar(30),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardFieldID
   )
) TYPE=InnoDB;

CREATE TABLE `dsbt` (
   DashboardTypeID int unsigned auto_increment not null,
   Title varchar(30),
   ClassName varchar(30),
   IncludeFile varchar(30),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DashboardTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dsbt_l` (
   _RecordID int unsigned not null auto_increment,
   DashboardTypeID int unsigned  not null,
   Title varchar(30),
   ClassName varchar(30),
   IncludeFile varchar(30),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DashboardTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `eqp` (
   EquipmentID int unsigned auto_increment not null,
   OrganizationID int,
   EquipmentTypeID int unsigned not null,
   EquipmentName varchar(128),
   EquipmentDesc text,
   ModelNo varchar(50),
   BaseUnitID int,
   ManufacturerID int,
   ManufPartNo varchar(128),
   EquipmentURL varchar(128),
   EmergencyResponse bool default 0,
   Issued bool,
   HazardousEnergy bool default 0,
   SystemComponent bool default 0,
   CriticalControl bool default 0,
   Training bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqp_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentID int unsigned  not null,
   OrganizationID int,
   EquipmentTypeID int unsigned not null,
   EquipmentName varchar(128),
   EquipmentDesc text,
   ModelNo varchar(50),
   BaseUnitID int,
   ManufacturerID int,
   ManufPartNo varchar(128),
   EquipmentURL varchar(128),
   EmergencyResponse bool default 0,
   Issued bool,
   HazardousEnergy bool default 0,
   SystemComponent bool default 0,
   CriticalControl bool default 0,
   Training bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentID
   )
) TYPE=InnoDB;

CREATE TABLE `eqpa` (
   EquipAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EquipmentInventoryID int unsigned,
   UseConditions text,
   EquipAssocStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpa_l` (
   _RecordID int unsigned not null auto_increment,
   EquipAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EquipmentInventoryID int unsigned,
   UseConditions text,
   EquipAssocStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `eqpc` (
   EquipmentConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   EquipmentConsiderationTitle varchar(128),
   EquipmentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpc_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   EquipmentConsiderationTitle varchar(128),
   EquipmentConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `eqpin` (
   EquipmentInventoryID int unsigned auto_increment not null,
   EquipmentID int,
   OrganizationID int,
   TrackingNumber varchar(10),
   EquipmentStorageMethodID int,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentInventoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpin_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentInventoryID int unsigned  not null,
   EquipmentID int,
   OrganizationID int,
   TrackingNumber varchar(10),
   EquipmentStorageMethodID int,
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued float,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentInventoryID
   )
) TYPE=InnoDB;

CREATE TABLE `eqpt` (
   EquipmentTypeID int unsigned auto_increment not null,
   EquipmentCategoryID int,
   EquipmentTypeTitle varchar(128),
   EquipmentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqpt_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentTypeID int unsigned  not null,
   EquipmentCategoryID int,
   EquipmentTypeTitle varchar(128),
   EquipmentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `eqptr` (
   EquipmentTransactionID int unsigned auto_increment not null,
   EquipmentInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EquipmentTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `eqptr_l` (
   _RecordID int unsigned not null auto_increment,
   EquipmentTransactionID int unsigned  not null,
   EquipmentInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EquipmentTransactionID
   )
) TYPE=InnoDB;

CREATE TABLE `evt` (
   EventID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   EventTitle varchar(128),
   EventDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EventID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `evt_l` (
   _RecordID int unsigned not null auto_increment,
   EventID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   EventTitle varchar(128),
   EventDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EventID
   )
) TYPE=InnoDB;

CREATE TABLE `ewk` (
   ElevatedWorkID int unsigned auto_increment not null,
   OrganizationID int,
   ElevatedWorkTypeID int unsigned not null,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewk_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkID int unsigned  not null,
   OrganizationID int,
   ElevatedWorkTypeID int unsigned not null,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkID
   )
) TYPE=InnoDB;

CREATE TABLE `ewkp` (
   ElevatedWorkPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   ElevatedWorkShiftID int,
   ElevatedWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewkp_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   ElevatedWorkShiftID int,
   ElevatedWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkPermitID
   )
) TYPE=InnoDB;

CREATE TABLE `ewkt` (
   ElevatedWorkTypeID int unsigned auto_increment not null,
   ElevatedWorkCategoryID int,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewkt_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkTypeID int unsigned  not null,
   ElevatedWorkCategoryID int,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `fbk` (
   FeedbackID int unsigned auto_increment not null,
   OrganizationID int,
   Anonymous bool default 0,
   FeedbackTypeID int,
   FeedbackTitle text,
   FeedbackProvided text,
   FeedbackStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FeedbackID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fbk_l` (
   _RecordID int unsigned not null auto_increment,
   FeedbackID int unsigned  not null,
   OrganizationID int,
   Anonymous bool default 0,
   FeedbackTypeID int,
   FeedbackTitle text,
   FeedbackProvided text,
   FeedbackStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FeedbackID
   )
) TYPE=InnoDB;

CREATE TABLE `fbkr` (
   FeedbackResponseID int unsigned auto_increment not null,
   FeedbackID int unsigned not null,
   Response text,
   ImpactID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FeedbackResponseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fbkr_l` (
   _RecordID int unsigned not null auto_increment,
   FeedbackResponseID int unsigned  not null,
   FeedbackID int unsigned not null,
   Response text,
   ImpactID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FeedbackResponseID
   )
) TYPE=InnoDB;

CREATE TABLE `fil` (
   FileReqID int unsigned auto_increment not null,
   OrganizationID int,
   FileTypeID int unsigned not null,
   FileRetentionMethodID int,
   FileRetentionDesc text,
   FileRetentionPeriod int,
   FileRetentionUnitsID int,
   FileDispositionMethodID int,
   FileDispositionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `fil_l` (
   _RecordID int unsigned not null auto_increment,
   FileReqID int unsigned  not null,
   OrganizationID int,
   FileTypeID int unsigned not null,
   FileRetentionMethodID int,
   FileRetentionDesc text,
   FileRetentionPeriod int,
   FileRetentionUnitsID int,
   FileDispositionMethodID int,
   FileDispositionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileReqID
   )
) TYPE=InnoDB;

CREATE TABLE `filph` (
   FileRetentionID int unsigned auto_increment not null,
   FileReqID int unsigned not null,
   Original bool default 1,
   FilingOrganizationID int,
   FileName varchar(128),
   FileNumber varchar(20),
   FilingMethodID int,
   FilingMethodDesc text,
   FilingDispositionID int,
   FilingDispositionDesc text,
   PhysicalFileStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileRetentionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filph_l` (
   _RecordID int unsigned not null auto_increment,
   FileRetentionID int unsigned  not null,
   FileReqID int unsigned not null,
   Original bool default 1,
   FilingOrganizationID int,
   FileName varchar(128),
   FileNumber varchar(20),
   FilingMethodID int,
   FilingMethodDesc text,
   FilingDispositionID int,
   FilingDispositionDesc text,
   PhysicalFileStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileRetentionID
   )
) TYPE=InnoDB;

CREATE TABLE `filr` (
   FileRecordID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileRetentionID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filr_l` (
   _RecordID int unsigned not null auto_increment,
   FileRecordID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileRetentionID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `filt` (
   FileTypeID int unsigned auto_increment not null,
   FileCategoryID int,
   FileTypeTitle varchar(128),
   FileTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filt_l` (
   _RecordID int unsigned not null auto_increment,
   FileTypeID int unsigned  not null,
   FileCategoryID int,
   FileTypeTitle varchar(128),
   FileTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `gap` (
   GapAnalysisID int unsigned auto_increment not null,
   ModuleID varchar(5),
   AnalysisTypeID int unsigned,
   AnalysisItem varchar(128),
   AnalysisDescription text,
   KeyResources text,
   KeyLocations text,
   KeyRisks text,
   HoursEstimate float,
   MethodsandBenefits text,
   AdvancedCopy bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GapAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gap_l` (
   _RecordID int unsigned not null auto_increment,
   GapAnalysisID int unsigned  not null,
   ModuleID varchar(5),
   AnalysisTypeID int unsigned,
   AnalysisItem varchar(128),
   AnalysisDescription text,
   KeyResources text,
   KeyLocations text,
   KeyRisks text,
   HoursEstimate float,
   MethodsandBenefits text,
   AdvancedCopy bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GapAnalysisID
   )
) TYPE=InnoDB;

CREATE TABLE `gapo` (
   OrgGapAnalysisID int unsigned auto_increment not null,
   OrganizationID int,
   GapTitle varchar(128),
   GapObjective text,
   ScheduledStart datetime,
   ScheduledEnd datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrgGapAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gapo_l` (
   _RecordID int unsigned not null auto_increment,
   OrgGapAnalysisID int unsigned  not null,
   OrganizationID int,
   GapTitle varchar(128),
   GapObjective text,
   ScheduledStart datetime,
   ScheduledEnd datetime,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrgGapAnalysisID
   )
) TYPE=InnoDB;

CREATE TABLE `gapoi` (
   GapAnalysisItemID int unsigned auto_increment not null,
   OrgGapAnalysisID int unsigned not null,
   GapAnalysisID int unsigned not null,
   OrganizationDescription text,
   OrganizationResources text,
   OrganizationLocations text,
   OrganizationRisks text,
   AnalysisStartTime datetime,
   AnalysisEndTime datetime,
   ReviewStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GapAnalysisItemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gapoi_l` (
   _RecordID int unsigned not null auto_increment,
   GapAnalysisItemID int unsigned  not null,
   OrgGapAnalysisID int unsigned not null,
   GapAnalysisID int unsigned not null,
   OrganizationDescription text,
   OrganizationResources text,
   OrganizationLocations text,
   OrganizationRisks text,
   AnalysisStartTime datetime,
   AnalysisEndTime datetime,
   ReviewStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GapAnalysisItemID
   )
) TYPE=InnoDB;

CREATE TABLE `glo` (
   GlossaryID int unsigned auto_increment not null,
   OrganizationID int,
   GlossaryItem varchar(128),
   Definition text,
   GlossaryURL varchar(128),
   Protected bool,
   Display bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GlossaryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `glo_l` (
   _RecordID int unsigned not null auto_increment,
   GlossaryID int unsigned  not null,
   OrganizationID int,
   GlossaryItem varchar(128),
   Definition text,
   GlossaryURL varchar(128),
   Protected bool,
   Display bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GlossaryID
   )
) TYPE=InnoDB;

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

CREATE TABLE `gui` (
   GuidanceOrganizationID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      GuidanceOrganizationID
   ),
   INDEX gui_Related (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `gui_l` (
   _RecordID int unsigned not null auto_increment,
   GuidanceOrganizationID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      GuidanceOrganizationID
   ),
   INDEX gui_l_Related (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `hazt` (
   HazardTypeID int unsigned auto_increment not null,
   HazCategoryID int,
   HazType varchar(128),
   HazTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hazt_l` (
   _RecordID int unsigned not null auto_increment,
   HazardTypeID int unsigned  not null,
   HazCategoryID int,
   HazType varchar(128),
   HazTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `hwk` (
   HotWorkID int unsigned auto_increment not null,
   OrganizationID int,
   HotWorkTypeID int unsigned not null,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwk_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkID int unsigned  not null,
   OrganizationID int,
   HotWorkTypeID int unsigned not null,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkID
   )
) TYPE=InnoDB;

CREATE TABLE `hwkp` (
   HotWorkPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   HotWorkShiftID int,
   HotWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwkp_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   HotWorkShiftID int,
   HotWorkID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkPermitID
   )
) TYPE=InnoDB;

CREATE TABLE `hwkt` (
   HotWorkTypeID int unsigned auto_increment not null,
   HotWorkCategoryID int,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwkt_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkTypeID int unsigned  not null,
   HotWorkCategoryID int,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `hza` (
   HazardID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LocationOrganizationID int,
   HazardTypeID int unsigned not null,
   HazardTitle varchar(128),
   HazardDesc text,
   HazardStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hza_l` (
   _RecordID int unsigned not null auto_increment,
   HazardID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LocationOrganizationID int,
   HazardTypeID int unsigned not null,
   HazardTitle varchar(128),
   HazardDesc text,
   HazardStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardID
   )
) TYPE=InnoDB;

CREATE TABLE `hzc` (
   HazardConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   HazardConsiderationTitle varchar(128),
   HazardConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzc_l` (
   _RecordID int unsigned not null auto_increment,
   HazardConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   HazardConsiderationTitle varchar(128),
   HazardConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `hze` (
   HazEnergyControlID int unsigned auto_increment not null,
   EquipmentInventoryID int unsigned,
   ControlProcTitle text,
   ControlProcDesc text,
   HazEnergyControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazEnergyControlID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hze_l` (
   _RecordID int unsigned not null auto_increment,
   HazEnergyControlID int unsigned  not null,
   EquipmentInventoryID int unsigned,
   ControlProcTitle text,
   ControlProcDesc text,
   HazEnergyControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazEnergyControlID
   )
) TYPE=InnoDB;

CREATE TABLE `hzea` (
   HZEControlProcedureAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HazEnergyControlID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HZEControlProcedureAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzea_l` (
   _RecordID int unsigned not null auto_increment,
   HZEControlProcedureAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HazEnergyControlID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HZEControlProcedureAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `hzead` (
   AdjustmentID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   AdjustmentOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AdjustmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzead_l` (
   _RecordID int unsigned not null auto_increment,
   AdjustmentID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   AdjustmentOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AdjustmentID
   )
) TYPE=InnoDB;

CREATE TABLE `hzecl` (
   CleaningID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   CleaningOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CleaningID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzecl_l` (
   _RecordID int unsigned not null auto_increment,
   CleaningID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   CleaningOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CleaningID
   )
) TYPE=InnoDB;

CREATE TABLE `hzee` (
   EnergySourceID int unsigned auto_increment not null,
   OrganizationID int,
   EnergyTypeID int,
   EnergySourceName varchar(50),
   EnergySourceNumber varchar(25),
   EnergySourceDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EnergySourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzee_l` (
   _RecordID int unsigned not null auto_increment,
   EnergySourceID int unsigned  not null,
   OrganizationID int,
   EnergyTypeID int,
   EnergySourceName varchar(50),
   EnergySourceNumber varchar(25),
   EnergySourceDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EnergySourceID
   )
) TYPE=InnoDB;

CREATE TABLE `hzeea` (
   EnergySourceAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EnergySourceID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EnergySourceAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzeea_l` (
   _RecordID int unsigned not null auto_increment,
   EnergySourceAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EnergySourceID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EnergySourceAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `hzerp` (
   RepairID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   RepairOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RepairID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzerp_l` (
   _RecordID int unsigned not null auto_increment,
   RepairID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   RepairOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RepairID
   )
) TYPE=InnoDB;

CREATE TABLE `hzesv` (
   ServiceID int unsigned auto_increment not null,
   HazEnergyControlID int unsigned not null,
   ServiceOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzesv_l` (
   _RecordID int unsigned not null auto_increment,
   ServiceID int unsigned  not null,
   HazEnergyControlID int unsigned not null,
   ServiceOrganizationID int,
   EnergyControlStageID int,
   ControlStepTitle varchar(128),
   ControlStepDesc text,
   ControlStepOrder float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ServiceID
   )
) TYPE=InnoDB;

CREATE TABLE `hzr` (
   HazardID int unsigned auto_increment not null,
   HazardTitle varchar(128),
   Description text,
   OrganizationID int,
   ReceivedDate datetime,
   ReportSourceID int,
   HazAbateStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzr_l` (
   _RecordID int unsigned not null auto_increment,
   HazardID int unsigned  not null,
   HazardTitle varchar(128),
   Description text,
   OrganizationID int,
   ReceivedDate datetime,
   ReportSourceID int,
   HazAbateStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardID
   )
) TYPE=InnoDB;

CREATE TABLE `ict` (
   IncentiveID int unsigned auto_increment not null,
   OrganizationID int,
   IncentiveCriteria varchar(128),
   IncentiveDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ict_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveID int unsigned  not null,
   OrganizationID int,
   IncentiveCriteria varchar(128),
   IncentiveDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveID
   )
) TYPE=InnoDB;

CREATE TABLE `icta` (
   IncentiveAwardID int unsigned auto_increment not null,
   IncentiveAssocID int,
   ActualValue decimal(12,4),
   AwardOrganizationID int,
   ActivityDesc text,
   SubmittalDate datetime,
   ReceivedDate datetime,
   Awarded bool default 1,
   DenialReason text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveAwardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `icta_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveAwardID int unsigned  not null,
   IncentiveAssocID int,
   ActualValue decimal(12,4),
   AwardOrganizationID int,
   ActivityDesc text,
   SubmittalDate datetime,
   ReceivedDate datetime,
   Awarded bool default 1,
   DenialReason text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveAwardID
   )
) TYPE=InnoDB;

CREATE TABLE `ictas` (
   IncentiveAssocID int unsigned auto_increment not null,
   IncentiveID int,
   OrganizationID int,
   IncentiveTypeID int,
   AverageValue decimal(12,4),
   IncentiveStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ictas_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveAssocID int unsigned  not null,
   IncentiveID int,
   OrganizationID int,
   IncentiveTypeID int,
   AverageValue decimal(12,4),
   IncentiveStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `ins` (
   PolicyID int unsigned auto_increment not null,
   PolicyNumber varchar(128),
   IntegrationKey varchar(128),
   OrganizationID int,
   RetentionAcct bool default 0,
   InsuranceTypeID int unsigned not null,
   PolicyName varchar(128),
   PolicyDesc text,
   PolicyStatusID int,
   CarrierNameID int,
   EstimatedPremium decimal(12,4),
   PremiumPaid decimal(12,4),
   PremiumValuationDate date,
   BrokerNameID int,
   BrokerageFee decimal(12,4),
   PercentofPremium float,
   ExcessCarrierID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ins_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyID int unsigned  not null,
   PolicyNumber varchar(128),
   IntegrationKey varchar(128),
   OrganizationID int,
   RetentionAcct bool default 0,
   InsuranceTypeID int unsigned not null,
   PolicyName varchar(128),
   PolicyDesc text,
   PolicyStatusID int,
   CarrierNameID int,
   EstimatedPremium decimal(12,4),
   PremiumPaid decimal(12,4),
   PremiumValuationDate date,
   BrokerNameID int,
   BrokerageFee decimal(12,4),
   PercentofPremium float,
   ExcessCarrierID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyID
   )
) TYPE=InnoDB;

CREATE TABLE `insa` (
   InsuranceAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PolicyID int,
   PolicyAssociationTypeID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insa_l` (
   _RecordID int unsigned not null auto_increment,
   InsuranceAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PolicyID int,
   PolicyAssociationTypeID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuranceAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `insc` (
   InsuranceConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   InsuranceConsiderationTitle varchar(128),
   InsuranceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insc_l` (
   _RecordID int unsigned not null auto_increment,
   InsuranceConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   InsuranceConsiderationTitle varchar(128),
   InsuranceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuranceConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `inscl` (
   CoverageLimitID int unsigned auto_increment not null,
   PolicyID int,
   CoverageLimitTypeID int unsigned,
   LimitAmount decimal(12,4),
   LimitDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CoverageLimitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inscl_l` (
   _RecordID int unsigned not null auto_increment,
   CoverageLimitID int unsigned  not null,
   PolicyID int,
   CoverageLimitTypeID int unsigned,
   LimitAmount decimal(12,4),
   LimitDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CoverageLimitID
   )
) TYPE=InnoDB;

CREATE TABLE `insff` (
   FinalFactorID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossFactorID int unsigned not null,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FinalFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insff_l` (
   _RecordID int unsigned not null auto_increment,
   FinalFactorID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossFactorID int unsigned not null,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FinalFactorID
   )
) TYPE=InnoDB;

CREATE TABLE `inslc` (
   PeriodLossCostsID int unsigned auto_increment not null,
   LossTriangleValueID int unsigned not null,
   PercentValue float,
   OrganizationID int,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PeriodLossCostsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslc_l` (
   _RecordID int unsigned not null auto_increment,
   PeriodLossCostsID int unsigned  not null,
   LossTriangleValueID int unsigned not null,
   PercentValue float,
   OrganizationID int,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PeriodLossCostsID
   )
) TYPE=InnoDB;

CREATE TABLE `insld` (
   LossDevelopmentFactorID int unsigned auto_increment not null,
   OrganizationID int,
   DevelopmentFactorsTitle varchar(128),
   DevelopmentTypeID int,
   PolicyTypeID int,
   LDFDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossDevelopmentFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insld_l` (
   _RecordID int unsigned not null auto_increment,
   LossDevelopmentFactorID int unsigned  not null,
   OrganizationID int,
   DevelopmentFactorsTitle varchar(128),
   DevelopmentTypeID int,
   PolicyTypeID int,
   LDFDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossDevelopmentFactorID
   )
) TYPE=InnoDB;

CREATE TABLE `inslf` (
   LossFactorID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   Months1ID int,
   Months2ID int,
   AverageRate float,
   IndustryStandardRate float,
   SelectedRate float,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossFactorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslf_l` (
   _RecordID int unsigned not null auto_increment,
   LossFactorID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   Months1ID int,
   Months2ID int,
   AverageRate float,
   IndustryStandardRate float,
   SelectedRate float,
   FinalDevelopmentFactor float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossFactorID
   )
) TYPE=InnoDB;

CREATE TABLE `insli` (
   LossIncreaseID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossTriangleValue1ID int unsigned not null,
   LossTriangleValue2ID int unsigned not null,
   RateofIncrease float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossIncreaseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `insli_l` (
   _RecordID int unsigned not null auto_increment,
   LossIncreaseID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossTriangleValue1ID int unsigned not null,
   LossTriangleValue2ID int unsigned not null,
   RateofIncrease float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossIncreaseID
   )
) TYPE=InnoDB;

CREATE TABLE `inslp` (
   LossPeriodID int unsigned auto_increment not null,
   LossPeriodLabel varchar(5),
   StartDate date,
   EndDate date,
   InflationRate float,
   LossPeriodDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossPeriodID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslp_l` (
   _RecordID int unsigned not null auto_increment,
   LossPeriodID int unsigned  not null,
   LossPeriodLabel varchar(5),
   StartDate date,
   EndDate date,
   InflationRate float,
   LossPeriodDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossPeriodID
   )
) TYPE=InnoDB;

CREATE TABLE `inslt` (
   LossTriangleValueID int unsigned auto_increment not null,
   LossDevelopmentFactorID int unsigned not null,
   LossPeriodID int unsigned not null,
   Months float,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossTriangleValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inslt_l` (
   _RecordID int unsigned not null auto_increment,
   LossTriangleValueID int unsigned  not null,
   LossDevelopmentFactorID int unsigned not null,
   LossPeriodID int unsigned not null,
   Months float,
   LossCosts decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossTriangleValueID
   )
) TYPE=InnoDB;

CREATE TABLE `inso` (
   InsuredOrganizationID int unsigned auto_increment not null,
   OrganizationID int,
   PolicyID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuredOrganizationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inso_l` (
   _RecordID int unsigned not null auto_increment,
   InsuredOrganizationID int unsigned  not null,
   OrganizationID int,
   PolicyID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuredOrganizationID
   )
) TYPE=InnoDB;

CREATE TABLE `inst` (
   InsuranceTypeID int unsigned auto_increment not null,
   InsuranceCategoryID int,
   InsuranceTypeTitle varchar(128),
   InsuranceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InsuranceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inst_l` (
   _RecordID int unsigned not null auto_increment,
   InsuranceTypeID int unsigned  not null,
   InsuranceCategoryID int,
   InsuranceTypeTitle varchar(128),
   InsuranceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InsuranceTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `inv` (
   InvolvementID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   PersonInvolvedID int,
   InvolvementDescriptorID int,
   Details text,
   InvolvementStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InvolvementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `inv_l` (
   _RecordID int unsigned not null auto_increment,
   InvolvementID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   PersonInvolvedID int,
   InvolvementDescriptorID int,
   Details text,
   InvolvementStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InvolvementID
   )
) TYPE=InnoDB;

CREATE TABLE `ire` (
   IncidentReportID int unsigned auto_increment not null,
   OrganizationID int,
   MannerReceivedID int,
   ReportSourceID int,
   RepPersonTypeID int,
   OnCoPropID int,
   RepEvent text,
   SubObserv text,
   IncidentName varchar(128),
   CodeName varchar(50),
   PotVioLaw bool default 0,
   GovtRept bool default 0,
   InvRptDftComp bool default 0,
   InvRptDftCompDate date,
   InvRptDftSent bool default 0,
   InvRptDftSentDate datetime,
   InvRptDftReceived bool default 0,
   InvRptDftRecDate date,
   InvRptFinalized bool default 0,
   InvRptFinalDate datetime,
   IncidentStatusID int,
   KeyLearning text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncidentReportID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ire_l` (
   _RecordID int unsigned not null auto_increment,
   IncidentReportID int unsigned  not null,
   OrganizationID int,
   MannerReceivedID int,
   ReportSourceID int,
   RepPersonTypeID int,
   OnCoPropID int,
   RepEvent text,
   SubObserv text,
   IncidentName varchar(128),
   CodeName varchar(50),
   PotVioLaw bool default 0,
   GovtRept bool default 0,
   InvRptDftComp bool default 0,
   InvRptDftCompDate date,
   InvRptDftSent bool default 0,
   InvRptDftSentDate datetime,
   InvRptDftReceived bool default 0,
   InvRptDftRecDate date,
   InvRptFinalized bool default 0,
   InvRptFinalDate datetime,
   IncidentStatusID int,
   KeyLearning text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncidentReportID
   )
) TYPE=InnoDB;

CREATE TABLE `irel` (
   LetterID int unsigned auto_increment not null,
   IncidentReportID int,
   ContactTypeID int,
   LetterOrganizationID int,
   LetterDate date,
   PostDate date,
   OrganizationID int,
   LetterSummary text,
   LetterAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LetterID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `irel_l` (
   _RecordID int unsigned not null auto_increment,
   LetterID int unsigned  not null,
   IncidentReportID int,
   ContactTypeID int,
   LetterOrganizationID int,
   LetterDate date,
   PostDate date,
   OrganizationID int,
   LetterSummary text,
   LetterAttached bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LetterID
   )
) TYPE=InnoDB;

CREATE TABLE `iren` (
   IRNumberID int unsigned auto_increment not null,
   IncidentReportID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IRNumberID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `iren_l` (
   _RecordID int unsigned not null auto_increment,
   IRNumberID int unsigned  not null,
   IncidentReportID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IRNumberID
   )
) TYPE=InnoDB;

CREATE TABLE `ireoc` (
   OutCounselID int unsigned auto_increment not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   LawFirmID int,
   LitigationRelationshipID int,
   RepresentationDetails text,
   CostofRepresentation decimal(12,4),
   RepresentationStatusID int,
   ResolutionOutcomeID int,
   ResolutionCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OutCounselID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ireoc_l` (
   _RecordID int unsigned not null auto_increment,
   OutCounselID int unsigned  not null,
   IncidentReportID int,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   LawFirmID int,
   LitigationRelationshipID int,
   RepresentationDetails text,
   CostofRepresentation decimal(12,4),
   RepresentationStatusID int,
   ResolutionOutcomeID int,
   ResolutionCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OutCounselID
   )
) TYPE=InnoDB;

CREATE TABLE `irepo` (
   PolicyID int unsigned auto_increment not null,
   IncidentReportID int,
   PartnershipID int,
   PolicyVariance text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `irepo_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyID int unsigned  not null,
   IncidentReportID int,
   PartnershipID int,
   PolicyVariance text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyID
   )
) TYPE=InnoDB;

CREATE TABLE `ireps` (
   IRProductOrServiceID int unsigned auto_increment not null,
   IncidentReportID int,
   ProdServID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IRProductOrServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ireps_l` (
   _RecordID int unsigned not null auto_increment,
   IRProductOrServiceID int unsigned  not null,
   IncidentReportID int,
   ProdServID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IRProductOrServiceID
   )
) TYPE=InnoDB;

CREATE TABLE `ires` (
   IncidentReportSituationID int unsigned auto_increment not null,
   IncidentReportID int,
   SituationTypeID int unsigned not null,
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncidentReportSituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ires_l` (
   _RecordID int unsigned not null auto_increment,
   IncidentReportSituationID int unsigned  not null,
   IncidentReportID int,
   SituationTypeID int unsigned not null,
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncidentReportSituationID
   )
) TYPE=InnoDB;

CREATE TABLE `iret` (
   TelephoneCallID int unsigned auto_increment not null,
   IncidentReportID int,
   ContactTypeID int,
   PhoningOrganizationID int,
   CallTime time,
   OrganizationID int,
   CallSummary text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TelephoneCallID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `iret_l` (
   _RecordID int unsigned not null auto_increment,
   TelephoneCallID int unsigned  not null,
   IncidentReportID int,
   ContactTypeID int,
   PhoningOrganizationID int,
   CallTime time,
   OrganizationID int,
   CallSummary text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TelephoneCallID
   )
) TYPE=InnoDB;

CREATE TABLE `irev` (
   VisitationID int unsigned auto_increment not null,
   IncidentReportID int,
   ContactTypeID int,
   VisitingOrganizationID int,
   OrganizationID int,
   MultiEmployer bool default 0,
   TradeSecret bool default 0,
   ContractorInspect bool default 0,
   ContractorID int,
   VisitReasonID int,
   VisitReasonDesc text,
   ConfBeginTime datetime,
   ConfEndTime datetime,
   OpenConfRemarks text,
   WalkBeginTime datetime,
   WalkEndTime datetime,
   WalkRoute text,
   WalkRemarks text,
   WalkSamples text,
   Samples bool default 0,
   WalkPhotos text,
   Photos bool default 0,
   CloseBeginTime datetime,
   CloseEndTime datetime,
   ClosingDesc text,
   MaterialExchange text,
   Citations bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VisitationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `irev_l` (
   _RecordID int unsigned not null auto_increment,
   VisitationID int unsigned  not null,
   IncidentReportID int,
   ContactTypeID int,
   VisitingOrganizationID int,
   OrganizationID int,
   MultiEmployer bool default 0,
   TradeSecret bool default 0,
   ContractorInspect bool default 0,
   ContractorID int,
   VisitReasonID int,
   VisitReasonDesc text,
   ConfBeginTime datetime,
   ConfEndTime datetime,
   OpenConfRemarks text,
   WalkBeginTime datetime,
   WalkEndTime datetime,
   WalkRoute text,
   WalkRemarks text,
   WalkSamples text,
   Samples bool default 0,
   WalkPhotos text,
   Photos bool default 0,
   CloseBeginTime datetime,
   CloseEndTime datetime,
   ClosingDesc text,
   MaterialExchange text,
   Citations bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VisitationID
   )
) TYPE=InnoDB;

CREATE TABLE `jan` (
   JobAnalysisID int unsigned auto_increment not null,
   OrganizationID int,
   FunctionID int,
   JobAnalysisTitle varchar(128),
   JobAnalysisDesc text,
   JobAnalysisNumber varchar(50),
   JobAnalysisStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobAnalysisID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jan_l` (
   _RecordID int unsigned not null auto_increment,
   JobAnalysisID int unsigned  not null,
   OrganizationID int,
   FunctionID int,
   JobAnalysisTitle varchar(128),
   JobAnalysisDesc text,
   JobAnalysisNumber varchar(50),
   JobAnalysisStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobAnalysisID
   )
) TYPE=InnoDB;

CREATE TABLE `jana` (
   JobAnalysisAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   JobAnalysisID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobAnalysisAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jana_l` (
   _RecordID int unsigned not null auto_increment,
   JobAnalysisAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   JobAnalysisID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobAnalysisAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `jank` (
   JobKSAID int unsigned auto_increment not null,
   JobAnalysisID int,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jank_l` (
   _RecordID int unsigned not null auto_increment,
   JobKSAID int unsigned  not null,
   JobAnalysisID int,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobKSAID
   )
) TYPE=InnoDB;

CREATE TABLE `janst` (
   JobSpecificTaskID int unsigned auto_increment not null,
   JobAnalysisID int unsigned not null,
   SortOrder int,
   TaskOrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobSpecificTaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `janst_l` (
   _RecordID int unsigned not null auto_increment,
   JobSpecificTaskID int unsigned  not null,
   JobAnalysisID int unsigned not null,
   SortOrder int,
   TaskOrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobSpecificTaskID
   )
) TYPE=InnoDB;

CREATE TABLE `jant` (
   JobTaskID int unsigned auto_increment not null,
   JobAnalysisID int,
   SortOrder int,
   TaskID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jant_l` (
   _RecordID int unsigned not null auto_increment,
   JobTaskID int unsigned  not null,
   JobAnalysisID int,
   SortOrder int,
   TaskID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTaskID
   )
) TYPE=InnoDB;

CREATE TABLE `ksa` (
   KSAID int unsigned auto_increment not null,
   CapabilityID int unsigned not null,
   KSATitle varchar(30),
   KSADesc text,
   KSAAbbr varchar(5),
   QualificationMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      KSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksa_l` (
   _RecordID int unsigned not null auto_increment,
   KSAID int unsigned  not null,
   CapabilityID int unsigned not null,
   KSATitle varchar(30),
   KSADesc text,
   KSAAbbr varchar(5),
   QualificationMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      KSAID
   )
) TYPE=InnoDB;

CREATE TABLE `ksaae` (
   AreaID int unsigned auto_increment not null,
   OrganizationID int,
   KSAAreaID int,
   AreaTitle varchar(30),
   AreaDesc text,
   AreaAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AreaID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksaae_l` (
   _RecordID int unsigned not null auto_increment,
   AreaID int unsigned  not null,
   OrganizationID int,
   KSAAreaID int,
   AreaTitle varchar(30),
   AreaDesc text,
   AreaAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AreaID
   )
) TYPE=InnoDB;

CREATE TABLE `ksal` (
   LevelID int unsigned auto_increment not null,
   KSAID int unsigned not null,
   KSALevelID int,
   KSALevelDesc text,
   ReviewCriteria text,
   TrainingQualified bool default 0,
   ExperienceQualified bool default 0,
   MedicallyQualified bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LevelID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksal_l` (
   _RecordID int unsigned not null auto_increment,
   LevelID int unsigned  not null,
   KSAID int unsigned not null,
   KSALevelID int,
   KSALevelDesc text,
   ReviewCriteria text,
   TrainingQualified bool default 0,
   ExperienceQualified bool default 0,
   MedicallyQualified bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LevelID
   )
) TYPE=InnoDB;

CREATE TABLE `ksasc` (
   CapabilityID int unsigned auto_increment not null,
   AreaID int unsigned not null,
   CapabilityTitle varchar(128),
   CapabilityDesc text,
   CapabilityAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CapabilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ksasc_l` (
   _RecordID int unsigned not null auto_increment,
   CapabilityID int unsigned  not null,
   AreaID int unsigned not null,
   CapabilityTitle varchar(128),
   CapabilityDesc text,
   CapabilityAbbr varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CapabilityID
   )
) TYPE=InnoDB;

CREATE TABLE `lbr` (
   LineBreakID int unsigned auto_increment not null,
   OrganizationID int,
   LineBreakTypeID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   SystemID int,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lbr_l` (
   _RecordID int unsigned not null auto_increment,
   LineBreakID int unsigned  not null,
   OrganizationID int,
   LineBreakTypeID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   SystemID int,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LineBreakID
   )
) TYPE=InnoDB;

CREATE TABLE `lbrp` (
   LineBreakPermitID int unsigned auto_increment not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   LineBreakShiftID int,
   LineBreakID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lbrp_l` (
   _RecordID int unsigned not null auto_increment,
   LineBreakPermitID int unsigned  not null,
   JobNumber varchar(50),
   PermitNumber varchar(50),
   WorkOrderID int unsigned not null,
   LineBreakShiftID int,
   LineBreakID int,
   Explanation text,
   KeyLearnings text,
   SpecialHazards text,
   PerformerRelationshipID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LineBreakPermitID
   )
) TYPE=InnoDB;

CREATE TABLE `lbrt` (
   LineBreakTypeID int unsigned auto_increment not null,
   LineBreakCategoryID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lbrt_l` (
   _RecordID int unsigned not null auto_increment,
   LineBreakTypeID int unsigned  not null,
   LineBreakCategoryID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LineBreakTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `lch` (
   CharacteristicID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LossCharID int,
   LossCharDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CharacteristicID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lch_l` (
   _RecordID int unsigned not null auto_increment,
   CharacteristicID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LossCharID int,
   LossCharDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CharacteristicID
   )
) TYPE=InnoDB;

CREATE TABLE `lco` (
   LossCostID int unsigned auto_increment not null,
   ClaimID int unsigned not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ValuationDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lco_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostID int unsigned  not null,
   ClaimID int unsigned not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ValuationDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostID
   )
) TYPE=InnoDB;

CREATE TABLE `lcod` (
   LossCostDetailID int unsigned auto_increment not null,
   LossCostID int unsigned not null,
   LossCostTypeID int,
   Incurred decimal(12,4),
   Payments decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostDetailID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lcod_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostDetailID int unsigned  not null,
   LossCostID int unsigned not null,
   LossCostTypeID int,
   Incurred decimal(12,4),
   Payments decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostDetailID
   )
) TYPE=InnoDB;

CREATE TABLE `lcot` (
   LossCostTypeID int unsigned auto_increment not null,
   CostCategoryID int,
   CostType varchar(128),
   CostCode varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lcot_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostTypeID int unsigned  not null,
   CostCategoryID int,
   CostType varchar(128),
   CostCode varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `len` (
   LossEnvironmentID int unsigned auto_increment not null,
   OrganizationID int,
   ChemicalID int unsigned,
   SystemID int unsigned not null,
   SubSystemID int unsigned not null,
   ComponentID int unsigned not null,
   ReleaseTypeID int,
   ReleaseLossDesc text,
   ShiftID int,
   ReleaseDuration float,
   DurationUnitsID int,
   ConcentrationAmount float,
   ConcentrationUnitsID int,
   ReleaseAmount float,
   WeightVolUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEnvironmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `len_l` (
   _RecordID int unsigned not null auto_increment,
   LossEnvironmentID int unsigned  not null,
   OrganizationID int,
   ChemicalID int unsigned,
   SystemID int unsigned not null,
   SubSystemID int unsigned not null,
   ComponentID int unsigned not null,
   ReleaseTypeID int,
   ReleaseLossDesc text,
   ShiftID int,
   ReleaseDuration float,
   DurationUnitsID int,
   ConcentrationAmount float,
   ConcentrationUnitsID int,
   ReleaseAmount float,
   WeightVolUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEnvironmentID
   )
) TYPE=InnoDB;

CREATE TABLE `lin` (
   LossInjuryID int unsigned auto_increment not null,
   OrganizationID int,
   JobTitleID int,
   HrsPerDay float,
   DaysPerWeek float,
   EarningsCalculationMethodID int,
   EarningsStartDate date,
   EarningsEndDate date,
   LocalAvgDailyEarnings decimal(12,4),
   AvgDailyEarnings decimal(12,4),
   LocalAvgWeeklyEarnings decimal(12,4),
   AvgWeeklyEarnings decimal(12,4),
   LocalAvgMonthlyEarnings decimal(12,4),
   AvgMonthlyEarnings decimal(12,4),
   LocalAvgAnnualEarnings decimal(12,4),
   AvgAnnualEarnings decimal(12,4),
   EarningsAttached bool default 0,
   ShiftID int,
   TaskID int,
   InjuryTaskDesc text,
   InjuryExposureID int,
   InjuryExposureDesc text,
   InjurySource1ID int,
   InjurySource2ID int,
   InjurySourceDesc text,
   InjuryObject varchar(128),
   ObjectDesc text,
   RegulatoryRecordable bool default 0,
   PrivacyCase bool default 0,
   Rationale text,
   WorkBegan datetime,
   DateofDeath date,
   InjuryIllnessCategoryID int unsigned not null,
   InjuryIllnessTypeID int unsigned not null,
   PrimaryTreatingFacilityID int,
   EmergencyRoom bool default 0,
   Hospitalized bool default 0,
   RecorderJobTitleID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossInjuryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lin_l` (
   _RecordID int unsigned not null auto_increment,
   LossInjuryID int unsigned  not null,
   OrganizationID int,
   JobTitleID int,
   HrsPerDay float,
   DaysPerWeek float,
   EarningsCalculationMethodID int,
   EarningsStartDate date,
   EarningsEndDate date,
   LocalAvgDailyEarnings decimal(12,4),
   AvgDailyEarnings decimal(12,4),
   LocalAvgWeeklyEarnings decimal(12,4),
   AvgWeeklyEarnings decimal(12,4),
   LocalAvgMonthlyEarnings decimal(12,4),
   AvgMonthlyEarnings decimal(12,4),
   LocalAvgAnnualEarnings decimal(12,4),
   AvgAnnualEarnings decimal(12,4),
   EarningsAttached bool default 0,
   ShiftID int,
   TaskID int,
   InjuryTaskDesc text,
   InjuryExposureID int,
   InjuryExposureDesc text,
   InjurySource1ID int,
   InjurySource2ID int,
   InjurySourceDesc text,
   InjuryObject varchar(128),
   ObjectDesc text,
   RegulatoryRecordable bool default 0,
   PrivacyCase bool default 0,
   Rationale text,
   WorkBegan datetime,
   DateofDeath date,
   InjuryIllnessCategoryID int unsigned not null,
   InjuryIllnessTypeID int unsigned not null,
   PrimaryTreatingFacilityID int,
   EmergencyRoom bool default 0,
   Hospitalized bool default 0,
   RecorderJobTitleID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossInjuryID
   )
) TYPE=InnoDB;

CREATE TABLE `linbp` (
   BodyPartID int unsigned auto_increment not null,
   BodyPartTypeID int,
   BodyPartTitle varchar(128),
   BodyPartDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BodyPartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linbp_l` (
   _RecordID int unsigned not null auto_increment,
   BodyPartID int unsigned  not null,
   BodyPartTypeID int,
   BodyPartTitle varchar(128),
   BodyPartDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BodyPartID
   )
) TYPE=InnoDB;

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

CREATE TABLE `line` (
   EarningsID int unsigned auto_increment not null,
   LossInjuryID int,
   StartDate date,
   EndDate date,
   Amount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EarningsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `line_l` (
   _RecordID int unsigned not null auto_increment,
   EarningsID int unsigned  not null,
   LossInjuryID int,
   StartDate date,
   EndDate date,
   Amount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EarningsID
   )
) TYPE=InnoDB;

CREATE TABLE `linet` (
   InjuryExposureTypeID int unsigned auto_increment not null,
   InjuryExposureCategoryID int,
   ExposureType varchar(128),
   InjuryExposureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linet_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryExposureTypeID int unsigned  not null,
   InjuryExposureCategoryID int,
   ExposureType varchar(128),
   InjuryExposureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryExposureTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `linex` (
   InjuryExposureID int unsigned auto_increment not null,
   InjuryExposureTypeID int,
   InjuryExposureTitle varchar(128),
   InjuryExposureDivision varchar(10),
   InjuryExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linex_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryExposureID int unsigned  not null,
   InjuryExposureTypeID int,
   InjuryExposureTitle varchar(128),
   InjuryExposureDivision varchar(10),
   InjuryExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryExposureID
   )
) TYPE=InnoDB;

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

CREATE TABLE `linm` (
   WorkModificationID int unsigned auto_increment not null,
   LossInjuryID int,
   ModificationTypeID int,
   AuthorizingOrganizationID int,
   DisabilitySlipAttached bool default 0,
   DisabilityDescription text,
   DaysNotScheduled int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkModificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linm_l` (
   _RecordID int unsigned not null auto_increment,
   WorkModificationID int unsigned  not null,
   LossInjuryID int,
   ModificationTypeID int,
   AuthorizingOrganizationID int,
   DisabilitySlipAttached bool default 0,
   DisabilityDescription text,
   DaysNotScheduled int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkModificationID
   )
) TYPE=InnoDB;

CREATE TABLE `linna` (
   InjuryNatureID int unsigned auto_increment not null,
   InjuryNatureTypeID int,
   InjuryNatureTitle varchar(128),
   InjuryNatureDivision varchar(10),
   InjuryNatureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linna_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryNatureID int unsigned  not null,
   InjuryNatureTypeID int,
   InjuryNatureTitle varchar(128),
   InjuryNatureDivision varchar(10),
   InjuryNatureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryNatureID
   )
) TYPE=InnoDB;

CREATE TABLE `linnt` (
   InjuryNatureTypeID int unsigned auto_increment not null,
   InjuryNatureCategoryID int,
   NatureType varchar(128),
   InjuryNatureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linnt_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryNatureTypeID int unsigned  not null,
   InjuryNatureCategoryID int,
   NatureType varchar(128),
   InjuryNatureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryNatureTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `linsc` (
   InjurySourceID int unsigned auto_increment not null,
   InjurySourceTypeID int,
   InjurySourceTitle varchar(128),
   InjurySourceDivision varchar(10),
   InjurySourceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linsc_l` (
   _RecordID int unsigned not null auto_increment,
   InjurySourceID int unsigned  not null,
   InjurySourceTypeID int,
   InjurySourceTitle varchar(128),
   InjurySourceDivision varchar(10),
   InjurySourceDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjurySourceID
   )
) TYPE=InnoDB;

CREATE TABLE `linst` (
   InjurySourceTypeID int unsigned auto_increment not null,
   InjurySourceCategoryID int,
   SourceType varchar(128),
   InjurySourceTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjurySourceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linst_l` (
   _RecordID int unsigned not null auto_increment,
   InjurySourceTypeID int unsigned  not null,
   InjurySourceCategoryID int,
   SourceType varchar(128),
   InjurySourceTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjurySourceTypeID
   )
) TYPE=InnoDB;

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

CREATE TABLE `lit` (
   LossITID int unsigned auto_increment not null,
   OrganizationID int,
   ITIncidentDesc text,
   ITIncidentStatusID int,
   ITIncidentIndicatorID int,
   ITIndicatorDesc text,
   ITIndicatorStatusID int,
   ShiftID int,
   IncidentDuration float,
   DurationUnitsID int,
   ITLossDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossITID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lit_l` (
   _RecordID int unsigned not null auto_increment,
   LossITID int unsigned  not null,
   OrganizationID int,
   ITIncidentDesc text,
   ITIncidentStatusID int,
   ITIncidentIndicatorID int,
   ITIndicatorDesc text,
   ITIndicatorStatusID int,
   ShiftID int,
   IncidentDuration float,
   DurationUnitsID int,
   ITLossDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossITID
   )
) TYPE=InnoDB;

CREATE TABLE `litii` (
   ITIncidentIndicatorID int unsigned auto_increment not null,
   IndicatorCategoryID int,
   IndicatorType varchar(128),
   IndicatorDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ITIncidentIndicatorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `litii_l` (
   _RecordID int unsigned not null auto_increment,
   ITIncidentIndicatorID int unsigned  not null,
   IndicatorCategoryID int,
   IndicatorType varchar(128),
   IndicatorDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ITIncidentIndicatorID
   )
) TYPE=InnoDB;

CREATE TABLE `lli` (
   LossLegitimacyID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LossLegitimacyTypeID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossLegitimacyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lli_l` (
   _RecordID int unsigned not null auto_increment,
   LossLegitimacyID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LossLegitimacyTypeID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossLegitimacyID
   )
) TYPE=InnoDB;

CREATE TABLE `llit` (
   LossLegitimacyTypeID int unsigned auto_increment not null,
   LegitimacyCategoryID int,
   LegitimacyType varchar(128),
   LegitimacyCode varchar(10),
   LegitimacyDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossLegitimacyTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `llit_l` (
   _RecordID int unsigned not null auto_increment,
   LossLegitimacyTypeID int unsigned  not null,
   LegitimacyCategoryID int,
   LegitimacyType varchar(128),
   LegitimacyCode varchar(10),
   LegitimacyDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossLegitimacyTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `lnk` (
   LinkID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LinkAddress varchar(128),
   LinkTitle varchar(128),
   LinkDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LinkID
   ),
   INDEX lnk_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lnk_l` (
   _RecordID int unsigned not null auto_increment,
   LinkID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LinkAddress varchar(128),
   LinkTitle varchar(128),
   LinkDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LinkID
   ),
   INDEX lnk_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `lpa` (
   LossEmpPracticeID int unsigned auto_increment not null,
   OrganizationID int,
   ShiftID int,
   PracticeTypeID int,
   Assertions text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEmpPracticeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpa_l` (
   _RecordID int unsigned not null auto_increment,
   LossEmpPracticeID int unsigned  not null,
   OrganizationID int,
   ShiftID int,
   PracticeTypeID int,
   Assertions text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEmpPracticeID
   )
) TYPE=InnoDB;

CREATE TABLE `lpas` (
   SubjectID int unsigned auto_increment not null,
   LossEmpPracticeID int,
   OrganizationID int,
   ComplaintDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SubjectID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpas_l` (
   _RecordID int unsigned not null auto_increment,
   SubjectID int unsigned  not null,
   LossEmpPracticeID int,
   OrganizationID int,
   ComplaintDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SubjectID
   )
) TYPE=InnoDB;

CREATE TABLE `lpd` (
   LossProdServID int unsigned auto_increment not null,
   OrganizationID int,
   LossProdServDesc text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossProdServID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpd_l` (
   _RecordID int unsigned not null auto_increment,
   LossProdServID int unsigned  not null,
   OrganizationID int,
   LossProdServDesc text,
   DesiredOutcome text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossProdServID
   )
) TYPE=InnoDB;

CREATE TABLE `lpdps` (
   LossProdServAssnID int unsigned auto_increment not null,
   LossProdServID int unsigned not null,
   ProdServID int unsigned not null,
   MethodofHarm text,
   DefectOriginID int,
   DefectDescription text,
   HazardDescription text,
   SupplierRelated bool default 0,
   TraceNumber varchar(128),
   BatchNumber varchar(128),
   LotNumber varchar(128),
   ModelNumber varchar(128),
   ProductionDate datetime,
   ShippingDate datetime,
   ProductionReviewDate datetime,
   AmountProduced float,
   AmountProducedUnitID int,
   ProductionConclusion text,
   ProcessReviewDate datetime,
   AmountInProcess float,
   AmountInProcessUnitID int,
   ProcessConclusion text,
   ShippingReviewDate datetime,
   AmountShipped float,
   AmountShippedUnitID int,
   ShippingConclusion text,
   InventoryReviewDate datetime,
   AmountinInventory float,
   AmountinInventoryUnitID int,
   InventoryConclusion text,
   ProductAvailable bool default 0,
   PresentDesc text,
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossProdServAssnID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lpdps_l` (
   _RecordID int unsigned not null auto_increment,
   LossProdServAssnID int unsigned  not null,
   LossProdServID int unsigned not null,
   ProdServID int unsigned not null,
   MethodofHarm text,
   DefectOriginID int,
   DefectDescription text,
   HazardDescription text,
   SupplierRelated bool default 0,
   TraceNumber varchar(128),
   BatchNumber varchar(128),
   LotNumber varchar(128),
   ModelNumber varchar(128),
   ProductionDate datetime,
   ShippingDate datetime,
   ProductionReviewDate datetime,
   AmountProduced float,
   AmountProducedUnitID int,
   ProductionConclusion text,
   ProcessReviewDate datetime,
   AmountInProcess float,
   AmountInProcessUnitID int,
   ProcessConclusion text,
   ShippingReviewDate datetime,
   AmountShipped float,
   AmountShippedUnitID int,
   ShippingConclusion text,
   InventoryReviewDate datetime,
   AmountinInventory float,
   AmountinInventoryUnitID int,
   InventoryConclusion text,
   ProductAvailable bool default 0,
   PresentDesc text,
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossProdServAssnID
   )
) TYPE=InnoDB;

CREATE TABLE `lppb` (
   LossBuildingID int unsigned auto_increment not null,
   OrganizationID int,
   BuildingID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   PropertyAvailable bool default 0,
   PropertyAvailableDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossBuildingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppb_l` (
   _RecordID int unsigned not null auto_increment,
   LossBuildingID int unsigned  not null,
   OrganizationID int,
   BuildingID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   PropertyAvailable bool default 0,
   PropertyAvailableDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossBuildingID
   )
) TYPE=InnoDB;

CREATE TABLE `lppe` (
   LossEquipmentID int unsigned auto_increment not null,
   OrganizationID int,
   EquipmentTypeID int,
   EquipmentInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   EquipmentAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossEquipmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppe_l` (
   _RecordID int unsigned not null auto_increment,
   LossEquipmentID int unsigned  not null,
   OrganizationID int,
   EquipmentTypeID int,
   EquipmentInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   EquipmentAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossEquipmentID
   )
) TYPE=InnoDB;

CREATE TABLE `lppo` (
   LossOtherAssetID int unsigned auto_increment not null,
   OrganizationID int,
   OtherAssetInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   AssetAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossOtherAssetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppo_l` (
   _RecordID int unsigned not null auto_increment,
   LossOtherAssetID int unsigned  not null,
   OrganizationID int,
   OtherAssetInventoryID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   AssetAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossOtherAssetID
   )
) TYPE=InnoDB;

CREATE TABLE `lppv` (
   LossVehicleID int unsigned auto_increment not null,
   OrganizationID int,
   VehicleID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   VehicleAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossVehicleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lppv_l` (
   _RecordID int unsigned not null auto_increment,
   LossVehicleID int unsigned  not null,
   OrganizationID int,
   VehicleID int,
   PropOwnershipID int,
   PropertyLossTypeID int,
   PropertyLossDesc text,
   VehicleAvailable bool default 0,
   PresentDesc varchar(255),
   PresentAddress1 varchar(50),
   PresentAddress2 varchar(50),
   PresentCountyID int unsigned,
   PresentCity varchar(50),
   PresentPostalCode varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossVehicleID
   )
) TYPE=InnoDB;

CREATE TABLE `lstt` (
   LossStatusTypeID int unsigned auto_increment not null,
   LossStatusCategoryID int,
   LossStatus varchar(128),
   LossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossStatusTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lstt_l` (
   _RecordID int unsigned not null auto_increment,
   LossStatusTypeID int unsigned  not null,
   LossStatusCategoryID int,
   LossStatus varchar(128),
   LossStatusDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossStatusTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `mea` (
   MeasurementID int unsigned auto_increment not null,
   MeasurementTitle varchar(128),
   MeasurementPurpose text,
   MeasurementRationale text,
   BenchmarkValue float,
   BenchmarkDescription text,
   OrganizationID int,
   MeasurementOriginDate date,
   MeasurementStatusID int,
   NumeratorTitle varchar(128),
   NumeratorModuleID varchar(5),
   NumeratorOrganizationID int,
   NumeratorTypeID int,
   DenominatorTitle varchar(128),
   DenominatorModuleID varchar(5),
   DenominatorOrganizationID int,
   DenominatorTypeID int,
   StandardBase float,
   ChartTypeID int,
   XIntervalValue float,
   XIntervalID int,
   MeasurementStartDate date,
   MeasurementEndDate date,
   Resultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MeasurementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mea_l` (
   _RecordID int unsigned not null auto_increment,
   MeasurementID int unsigned  not null,
   MeasurementTitle varchar(128),
   MeasurementPurpose text,
   MeasurementRationale text,
   BenchmarkValue float,
   BenchmarkDescription text,
   OrganizationID int,
   MeasurementOriginDate date,
   MeasurementStatusID int,
   NumeratorTitle varchar(128),
   NumeratorModuleID varchar(5),
   NumeratorOrganizationID int,
   NumeratorTypeID int,
   DenominatorTitle varchar(128),
   DenominatorModuleID varchar(5),
   DenominatorOrganizationID int,
   DenominatorTypeID int,
   StandardBase float,
   ChartTypeID int,
   XIntervalValue float,
   XIntervalID int,
   MeasurementStartDate date,
   MeasurementEndDate date,
   Resultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MeasurementID
   )
) TYPE=InnoDB;

CREATE TABLE `meaa` (
   AssignedMeasurementID int unsigned auto_increment not null,
   MeasurementID int,
   NumeratorOrganizationID int,
   DenominatorOrganizationID int,
   XIntervalID int,
   AssignedOrganizationID int,
   AssigneeResultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AssignedMeasurementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `meaa_l` (
   _RecordID int unsigned not null auto_increment,
   AssignedMeasurementID int unsigned  not null,
   MeasurementID int,
   NumeratorOrganizationID int,
   DenominatorOrganizationID int,
   XIntervalID int,
   AssignedOrganizationID int,
   AssigneeResultant float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AssignedMeasurementID
   )
) TYPE=InnoDB;

CREATE TABLE `med` (
   MedicalExamID int unsigned auto_increment not null,
   OrganizationID int,
   MedicalExamTypeID int unsigned not null,
   MedicalExamTitle varchar(128),
   MedicalExamDesc text,
   MedicalExamCriteria text,
   SchedFreq float,
   SchedFreqUnitsID int,
   MedicalProviderID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `med_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamID int unsigned  not null,
   OrganizationID int,
   MedicalExamTypeID int unsigned not null,
   MedicalExamTitle varchar(128),
   MedicalExamDesc text,
   MedicalExamCriteria text,
   SchedFreq float,
   SchedFreqUnitsID int,
   MedicalProviderID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamID
   )
) TYPE=InnoDB;

CREATE TABLE `medc` (
   MedicalConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   MedicalConsiderationTitle varchar(128),
   MedicalConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medc_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   MedicalConsiderationTitle varchar(128),
   MedicalConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `medee` (
   MedicalExamElementID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ExamElementID int,
   ExamElementDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamElementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medee_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamElementID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ExamElementID int,
   ExamElementDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamElementID
   )
) TYPE=InnoDB;

CREATE TABLE `medep` (
   ExamProcedureStepID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ProcedureStepOrder float,
   ProcedureStepTitle varchar(128),
   ProcedureStepDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExamProcedureStepID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medep_l` (
   _RecordID int unsigned not null auto_increment,
   ExamProcedureStepID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ProcedureStepOrder float,
   ProcedureStepTitle varchar(128),
   ProcedureStepDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExamProcedureStepID
   )
) TYPE=InnoDB;

CREATE TABLE `medes` (
   MedicalExamServiceID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ExamServiceID int,
   ExamServiceDetail text,
   SchedFreq float,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medes_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamServiceID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ExamServiceID int,
   ExamServiceDetail text,
   SchedFreq float,
   SchedFreqUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamServiceID
   )
) TYPE=InnoDB;

CREATE TABLE `medet` (
   MedicalExamTypeID int unsigned auto_increment not null,
   ExamCategoryID int,
   ExamType varchar(128),
   ExamTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MedicalExamTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medet_l` (
   _RecordID int unsigned not null auto_increment,
   MedicalExamTypeID int unsigned  not null,
   ExamCategoryID int,
   ExamType varchar(128),
   ExamTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MedicalExamTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `medse` (
   ScheduledExamID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   ScheduledProviderID int,
   ExamResultsID int,
   ExamResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ScheduledExamID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medse_l` (
   _RecordID int unsigned not null auto_increment,
   ScheduledExamID int unsigned  not null,
   MedicalExamID int unsigned not null,
   ScheduledProviderID int,
   ExamResultsID int,
   ExamResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ScheduledExamID
   )
) TYPE=InnoDB;

CREATE TABLE `medsr` (
   ExamServiceResultID int unsigned auto_increment not null,
   MedicalExamID int unsigned not null,
   MedicalExamServiceID int unsigned not null,
   ScheduledExamID int unsigned not null,
   ScheduledServiceProviderID int,
   ServiceResultsID int,
   ServiceResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExamServiceResultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `medsr_l` (
   _RecordID int unsigned not null auto_increment,
   ExamServiceResultID int unsigned  not null,
   MedicalExamID int unsigned not null,
   MedicalExamServiceID int unsigned not null,
   ScheduledExamID int unsigned not null,
   ScheduledServiceProviderID int,
   ServiceResultsID int,
   ServiceResultsDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExamServiceResultID
   )
) TYPE=InnoDB;

CREATE TABLE `moc` (
   ManagedChangeID int unsigned auto_increment not null,
   ChangeGuidelineID int unsigned not null,
   ManagedChangeTitle text,
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   OrganizationID int,
   ManagedChangeStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ManagedChangeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `moc_l` (
   _RecordID int unsigned not null auto_increment,
   ManagedChangeID int unsigned  not null,
   ChangeGuidelineID int unsigned not null,
   ManagedChangeTitle text,
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   OrganizationID int,
   ManagedChangeStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ManagedChangeID
   )
) TYPE=InnoDB;

CREATE TABLE `mocg` (
   ChangeGuidelineID int unsigned auto_increment not null,
   PolicyOrganizationID int,
   ChangeCategoryID int,
   GuidelineTitle varchar(128),
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   GuidelineStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChangeGuidelineID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mocg_l` (
   _RecordID int unsigned not null auto_increment,
   ChangeGuidelineID int unsigned  not null,
   PolicyOrganizationID int,
   ChangeCategoryID int,
   GuidelineTitle varchar(128),
   Scope text,
   Degree text,
   DesignConsiderations text,
   ConstructionConsiderations text,
   OperationalConsiderations text,
   MaintenanceConsiderations text,
   DecommissionConsiderations text,
   GuidelineStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChangeGuidelineID
   )
) TYPE=InnoDB;

CREATE TABLE `mod` (
   ModuleID varchar(5) not null,
   Name varchar(128),
   ModuleDesc text,
   StandAlone bool default 0,
   SubModule bool default 0,
   Association bool default 0,
   Global bool default 0,
   Remote bool default 0,
   ModuleStatusID int unsigned,
   GlobalDiscussionAddress varchar(50),
   LocalDiscussionAddress varchar(50),
   LastParsed datetime,
   ParentModuleID varchar(5),
   OwnerField varchar(50),
   RecordDescriptionField varchar(50),
   RevisionAuthor varchar(30),
   RevisionNumber int unsigned,
   RevisionDate varchar(50),
   RecordLabelField varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mod_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   Name varchar(128),
   ModuleDesc text,
   StandAlone bool default 0,
   SubModule bool default 0,
   Association bool default 0,
   Global bool default 0,
   Remote bool default 0,
   ModuleStatusID int unsigned,
   GlobalDiscussionAddress varchar(50),
   LocalDiscussionAddress varchar(50),
   LastParsed datetime,
   ParentModuleID varchar(5),
   OwnerField varchar(50),
   RecordDescriptionField varchar(50),
   RevisionAuthor varchar(30),
   RevisionNumber int unsigned,
   RevisionDate varchar(50),
   RecordLabelField varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleID
   )
) TYPE=InnoDB;

CREATE TABLE `modch` (
   ModuleChartID int unsigned auto_increment not null,
   ModuleID varchar(5),
   Name varchar(10),
   Title varchar(128),
   Type varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleChartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modch_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleChartID int unsigned  not null,
   ModuleID varchar(5),
   Name varchar(10),
   Title varchar(128),
   Type varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleChartID
   )
) TYPE=InnoDB;

CREATE TABLE `modd` (
   ModuleDependencyID int unsigned auto_increment not null,
   ModuleID varchar(5),
   DependencyID varchar(5),
   ForeignDependency bool default 0,
   SubModDependency bool default 0,
   RemoteDependency bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleDependencyID
   ),
   INDEX modd_ModuleID (
      ModuleID
   ),
   INDEX modd_DependencyID (
      DependencyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modd_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleDependencyID int unsigned  not null,
   ModuleID varchar(5),
   DependencyID varchar(5),
   ForeignDependency bool default 0,
   SubModDependency bool default 0,
   RemoteDependency bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleDependencyID
   ),
   INDEX modd_l_ModuleID (
      ModuleID
   ),
   INDEX modd_l_DependencyID (
      DependencyID
   )
) TYPE=InnoDB;

CREATE TABLE `moddr` (
   ModuleDirectionID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   OrganizationID int,
   DirectionTitle varchar(128),
   Direction text,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleDirectionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `moddr_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleDirectionID int unsigned  not null,
   RelatedModuleID varchar(5),
   OrganizationID int,
   DirectionTitle varchar(128),
   Direction text,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleDirectionID
   )
) TYPE=InnoDB;

CREATE TABLE `modgt` (
   RecordID int unsigned auto_increment not null,
   ModuleID varchar(5) not null,
   Duration float unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecordID
   ),
   INDEX modgt_ModuleID (
      ModuleID,
      Duration
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modgt_l` (
   _RecordID int unsigned not null auto_increment,
   RecordID int unsigned  not null,
   ModuleID varchar(5) not null,
   Duration float unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecordID
   ),
   INDEX modgt_l_ModuleID (
      ModuleID,
      Duration
   )
) TYPE=InnoDB;

CREATE TABLE `modir` (
   IssueReportID int unsigned auto_increment not null,
   OrganizationID int,
   ModuleID varchar(5),
   IssueCategoryID int unsigned,
   IssueTypeID int unsigned,
   IssueBrowserID int unsigned,
   IssueBrowserVersion varchar(25),
   IssueOperatingSystemID int unsigned,
   IssueOperatingSystemVersion varchar(25),
   IssueTitle varchar(255),
   IssueDesc text,
   PersonReportingID int unsigned,
   ReportedPriorityID int unsigned,
   PosttoActiveAgenda bool default 1,
   PersonAccountableID int unsigned,
   AccountablePriorityID int unsigned,
   ResolutionComplexityID int unsigned,
   HoursEstimate float,
   IssueStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IssueReportID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modir_l` (
   _RecordID int unsigned not null auto_increment,
   IssueReportID int unsigned  not null,
   OrganizationID int,
   ModuleID varchar(5),
   IssueCategoryID int unsigned,
   IssueTypeID int unsigned,
   IssueBrowserID int unsigned,
   IssueBrowserVersion varchar(25),
   IssueOperatingSystemID int unsigned,
   IssueOperatingSystemVersion varchar(25),
   IssueTitle varchar(255),
   IssueDesc text,
   PersonReportingID int unsigned,
   ReportedPriorityID int unsigned,
   PosttoActiveAgenda bool default 1,
   PersonAccountableID int unsigned,
   AccountablePriorityID int unsigned,
   ResolutionComplexityID int unsigned,
   HoursEstimate float,
   IssueStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IssueReportID
   )
) TYPE=InnoDB;

CREATE TABLE `modnr` (
   NotificationRecipientID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   OrganizationID int unsigned not null,
   RecipientID int unsigned,
   Details text,
   NotificationImmediacy float,
   NotificationImmediacyUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NotificationRecipientID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modnr_l` (
   _RecordID int unsigned not null auto_increment,
   NotificationRecipientID int unsigned  not null,
   RelatedModuleID varchar(5),
   OrganizationID int unsigned not null,
   RecipientID int unsigned,
   Details text,
   NotificationImmediacy float,
   NotificationImmediacyUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NotificationRecipientID
   )
) TYPE=InnoDB;

CREATE TABLE `modrp` (
   ModuleReportID int unsigned auto_increment not null,
   ModuleID varchar(5) not null,
   Name varchar(50),
   Title varchar(50),
   LocationLevel varchar(10),
   LocationGroup varchar(20),
   Format varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleReportID
   ),
   INDEX modrp_Level (
      ModuleReportID,
      LocationLevel
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modrp_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleReportID int unsigned  not null,
   ModuleID varchar(5) not null,
   Name varchar(50),
   Title varchar(50),
   LocationLevel varchar(10),
   LocationGroup varchar(20),
   Format varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleReportID
   ),
   INDEX modrp_l_Level (
      ModuleReportID,
      LocationLevel
   )
) TYPE=InnoDB;

CREATE TABLE `mtg` (
   MeetingID int unsigned auto_increment not null,
   AssignedMeeting bool default 0,
   MasterAssignID int,
   OrganizationID int,
   MeetingTitle varchar(128),
   MeetingTypeID int,
   MeetingStartTime datetime,
   MeetingEndTime datetime,
   SeriesEndDate date,
   MeetingStatusID int,
   Agenda text,
   IssuesDiscussed text,
   DepartmentMeeting bool default 0,
   DepartmentID int,
   RegulatoryRequired bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MeetingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtg_l` (
   _RecordID int unsigned not null auto_increment,
   MeetingID int unsigned  not null,
   AssignedMeeting bool default 0,
   MasterAssignID int,
   OrganizationID int,
   MeetingTitle varchar(128),
   MeetingTypeID int,
   MeetingStartTime datetime,
   MeetingEndTime datetime,
   SeriesEndDate date,
   MeetingStatusID int,
   Agenda text,
   IssuesDiscussed text,
   DepartmentMeeting bool default 0,
   DepartmentID int,
   RegulatoryRequired bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MeetingID
   )
) TYPE=InnoDB;

CREATE TABLE `mtga` (
   AttendeeID int unsigned auto_increment not null,
   MeetingID int,
   PunctualnessID int,
   AttentivenessID int,
   AttendanceID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttendeeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtga_l` (
   _RecordID int unsigned not null auto_increment,
   AttendeeID int unsigned  not null,
   MeetingID int,
   PunctualnessID int,
   AttentivenessID int,
   AttendanceID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttendeeID
   )
) TYPE=InnoDB;

CREATE TABLE `mtgm` (
   MasterMeetingID int unsigned auto_increment not null,
   OrganizationID int,
   MeetingTypeID int,
   MeetingTitle varchar(128),
   Agenda text,
   RecurringMeeting bool default 0,
   SchedFreq float,
   SchedFreqUnitsID int,
   SeriesEndDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MasterMeetingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtgm_l` (
   _RecordID int unsigned not null auto_increment,
   MasterMeetingID int unsigned  not null,
   OrganizationID int,
   MeetingTypeID int,
   MeetingTitle varchar(128),
   Agenda text,
   RecurringMeeting bool default 0,
   SchedFreq float,
   SchedFreqUnitsID int,
   SeriesEndDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MasterMeetingID
   )
) TYPE=InnoDB;

CREATE TABLE `mtgma` (
   MasterAssignID int unsigned auto_increment not null,
   MasterMeetingID int,
   OrganizationID int,
   AssignmentDetails text,
   MasterLeaderObservations text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MasterAssignID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `mtgma_l` (
   _RecordID int unsigned not null auto_increment,
   MasterAssignID int unsigned  not null,
   MasterMeetingID int,
   OrganizationID int,
   AssignmentDetails text,
   MasterLeaderObservations text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MasterAssignID
   )
) TYPE=InnoDB;

CREATE TABLE `naic` (
   IndustryCodeID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   IndustryGroupID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryCodeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naic_l` (
   _RecordID int unsigned not null auto_increment,
   IndustryCodeID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   IndustryGroupID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustryCodeID
   )
) TYPE=InnoDB;

CREATE TABLE `naig` (
   IndustryGroupID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryGroupID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naig_l` (
   _RecordID int unsigned not null auto_increment,
   IndustryGroupID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustryGroupID
   )
) TYPE=InnoDB;

CREATE TABLE `nais` (
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySectorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `nais_l` (
   _RecordID int unsigned not null auto_increment,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustrySectorID
   )
) TYPE=InnoDB;

CREATE TABLE `naiss` (
   IndustrySubSectorID int unsigned not null,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySubSectorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naiss_l` (
   _RecordID int unsigned not null auto_increment,
   IndustrySubSectorID int unsigned not null,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustrySubSectorID
   )
) TYPE=InnoDB;

CREATE TABLE `ntf` (
   NotificationID int unsigned auto_increment not null,
   RelatedModuleID varchar(5) not null,
   RelatedRecordID int not null,
   Subject varchar(50) not null,
   Message text,
   XMLAttached bool,
   StatusID int,
   SenderID int,
   SentDate datetime,
   TextContent text,
   HTMLContent text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NotificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ntf_l` (
   _RecordID int unsigned not null auto_increment,
   NotificationID int unsigned  not null,
   RelatedModuleID varchar(5) not null,
   RelatedRecordID int not null,
   Subject varchar(50) not null,
   Message text,
   XMLAttached bool,
   StatusID int,
   SenderID int,
   SentDate datetime,
   TextContent text,
   HTMLContent text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NotificationID
   )
) TYPE=InnoDB;

CREATE TABLE `ntfap` (
   NotificationApproverID int unsigned auto_increment not null,
   OrganizationID int unsigned not null,
   RecipientOrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NotificationApproverID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ntfap_l` (
   _RecordID int unsigned not null auto_increment,
   NotificationApproverID int unsigned  not null,
   OrganizationID int unsigned not null,
   RecipientOrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NotificationApproverID
   )
) TYPE=InnoDB;

CREATE TABLE `ntfr` (
   NotificationRecipientID int unsigned not null auto_increment,
   NotificationID int unsigned not null,
   RecipientID int not null,
   StatusID int default 1,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NotificationRecipientID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ntfr_l` (
   _RecordID int unsigned not null auto_increment,
   NotificationRecipientID int unsigned not null ,
   NotificationID int unsigned not null,
   RecipientID int not null,
   StatusID int default 1,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NotificationRecipientID
   )
) TYPE=InnoDB;

CREATE TABLE `nts` (
   NoteID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title text,
   NoteDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NoteID
   ),
   INDEX nts_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `nts_l` (
   _RecordID int unsigned not null auto_increment,
   NoteID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   Title text,
   NoteDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NoteID
   ),
   INDEX nts_l_RelatedModuleIDRecordID (
      RelatedModuleID,
      RelatedRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `oas` (
   OtherAssetID int unsigned auto_increment not null,
   OrganizationID int,
   OtherAssetTitle varchar(128),
   OtherAssetTypeID int,
   OtherAssetDesc text,
   BaseUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherAssetID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oas_l` (
   _RecordID int unsigned not null auto_increment,
   OtherAssetID int unsigned  not null,
   OrganizationID int,
   OtherAssetTitle varchar(128),
   OtherAssetTypeID int,
   OtherAssetDesc text,
   BaseUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherAssetID
   )
) TYPE=InnoDB;

CREATE TABLE `oasin` (
   OtherAssetInventoryID int unsigned auto_increment not null,
   OtherAssetID int,
   OrganizationID int,
   TrackingNumber varchar(50),
   AssetStorageMethodID int,
   AssetStorageDesc varchar(255),
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued int,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherAssetInventoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oasin_l` (
   _RecordID int unsigned not null auto_increment,
   OtherAssetInventoryID int unsigned  not null,
   OtherAssetID int,
   OrganizationID int,
   TrackingNumber varchar(50),
   AssetStorageMethodID int,
   AssetStorageDesc varchar(255),
   ReOrderQuantity float,
   ReOrderQuantityUoMID int,
   StartQuantity float,
   StartQuantityUoMID int,
   StartDate date,
   Issued int,
   IssuedUoMID int,
   Returned float,
   ReturnedUoMID int,
   Added float,
   AddedUoMID int,
   Transferred float,
   TransferredUoMID int,
   PresentQuantity float,
   PresentQuantityUoMID int,
   PresentQuantityDate date,
   BeginningCalculationDate date,
   EndingCalculationDate date,
   LossQuantity float,
   LossQuantityUoMID int,
   LossCost decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherAssetInventoryID
   )
) TYPE=InnoDB;

CREATE TABLE `oast` (
   OtherAssetTransactionID int unsigned auto_increment not null,
   OtherAssetInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherAssetTransactionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oast_l` (
   _RecordID int unsigned not null auto_increment,
   OtherAssetTransactionID int unsigned  not null,
   OtherAssetInventoryID int,
   TransactionTypeID int,
   TransactionAmount float,
   TransAmountUoMID int,
   TransactionTime time,
   EstimatedReturnDate date,
   EstimatedReturnTime time,
   ActualReturnDate date,
   ActualReturnTime time,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherAssetTransactionID
   )
) TYPE=InnoDB;

CREATE TABLE `opdh` (
   HoursWorkedID int unsigned auto_increment not null,
   OrganizationID int,
   PayrollClassTypeID int,
   BeginDate date,
   EndDate date,
   Hours float,
   People float,
   Comment text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HoursWorkedID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdh_l` (
   _RecordID int unsigned not null auto_increment,
   HoursWorkedID int unsigned  not null,
   OrganizationID int,
   PayrollClassTypeID int,
   BeginDate date,
   EndDate date,
   Hours float,
   People float,
   Comment text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HoursWorkedID
   )
) TYPE=InnoDB;

CREATE TABLE `opdps` (
   ProdServID int unsigned auto_increment not null,
   OrganizationID int,
   ProductServiceTypeID int unsigned not null,
   ProdServName varchar(128),
   ProdServDesc text,
   ProdServOrigDate date,
   ProdServTrackingNo varchar(128),
   SKUNo varchar(128),
   ProductLifeCycleID int,
   ProdServStatusID int,
   ProdServDiscontinued bool default 0,
   DiscontinuedDate date,
   TraceMarkings text,
   TraceMarkingDestructibility bool default 0,
   ProductionRecords text,
   ProcessRecords text,
   TraceMarkingSample bool default 0,
   ProductionRecordSample bool default 0,
   ShippingRecordSample bool default 0,
   SalesRecordSample bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ProdServID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdps_l` (
   _RecordID int unsigned not null auto_increment,
   ProdServID int unsigned  not null,
   OrganizationID int,
   ProductServiceTypeID int unsigned not null,
   ProdServName varchar(128),
   ProdServDesc text,
   ProdServOrigDate date,
   ProdServTrackingNo varchar(128),
   SKUNo varchar(128),
   ProductLifeCycleID int,
   ProdServStatusID int,
   ProdServDiscontinued bool default 0,
   DiscontinuedDate date,
   TraceMarkings text,
   TraceMarkingDestructibility bool default 0,
   ProductionRecords text,
   ProcessRecords text,
   TraceMarkingSample bool default 0,
   ProductionRecordSample bool default 0,
   ShippingRecordSample bool default 0,
   SalesRecordSample bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ProdServID
   )
) TYPE=InnoDB;

CREATE TABLE `opdpt` (
   ProductServiceTypeID int unsigned auto_increment not null,
   ProductorServiceID int,
   ProductServiceCategoryID int,
   ProductServiceTypeTitle varchar(128),
   ProductServiceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ProductServiceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdpt_l` (
   _RecordID int unsigned not null auto_increment,
   ProductServiceTypeID int unsigned  not null,
   ProductorServiceID int,
   ProductServiceCategoryID int,
   ProductServiceTypeTitle varchar(128),
   ProductServiceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ProductServiceTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `opdr` (
   RevenueID int unsigned auto_increment not null,
   ProdServID int unsigned not null,
   BeginDate date,
   EndDate date,
   RevenueAmount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RevenueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdr_l` (
   _RecordID int unsigned not null auto_increment,
   RevenueID int unsigned  not null,
   ProdServID int unsigned not null,
   BeginDate date,
   EndDate date,
   RevenueAmount decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RevenueID
   )
) TYPE=InnoDB;

CREATE TABLE `opp` (
   OppPermitID int unsigned auto_increment not null,
   PermitName varchar(128),
   OrganizationID int,
   PermitTypeID int unsigned not null,
   PermitDesc text,
   AgencyLevelID int,
   AgencyID int,
   CriticalPermit bool default 0,
   ActivePermit bool default 0,
   TransferRestrict bool default 0,
   ModifyRestrict bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opp_l` (
   _RecordID int unsigned not null auto_increment,
   OppPermitID int unsigned  not null,
   PermitName varchar(128),
   OrganizationID int,
   PermitTypeID int unsigned not null,
   PermitDesc text,
   AgencyLevelID int,
   AgencyID int,
   CriticalPermit bool default 0,
   ActivePermit bool default 0,
   TransferRestrict bool default 0,
   ModifyRestrict bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppPermitID
   )
) TYPE=InnoDB;

CREATE TABLE `oppa` (
   OperatingPermitAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OppPermitID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OperatingPermitAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppa_l` (
   _RecordID int unsigned not null auto_increment,
   OperatingPermitAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OppPermitID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OperatingPermitAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `oppap` (
   PermitAppID int unsigned auto_increment not null,
   OppPermitID int,
   AppTypeID int,
   AppAppr bool default 0,
   AppExp date,
   ApplicationDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitAppID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppap_l` (
   _RecordID int unsigned not null auto_increment,
   PermitAppID int unsigned  not null,
   OppPermitID int,
   AppTypeID int,
   AppAppr bool default 0,
   AppExp date,
   ApplicationDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitAppID
   )
) TYPE=InnoDB;

CREATE TABLE `oppba` (
   PermitBuildID int unsigned auto_increment not null,
   OppPermitID int,
   OrganizationID int,
   BuildingID int,
   PermitBuildAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitBuildID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppba_l` (
   _RecordID int unsigned not null auto_increment,
   PermitBuildID int unsigned  not null,
   OppPermitID int,
   OrganizationID int,
   BuildingID int,
   PermitBuildAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitBuildID
   )
) TYPE=InnoDB;

CREATE TABLE `oppc` (
   PermitConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   PermitConsiderationTitle varchar(128),
   PermitConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppc_l` (
   _RecordID int unsigned not null auto_increment,
   PermitConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   PermitConsiderationTitle varchar(128),
   PermitConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `oppca` (
   PermitChemicalID int unsigned auto_increment not null,
   OppPermitID int,
   ChemicalInventoryID int,
   PermitChemicalAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitChemicalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppca_l` (
   _RecordID int unsigned not null auto_increment,
   PermitChemicalID int unsigned  not null,
   OppPermitID int,
   ChemicalInventoryID int,
   PermitChemicalAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitChemicalID
   )
) TYPE=InnoDB;

CREATE TABLE `oppcr` (
   PermitCondID int unsigned auto_increment not null,
   OppPermitID int,
   PermitRuleNo varchar(50),
   CondReq text,
   RefNo varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitCondID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppcr_l` (
   _RecordID int unsigned not null auto_increment,
   PermitCondID int unsigned  not null,
   OppPermitID int,
   PermitRuleNo varchar(50),
   CondReq text,
   RefNo varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitCondID
   )
) TYPE=InnoDB;

CREATE TABLE `oppea` (
   PermitEqptID int unsigned auto_increment not null,
   OppPermitID int,
   EquipmentInventoryID int,
   PermitEqptAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitEqptID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppea_l` (
   _RecordID int unsigned not null auto_increment,
   PermitEqptID int unsigned  not null,
   OppPermitID int,
   EquipmentInventoryID int,
   PermitEqptAffect text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitEqptID
   )
) TYPE=InnoDB;

CREATE TABLE `oppno` (
   OppNoID int unsigned auto_increment not null,
   OppPermitID int,
   AgencyID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppNoID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppno_l` (
   _RecordID int unsigned not null auto_increment,
   OppNoID int unsigned  not null,
   OppPermitID int,
   AgencyID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppNoID
   )
) TYPE=InnoDB;

CREATE TABLE `oppoa` (
   PermitOrgsID int unsigned auto_increment not null,
   OppPermitID int,
   OrganizationID int,
   Manner text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitOrgsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppoa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitOrgsID int unsigned  not null,
   OppPermitID int,
   OrganizationID int,
   Manner text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitOrgsID
   )
) TYPE=InnoDB;

CREATE TABLE `oppp` (
   PermitPenaltyID int unsigned auto_increment not null,
   OppPermitID int,
   PenaltyTypeID int,
   PenaltyDesc text,
   PenaltyAmount decimal(12,4),
   PenaltyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitPenaltyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppp_l` (
   _RecordID int unsigned not null auto_increment,
   PermitPenaltyID int unsigned  not null,
   OppPermitID int,
   PenaltyTypeID int,
   PenaltyDesc text,
   PenaltyAmount decimal(12,4),
   PenaltyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitPenaltyID
   )
) TYPE=InnoDB;

CREATE TABLE `opppc` (
   OppPermitCategoryID int unsigned auto_increment not null,
   OppPermitID int,
   PermitCategoryID int,
   PermitCategoryName varchar(128),
   PermitCategoryDesc varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppPermitCategoryID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opppc_l` (
   _RecordID int unsigned not null auto_increment,
   OppPermitCategoryID int unsigned  not null,
   OppPermitID int,
   PermitCategoryID int,
   PermitCategoryName varchar(128),
   PermitCategoryDesc varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppPermitCategoryID
   )
) TYPE=InnoDB;

CREATE TABLE `opppf` (
   PermitFeeID int unsigned auto_increment not null,
   OppPermitID int,
   PermitFeeTypeID int,
   Amount decimal(12,4),
   FeeExplan varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitFeeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opppf_l` (
   _RecordID int unsigned not null auto_increment,
   PermitFeeID int unsigned  not null,
   OppPermitID int,
   PermitFeeTypeID int,
   Amount decimal(12,4),
   FeeExplan varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitFeeID
   )
) TYPE=InnoDB;

CREATE TABLE `opprr` (
   PermitRptReqID int unsigned auto_increment not null,
   OppPermitID int,
   ReportReqID int,
   Requirement varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitRptReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opprr_l` (
   _RecordID int unsigned not null auto_increment,
   PermitRptReqID int unsigned  not null,
   OppPermitID int,
   ReportReqID int,
   Requirement varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitRptReqID
   )
) TYPE=InnoDB;

CREATE TABLE `oppsa` (
   PermitSystemID int unsigned auto_increment not null,
   OppPermitID int,
   SystemID int,
   PermitSystemEffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppsa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitSystemID int unsigned  not null,
   OppPermitID int,
   SystemID int,
   PermitSystemEffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitSystemID
   )
) TYPE=InnoDB;

CREATE TABLE `oppt` (
   PermitTypeID int unsigned auto_increment not null,
   PermitCategoryID int,
   PermitTypeName varchar(128),
   PermitTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppt_l` (
   _RecordID int unsigned not null auto_increment,
   PermitTypeID int unsigned  not null,
   PermitCategoryID int,
   PermitTypeName varchar(128),
   PermitTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `oppwa` (
   PermitWasteID int unsigned auto_increment not null,
   OppPermitID int,
   WasteID int,
   PermitWasteAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitWasteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppwa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitWasteID int unsigned  not null,
   OppPermitID int,
   WasteID int,
   PermitWasteAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitWasteID
   )
) TYPE=InnoDB;

CREATE TABLE `opt` (
   OpportunityEstimateID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OwnerOrganizationID int,
   OpportunityEstimateTitle varchar(128),
   OpportunityEstimateDescription text,
   Estimate decimal(12,4),
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OpportunityEstimateID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opt_l` (
   _RecordID int unsigned not null auto_increment,
   OpportunityEstimateID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OwnerOrganizationID int,
   OpportunityEstimateTitle varchar(128),
   OpportunityEstimateDescription text,
   Estimate decimal(12,4),
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OpportunityEstimateID
   )
) TYPE=InnoDB;

CREATE TABLE `optc` (
   OpportunityConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   Estimate decimal(12,4),
   OpportunityConsiderationTitle varchar(128),
   OpportunityConsiderationDescription text,
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OpportunityConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `optc_l` (
   _RecordID int unsigned not null auto_increment,
   OpportunityConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   Estimate decimal(12,4),
   OpportunityConsiderationTitle varchar(128),
   OpportunityConsiderationDescription text,
   FinancialBenefitFrequency float,
   FinancialBenefitFrequencyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OpportunityConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `org` (
   OrganizationID int unsigned auto_increment not null,
   ParentOrganizationID int unsigned null,
   DirectionOrganizationID int unsigned null,
   PolicyOrganizationID int unsigned null,
   Name varchar(128),
   ShortName varchar(20),
   Description text,
   IndustryCodeID int unsigned,
   SIC varchar(20),
   OrganizationTypeID int unsigned not null default 0,
   URL varchar(128),
   GuidanceLogoName varchar(50),
   Address1 text,
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(50),
   Phone varchar(50),
   Fax varchar(50),
   Email varchar(128),
   RegionID int unsigned,
   DivisionID int unsigned,
   Participant bool,
   Manufacturer bool,
   Contractor bool,
   InsuranceCarrier bool,
   InsuranceBroker bool,
   LawFirm bool,
   GovAgency bool,
   Public bool,
   Customer bool,
   AgencyLevelID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrganizationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `org_l` (
   _RecordID int unsigned not null auto_increment,
   OrganizationID int unsigned  not null,
   ParentOrganizationID int unsigned null,
   DirectionOrganizationID int unsigned null,
   PolicyOrganizationID int unsigned null,
   Name varchar(128),
   ShortName varchar(20),
   Description text,
   IndustryCodeID int unsigned,
   SIC varchar(20),
   OrganizationTypeID int unsigned not null default 0,
   URL varchar(128),
   GuidanceLogoName varchar(50),
   Address1 text,
   CountyID int unsigned,
   City varchar(50),
   PostalCode varchar(50),
   Phone varchar(50),
   Fax varchar(50),
   Email varchar(128),
   RegionID int unsigned,
   DivisionID int unsigned,
   Participant bool,
   Manufacturer bool,
   Contractor bool,
   InsuranceCarrier bool,
   InsuranceBroker bool,
   LawFirm bool,
   GovAgency bool,
   Public bool,
   Customer bool,
   AgencyLevelID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrganizationID
   )
) TYPE=InnoDB;

CREATE TABLE `orgca` (
   AdditionalInsuredID int unsigned auto_increment not null,
   CertificateID int unsigned not null,
   OrganizationID int unsigned,
   Circumstances text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AdditionalInsuredID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgca_l` (
   _RecordID int unsigned not null auto_increment,
   AdditionalInsuredID int unsigned  not null,
   CertificateID int unsigned not null,
   OrganizationID int unsigned,
   Circumstances text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AdditionalInsuredID
   )
) TYPE=InnoDB;

CREATE TABLE `orgci` (
   CertificateID int unsigned auto_increment not null,
   NamedOrgID int unsigned,
   CarrierID int unsigned,
   PolicyTypeID int unsigned,
   CertificateTitle varchar(128),
   CertificateNo varchar(128),
   CoverageAmount decimal(12,4),
   CertificateAttached bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertificateID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgci_l` (
   _RecordID int unsigned not null auto_increment,
   CertificateID int unsigned  not null,
   NamedOrgID int unsigned,
   CarrierID int unsigned,
   PolicyTypeID int unsigned,
   CertificateTitle varchar(128),
   CertificateNo varchar(128),
   CoverageAmount decimal(12,4),
   CertificateAttached bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertificateID
   )
) TYPE=InnoDB;

CREATE TABLE `orgda` (
   DepartmentAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DepartmentID int,
   ResponsibilityTitle varchar(128),
   Responsibilities text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DepartmentAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgda_l` (
   _RecordID int unsigned not null auto_increment,
   DepartmentAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DepartmentID int,
   ResponsibilityTitle varchar(128),
   Responsibilities text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DepartmentAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `orgdp` (
   DepartmentID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LevelID int unsigned,
   DepartmentName varchar(128) not null,
   DepartmentDesc text,
   DepartmentNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DepartmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgdp_l` (
   _RecordID int unsigned not null auto_increment,
   DepartmentID int unsigned  not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LevelID int unsigned,
   DepartmentName varchar(128) not null,
   DepartmentDesc text,
   DepartmentNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DepartmentID
   )
) TYPE=InnoDB;

CREATE TABLE `orgdv` (
   DivisionID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   DivisionName varchar(50),
   DivisionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DivisionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgdv_l` (
   _RecordID int unsigned not null auto_increment,
   DivisionID int unsigned  not null,
   OrganizationID int unsigned,
   DivisionName varchar(50),
   DivisionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DivisionID
   )
) TYPE=InnoDB;

CREATE TABLE `orgj` (
   JobID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   ContractingID int unsigned,
   JobNumber varchar(128),
   JobDesc text,
   JobStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgj_l` (
   _RecordID int unsigned not null auto_increment,
   JobID int unsigned  not null,
   OrganizationID int unsigned,
   ContractingID int unsigned,
   JobNumber varchar(128),
   JobDesc text,
   JobStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobID
   )
) TYPE=InnoDB;

CREATE TABLE `orgja` (
   JobTitleAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrgJobTitleID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgja_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrgJobTitleID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `orgjb` (
   JobResponsibilityID int unsigned auto_increment not null,
   OrgJobTitleID int unsigned not null,
   JobResponsibilityTypeID int unsigned,
   ResponsibilityTitle varchar(128),
   ResponsibilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobResponsibilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjb_l` (
   _RecordID int unsigned not null auto_increment,
   JobResponsibilityID int unsigned  not null,
   OrgJobTitleID int unsigned not null,
   JobResponsibilityTypeID int unsigned,
   ResponsibilityTitle varchar(128),
   ResponsibilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobResponsibilityID
   )
) TYPE=InnoDB;

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

CREATE TABLE `orgjr` (
   JobReqID int unsigned auto_increment not null,
   OrgJobTitleID int unsigned,
   JobRequirementID int unsigned,
   JobReqDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjr_l` (
   _RecordID int unsigned not null auto_increment,
   JobReqID int unsigned  not null,
   OrgJobTitleID int unsigned,
   JobRequirementID int unsigned,
   JobReqDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobReqID
   )
) TYPE=InnoDB;

CREATE TABLE `orgjt` (
   OrgJobTitleID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LocalLevelID int unsigned,
   JobTitleTypeID int unsigned not null,
   JobTitleName varchar(128),
   JobDescription text,
   JobAuthority text,
   JobTitleNumber varchar(20),
   JobTitleStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrgJobTitleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjt_l` (
   _RecordID int unsigned not null auto_increment,
   OrgJobTitleID int unsigned  not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LocalLevelID int unsigned,
   JobTitleTypeID int unsigned not null,
   JobTitleName varchar(128),
   JobDescription text,
   JobAuthority text,
   JobTitleNumber varchar(20),
   JobTitleStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrgJobTitleID
   )
) TYPE=InnoDB;

CREATE TABLE `orgl` (
   LocationID int unsigned auto_increment not null,
   OrganizationID int unsigned not null,
   LocationTypeID int unsigned not null,
   LocationName varchar(128),
   LocationDesc text,
   LocationNumber varchar(50),
   RoomID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgl_l` (
   _RecordID int unsigned not null auto_increment,
   LocationID int unsigned  not null,
   OrganizationID int unsigned not null,
   LocationTypeID int unsigned not null,
   LocationName varchar(128),
   LocationDesc text,
   LocationNumber varchar(50),
   RoomID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationID
   )
) TYPE=InnoDB;

CREATE TABLE `orgla` (
   LocationAssocID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LocationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationAssocID
   ),
   INDEX orgla_LocationID (
      LocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgla_l` (
   _RecordID int unsigned not null auto_increment,
   LocationAssocID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LocationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationAssocID
   ),
   INDEX orgla_l_LocationID (
      LocationID
   )
) TYPE=InnoDB;

CREATE TABLE `orglt` (
   LocationTypeID int unsigned auto_increment not null,
   LocationCategoryID int,
   LocationTypeTitle varchar(128),
   LocationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orglt_l` (
   _RecordID int unsigned not null auto_increment,
   LocationTypeID int unsigned  not null,
   LocationCategoryID int,
   LocationTypeTitle varchar(128),
   LocationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `orgr` (
   RequirementID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RequirementTitle varchar(128),
   RequirementPurpose text,
   RequirementDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RequirementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgr_l` (
   _RecordID int unsigned not null auto_increment,
   RequirementID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RequirementTitle varchar(128),
   RequirementPurpose text,
   RequirementDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RequirementID
   )
) TYPE=InnoDB;

CREATE TABLE `orgra` (
   RequirementAccID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   RequirementAccTitle varchar(128),
   RequirementAccDesc text,
   PertinentInfo text,
   RequirementAccStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RequirementAccID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgra_l` (
   _RecordID int unsigned not null auto_increment,
   RequirementAccID int unsigned  not null,
   OrganizationID int unsigned,
   RequirementAccTitle varchar(128),
   RequirementAccDesc text,
   PertinentInfo text,
   RequirementAccStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RequirementAccID
   )
) TYPE=InnoDB;

CREATE TABLE `orgrg` (
   RegionID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   RegionName varchar(50),
   RegionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgrg_l` (
   _RecordID int unsigned not null auto_increment,
   RegionID int unsigned  not null,
   OrganizationID int unsigned,
   RegionName varchar(50),
   RegionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegionID
   )
) TYPE=InnoDB;

CREATE TABLE `orgt` (
   OrganizationTypeID int unsigned auto_increment not null,
   OrganizationCategoryID int,
   OrganizationTypeTitle varchar(128),
   OrganizationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrganizationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgt_l` (
   _RecordID int unsigned not null auto_increment,
   OrganizationTypeID int unsigned  not null,
   OrganizationCategoryID int,
   OrganizationTypeTitle varchar(128),
   OrganizationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrganizationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `orgtc` (
   JobTitleConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   JobTitleConsiderationTitle varchar(128),
   JobTitleConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgtc_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   JobTitleConsiderationTitle varchar(128),
   JobTitleConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `orgtt` (
   JobTitleTypeID int unsigned auto_increment not null,
   JobTitleCategoryID int,
   JobTitleTypeTitle varchar(128),
   JobTitleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgtt_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleTypeID int unsigned  not null,
   JobTitleCategoryID int,
   JobTitleTypeTitle varchar(128),
   JobTitleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `orgwa` (
   WorkAvailabilityID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   WorkAvailabilityGroupID int,
   WorkUnavailabilityTypeID int unsigned not null,
   Explanation text,
   Days int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkAvailabilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgwa_l` (
   _RecordID int unsigned not null auto_increment,
   WorkAvailabilityID int unsigned  not null,
   OrganizationID int unsigned,
   WorkAvailabilityGroupID int,
   WorkUnavailabilityTypeID int unsigned not null,
   Explanation text,
   Days int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkAvailabilityID
   )
) TYPE=InnoDB;

CREATE TABLE `orgwu` (
   WorkUnavailabilityTypeID int unsigned auto_increment not null,
   UnavailabilityCategoryID int,
   UnavailabilityType varchar(128),
   UnavailabilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkUnavailabilityTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgwu_l` (
   _RecordID int unsigned not null auto_increment,
   WorkUnavailabilityTypeID int unsigned  not null,
   UnavailabilityCategoryID int,
   UnavailabilityType varchar(128),
   UnavailabilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkUnavailabilityTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `par` (
   PartnershipID int unsigned auto_increment not null,
   OrganizationID int,
   PartnershipTitle varchar(128),
   PartnershipNumber varchar(25),
   PartnershipPolicyID int unsigned not null,
   PartnershipPurpose text,
   PartnershipScope text,
   CommitmentStatement text,
   PartnershipStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `par_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipID int unsigned  not null,
   OrganizationID int,
   PartnershipTitle varchar(128),
   PartnershipNumber varchar(25),
   PartnershipPolicyID int unsigned not null,
   PartnershipPurpose text,
   PartnershipScope text,
   CommitmentStatement text,
   PartnershipStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipID
   )
) TYPE=InnoDB;

CREATE TABLE `para` (
   PartnershipAuditID int unsigned auto_increment not null,
   PartnershipID int unsigned not null,
   AuditingOrganizationID int,
   AuditScope text,
   AuditPurpose text,
   ImprovementOpportunity text,
   SharingOpportunity text,
   SharedExpectationsGoal float,
   LocalExpectationsGoal float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipAuditID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `para_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipAuditID int unsigned  not null,
   PartnershipID int unsigned not null,
   AuditingOrganizationID int,
   AuditScope text,
   AuditPurpose text,
   ImprovementOpportunity text,
   SharingOpportunity text,
   SharedExpectationsGoal float,
   LocalExpectationsGoal float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipAuditID
   )
) TYPE=InnoDB;

CREATE TABLE `parle` (
   LocalPartnerExpectationID int unsigned auto_increment not null,
   PartnershipID int unsigned not null,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocalPartnerExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parle_l` (
   _RecordID int unsigned not null auto_increment,
   LocalPartnerExpectationID int unsigned  not null,
   PartnershipID int unsigned not null,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocalPartnerExpectationID
   )
) TYPE=InnoDB;

CREATE TABLE `parls` (
   LocalExpectationScoreID int unsigned auto_increment not null,
   PartnershipAuditID int unsigned not null,
   LocalPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LocalExpectationScoreID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parls_l` (
   _RecordID int unsigned not null auto_increment,
   LocalExpectationScoreID int unsigned  not null,
   PartnershipAuditID int unsigned not null,
   LocalPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LocalExpectationScoreID
   )
) TYPE=InnoDB;

CREATE TABLE `parp` (
   PartnershipPolicyID int unsigned auto_increment not null,
   PolicyOrganizationID int,
   PolicyTitleID int,
   PolicyPurpose text,
   PolicyScope text,
   PolicyNumber varchar(25),
   CommitmentStatement text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipPolicyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parp_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipPolicyID int unsigned  not null,
   PolicyOrganizationID int,
   PolicyTitleID int,
   PolicyPurpose text,
   PolicyScope text,
   PolicyNumber varchar(25),
   CommitmentStatement text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipPolicyID
   )
) TYPE=InnoDB;

CREATE TABLE `parpa` (
   PartnershipAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PartnershipID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PartnershipAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parpa_l` (
   _RecordID int unsigned not null auto_increment,
   PartnershipAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   PartnershipID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PartnershipAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `parpe` (
   PolicyExpectationID int unsigned auto_increment not null,
   PartnershipPolicyID int,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PolicyExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parpe_l` (
   _RecordID int unsigned not null auto_increment,
   PolicyExpectationID int unsigned  not null,
   PartnershipPolicyID int,
   ExpectationGroupID int,
   Expectation text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PolicyExpectationID
   )
) TYPE=InnoDB;

CREATE TABLE `parse` (
   SharedPartnerExpectationID int unsigned auto_increment not null,
   PolicyExpectationID int unsigned not null,
   PartnershipPolicyID int,
   PartnershipID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedPartnerExpectationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parse_l` (
   _RecordID int unsigned not null auto_increment,
   SharedPartnerExpectationID int unsigned  not null,
   PolicyExpectationID int unsigned not null,
   PartnershipPolicyID int,
   PartnershipID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedPartnerExpectationID
   )
) TYPE=InnoDB;

CREATE TABLE `parss` (
   SharedExpectationScoreID int unsigned auto_increment not null,
   SharedPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   PartnershipAuditID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedExpectationScoreID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `parss_l` (
   _RecordID int unsigned not null auto_increment,
   SharedExpectationScoreID int unsigned  not null,
   SharedPartnerExpectationID int unsigned not null,
   Findings text,
   Score float,
   PartnershipAuditID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedExpectationScoreID
   )
) TYPE=InnoDB;

CREATE TABLE `pkg` (
   PackagingUnitEquivalentID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PackagingUnitEquivalentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pkg_l` (
   _RecordID int unsigned not null auto_increment,
   PackagingUnitEquivalentID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PackagingUnitEquivalentID
   )
) TYPE=InnoDB;

CREATE TABLE `pos` (
   PostingID int unsigned auto_increment not null,
   OrganizationID int,
   PostingTypeID int,
   PostingName varchar(255),
   CurrentVersion varchar(128),
   PostingDesc text,
   PostingURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PostingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pos_l` (
   _RecordID int unsigned not null auto_increment,
   PostingID int unsigned  not null,
   OrganizationID int,
   PostingTypeID int,
   PostingName varchar(255),
   CurrentVersion varchar(128),
   PostingDesc text,
   PostingURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PostingID
   )
) TYPE=InnoDB;

CREATE TABLE `posa` (
   PostingAssignmentID int unsigned auto_increment not null,
   PostingID int,
   PostingMethodDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PostingAssignmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `posa_l` (
   _RecordID int unsigned not null auto_increment,
   PostingAssignmentID int unsigned  not null,
   PostingID int,
   PostingMethodDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PostingAssignmentID
   )
) TYPE=InnoDB;

CREATE TABLE `ppl` (
   PersonID int unsigned auto_increment not null,
   OrganizationID int unsigned not null,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   DisplayName varchar(50),
   MobilePhone varchar(50),
   MobilePhoneAddress varchar(50),
   PagerNumber varchar(50),
   PagerPIN varchar(50),
   SkypeName varchar(50),
   WorkAddress1 varchar(128),
   WorkAddress2 varchar(128),
   WorkCountyID int unsigned,
   WorkCity varchar(50),
   WorkPostalCode varchar(50),
   WorkPhone varchar(50),
   WorkExtension varchar(50),
   WorkFax varchar(50),
   WorkEmail varchar(128),
   HomeAddress1 varchar(128),
   HomeAddress2 varchar(128),
   HomeCountyID int unsigned,
   HomeCity varchar(50),
   HomePostalCode varchar(50),
   HomePhone varchar(50),
   HomeFax varchar(50),
   HomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PersonID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppl_l` (
   _RecordID int unsigned not null auto_increment,
   PersonID int unsigned  not null,
   OrganizationID int unsigned not null,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   DisplayName varchar(50),
   MobilePhone varchar(50),
   MobilePhoneAddress varchar(50),
   PagerNumber varchar(50),
   PagerPIN varchar(50),
   SkypeName varchar(50),
   WorkAddress1 varchar(128),
   WorkAddress2 varchar(128),
   WorkCountyID int unsigned,
   WorkCity varchar(50),
   WorkPostalCode varchar(50),
   WorkPhone varchar(50),
   WorkExtension varchar(50),
   WorkFax varchar(50),
   WorkEmail varchar(128),
   HomeAddress1 varchar(128),
   HomeAddress2 varchar(128),
   HomeCountyID int unsigned,
   HomeCity varchar(50),
   HomePostalCode varchar(50),
   HomePhone varchar(50),
   HomeFax varchar(50),
   HomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PersonID
   )
) TYPE=InnoDB;

CREATE TABLE `ppla` (
   ChangeID int unsigned auto_increment not null,
   ChangeDate date,
   PersonID int unsigned,
   OldHomeAddress1 varchar(128),
   OldHomeAddress2 varchar(128),
   OldHomeCountyID int unsigned,
   OldHomeCity varchar(50),
   OldHomePostalCode varchar(50),
   OldHomePhone varchar(50),
   OldHomeFax varchar(50),
   OldHomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChangeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppla_l` (
   _RecordID int unsigned not null auto_increment,
   ChangeID int unsigned  not null,
   ChangeDate date,
   PersonID int unsigned,
   OldHomeAddress1 varchar(128),
   OldHomeAddress2 varchar(128),
   OldHomeCountyID int unsigned,
   OldHomeCity varchar(50),
   OldHomePostalCode varchar(50),
   OldHomePhone varchar(50),
   OldHomeFax varchar(50),
   OldHomeEmail varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChangeID
   )
) TYPE=InnoDB;

CREATE TABLE `pplam` (
   AvailabilityModificationID int unsigned auto_increment not null,
   EmployeePersonID int unsigned,
   AvailabilityModificationTypeID int unsigned not null,
   Explanation text,
   DaysNotScheduled float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AvailabilityModificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplam_l` (
   _RecordID int unsigned not null auto_increment,
   AvailabilityModificationID int unsigned  not null,
   EmployeePersonID int unsigned,
   AvailabilityModificationTypeID int unsigned not null,
   Explanation text,
   DaysNotScheduled float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AvailabilityModificationID
   )
) TYPE=InnoDB;

CREATE TABLE `pplat` (
   AvailabilityModificationTypeID int unsigned auto_increment not null,
   ModificationCategoryID int,
   ModificationType varchar(128),
   ModificationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AvailabilityModificationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplat_l` (
   _RecordID int unsigned not null auto_increment,
   AvailabilityModificationTypeID int unsigned  not null,
   ModificationCategoryID int,
   ModificationType varchar(128),
   ModificationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AvailabilityModificationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `ppld` (
   DependentID int unsigned auto_increment not null,
   EmployeePersonID int unsigned,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   RelationshipID int unsigned,
   DepGenderID int unsigned,
   DepBirthdate date,
   DepAge varchar(5),
   DepSocialSecNo varchar(25),
   Employed bool,
   SpecialFactors varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DependentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppld_l` (
   _RecordID int unsigned not null auto_increment,
   DependentID int unsigned  not null,
   EmployeePersonID int unsigned,
   LastName varchar(50),
   FirstName varchar(50),
   MiddleName varchar(50),
   RelationshipID int unsigned,
   DepGenderID int unsigned,
   DepBirthdate date,
   DepAge varchar(5),
   DepSocialSecNo varchar(25),
   Employed bool,
   SpecialFactors varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DependentID
   )
) TYPE=InnoDB;

CREATE TABLE `pple` (
   PersonID int unsigned not null,
   MaritalStatusID int unsigned,
   EmployeeNo varchar(50),
   SocialSecNo varchar(25),
   DriversLicenseNo varchar(25),
   DriversLicenseED varchar(25),
   DriversLicenseSt varchar(25),
   OpsCoVeh bool,
   OpsCommVeh bool,
   MVASigned bool,
   IdentificationNo varchar(128),
   GenderID int unsigned,
   RaceID int unsigned,
   TerminationTypeID int unsigned,
   Active bool,
   EmploymentStatusID int unsigned,
   WageStatusID int unsigned,
   ShiftID int unsigned,
   HourlyWage decimal(12,4),
   Salary decimal(12,4),
   DivisionID int unsigned,
   RegionID int unsigned,
   WorkAvailabilityGroupID int,
   LaborUtilizationRate float,
   I9DocCompleted bool,
   I9ListDocID int unsigned,
   I9ListAID int unsigned,
   I9ListBID int unsigned,
   I9ListCID int unsigned,
   BusinessContinuity bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PersonID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pple_l` (
   _RecordID int unsigned not null auto_increment,
   PersonID int unsigned not null,
   MaritalStatusID int unsigned,
   EmployeeNo varchar(50),
   SocialSecNo varchar(25),
   DriversLicenseNo varchar(25),
   DriversLicenseED varchar(25),
   DriversLicenseSt varchar(25),
   OpsCoVeh bool,
   OpsCommVeh bool,
   MVASigned bool,
   IdentificationNo varchar(128),
   GenderID int unsigned,
   RaceID int unsigned,
   TerminationTypeID int unsigned,
   Active bool,
   EmploymentStatusID int unsigned,
   WageStatusID int unsigned,
   ShiftID int unsigned,
   HourlyWage decimal(12,4),
   Salary decimal(12,4),
   DivisionID int unsigned,
   RegionID int unsigned,
   WorkAvailabilityGroupID int,
   LaborUtilizationRate float,
   I9DocCompleted bool,
   I9ListDocID int unsigned,
   I9ListAID int unsigned,
   I9ListBID int unsigned,
   I9ListCID int unsigned,
   BusinessContinuity bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PersonID
   )
) TYPE=InnoDB;

CREATE TABLE `pplek` (
   EmployeeKSAID int unsigned auto_increment not null,
   PersonID int unsigned,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EmployeeKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplek_l` (
   _RecordID int unsigned not null auto_increment,
   EmployeeKSAID int unsigned  not null,
   PersonID int unsigned,
   KSAID int unsigned not null,
   LevelID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EmployeeKSAID
   )
) TYPE=InnoDB;

CREATE TABLE `pplem` (
   EmergContactID int unsigned auto_increment not null,
   _ModDate datetime not null,
   EmployeePersonID int unsigned,
   ContactLastName varchar(50),
   ContactFirstName varchar(50),
   ContactMiddleName varchar(50),
   RelationshipID int unsigned,
   ContactAddress1 varchar(128),
   ContactAddress2 varchar(128),
   ContactStateID int,
   ContactCity varchar(50),
   ContactPostalCode varchar(50),
   ContactPhone varchar(50),
   ContactAltPhone varchar(50),
   ContactFax varchar(50),
   ContactEmail varchar(128),
   ContactPriority int,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EmergContactID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplem_l` (
   _RecordID int unsigned not null auto_increment,
   EmergContactID int unsigned  not null,
   _ModDate datetime not null,
   EmployeePersonID int unsigned,
   ContactLastName varchar(50),
   ContactFirstName varchar(50),
   ContactMiddleName varchar(50),
   RelationshipID int unsigned,
   ContactAddress1 varchar(128),
   ContactAddress2 varchar(128),
   ContactStateID int,
   ContactCity varchar(50),
   ContactPostalCode varchar(50),
   ContactPhone varchar(50),
   ContactAltPhone varchar(50),
   ContactFax varchar(50),
   ContactEmail varchar(128),
   ContactPriority int,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EmergContactID
   )
) TYPE=InnoDB;

CREATE TABLE `pplep` (
   PriorEmployerID int unsigned auto_increment not null,
   PersonID int unsigned,
   EmployerName varchar(128),
   JobTitle varchar(128),
   EmploymentDescription text,
   YearsofService float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PriorEmployerID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pplep_l` (
   _RecordID int unsigned not null auto_increment,
   PriorEmployerID int unsigned  not null,
   PersonID int unsigned,
   EmployerName varchar(128),
   JobTitle varchar(128),
   EmploymentDescription text,
   YearsofService float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PriorEmployerID
   )
) TYPE=InnoDB;

CREATE TABLE `ppleq` (
   QualificationID int unsigned auto_increment not null,
   PersonID int unsigned,
   InstitutionName varchar(128),
   LearningLevelID int unsigned,
   DegreeEarnedID int unsigned,
   CredentialName varchar(128),
   YearsAttended float,
   SpecialSkills varchar(128),
   QualificationStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QualificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppleq_l` (
   _RecordID int unsigned not null auto_increment,
   QualificationID int unsigned  not null,
   PersonID int unsigned,
   InstitutionName varchar(128),
   LearningLevelID int unsigned,
   DegreeEarnedID int unsigned,
   CredentialName varchar(128),
   YearsAttended float,
   SpecialSkills varchar(128),
   QualificationStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QualificationID
   )
) TYPE=InnoDB;

CREATE TABLE `ppljt` (
   PeopleJobTitleID int unsigned auto_increment not null,
   PersonID int unsigned,
   OrgJobTitleID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PeopleJobTitleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ppljt_l` (
   _RecordID int unsigned not null auto_increment,
   PeopleJobTitleID int unsigned  not null,
   PersonID int unsigned,
   OrgJobTitleID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PeopleJobTitleID
   )
) TYPE=InnoDB;

CREATE TABLE `prta` (
   ParticipantAccID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AccountabilityTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipantAccID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `prta_l` (
   _RecordID int unsigned not null auto_increment,
   ParticipantAccID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AccountabilityTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ParticipantAccID
   )
) TYPE=InnoDB;

CREATE TABLE `prti` (
   ParticipantInvID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   InvolvementTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipantInvID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `prti_l` (
   _RecordID int unsigned not null auto_increment,
   ParticipantInvID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   InvolvementTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ParticipantInvID
   )
) TYPE=InnoDB;

CREATE TABLE `prtt` (
   ParticipationTypeID int auto_increment not null,
   ParticipationPurposeID int,
   ParticipationTitle varchar(128),
   ParticipationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ParticipationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `prtt_l` (
   _RecordID int unsigned not null auto_increment,
   ParticipationTypeID int  not null,
   ParticipationPurposeID int,
   ParticipationTitle varchar(128),
   ParticipationTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ParticipationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `pub` (
   PublicityExposureID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   ExposureTitle varchar(128),
   ExposureDescription text,
   PublicityTypeID int unsigned not null,
   PublicityScopeID int,
   ExposureCriticalityID int,
   ExposureStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityExposureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pub_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityExposureID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   ExposureTitle varchar(128),
   ExposureDescription text,
   PublicityTypeID int unsigned not null,
   PublicityScopeID int,
   ExposureCriticalityID int,
   ExposureStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityExposureID
   )
) TYPE=InnoDB;

CREATE TABLE `pubc` (
   PublicityConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   PublicityConsiderationTitle varchar(128),
   PublicityConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pubc_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   PublicityConsiderationTitle varchar(128),
   PublicityConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `pubt` (
   PublicityTypeID int unsigned auto_increment not null,
   PublicityExposureCategoryID int,
   PublicityExposureType varchar(128),
   PublicityExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PublicityTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pubt_l` (
   _RecordID int unsigned not null auto_increment,
   PublicityTypeID int unsigned  not null,
   PublicityExposureCategoryID int,
   PublicityExposureType varchar(128),
   PublicityExposureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PublicityTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `rdc` (
   ModuleID varchar(5) not null,
   RecordID int not null,
   OrganizationID int,
   Value text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleID,
      RecordID
   ),
   INDEX rdc_RecordIDix (
      RecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rdc_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   RecordID int not null,
   OrganizationID int,
   Value text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleID,
      RecordID
   ),
   INDEX rdc_l_RecordIDix (
      RecordID
   )
) TYPE=InnoDB;

CREATE TABLE `reg` (
   RegulationID int unsigned auto_increment not null,
   OrganizationID int,
   CountryID int unsigned not null,
   AgencyLevelID int,
   AgencyID int,
   RegTitle varchar(128),
   RegCode varchar(128),
   RegSection varchar(128),
   RegScope text,
   RegDescription text,
   RegURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `reg_l` (
   _RecordID int unsigned not null auto_increment,
   RegulationID int unsigned  not null,
   OrganizationID int,
   CountryID int unsigned not null,
   AgencyLevelID int,
   AgencyID int,
   RegTitle varchar(128),
   RegCode varchar(128),
   RegSection varchar(128),
   RegScope text,
   RegDescription text,
   RegURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulationID
   )
) TYPE=InnoDB;

CREATE TABLE `rega` (
   RegulationAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RegulationID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulationAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rega_l` (
   _RecordID int unsigned not null auto_increment,
   RegulationAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RegulationID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulationAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `regc` (
   RegulatoryConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RegulationID int,
   RegulatoryConsiderationTitle varchar(128),
   RegulatoryConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RegulatoryConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `regc_l` (
   _RecordID int unsigned not null auto_increment,
   RegulatoryConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RegulationID int,
   RegulatoryConsiderationTitle varchar(128),
   RegulatoryConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RegulatoryConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `res` (
   ResourceID int not null auto_increment,
   ResourceTypeID int,
   ResourceDesc text,
   ResourceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `res_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceID int not null ,
   ResourceTypeID int,
   ResourceDesc text,
   ResourceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceID
   )
) TYPE=InnoDB;

CREATE TABLE `resm` (
   ModuleResourceID int not null auto_increment,
   ModuleDependencyID int,
   ModuleID varchar(5),
   ResourceID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleResourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `resm_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleResourceID int not null ,
   ModuleDependencyID int,
   ModuleID varchar(5),
   ResourceID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleResourceID
   )
) TYPE=InnoDB;

CREATE TABLE `reso` (
   ResourceAssignID int unsigned auto_increment not null,
   ResourceID int unsigned not null,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceAssignID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `reso_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceAssignID int unsigned  not null,
   ResourceID int unsigned not null,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceAssignID
   )
) TYPE=InnoDB;

CREATE TABLE `rest` (
   ResourceTypeID int auto_increment not null,
   ResourcePurposeID int,
   ResourceTitle varchar(128),
   ResourceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rest_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceTypeID int  not null,
   ResourcePurposeID int,
   ResourceTitle varchar(128),
   ResourceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `rsk` (
   ImperativeID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskImperativeID int,
   RiskDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ImperativeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsk_l` (
   _RecordID int unsigned not null auto_increment,
   ImperativeID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskImperativeID int,
   RiskDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ImperativeID
   )
) TYPE=InnoDB;

CREATE TABLE `rskc` (
   ImperativeConsidID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RiskImperativeID int,
   ImperativeConsidTitle varchar(128),
   ImperativeConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ImperativeConsidID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskc_l` (
   _RecordID int unsigned not null auto_increment,
   ImperativeConsidID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RiskImperativeID int,
   ImperativeConsidTitle varchar(128),
   ImperativeConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ImperativeConsidID
   )
) TYPE=InnoDB;

CREATE TABLE `rskcl` (
   RiskClassID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskClassificationID int,
   RiskClassDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RiskClassID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskcl_l` (
   _RecordID int unsigned not null auto_increment,
   RiskClassID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskClassificationID int,
   RiskClassDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RiskClassID
   )
) TYPE=InnoDB;

CREATE TABLE `rskl` (
   LikelihoodID int unsigned auto_increment not null,
   RiskLikelihoodTerm varchar(50),
   RiskLikelihood varchar(128),
   LikelihoodValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LikelihoodID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskl_l` (
   _RecordID int unsigned not null auto_increment,
   LikelihoodID int unsigned  not null,
   RiskLikelihoodTerm varchar(50),
   RiskLikelihood varchar(128),
   LikelihoodValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LikelihoodID
   )
) TYPE=InnoDB;

CREATE TABLE `rsks` (
   SeverityID int unsigned auto_increment not null,
   RiskSeverityTerm varchar(50),
   RiskSeverity varchar(128),
   SeverityLowCost decimal(12,4),
   SeverityHighCost decimal(12,4),
   SeverityValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SeverityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsks_l` (
   _RecordID int unsigned not null auto_increment,
   SeverityID int unsigned  not null,
   RiskSeverityTerm varchar(50),
   RiskSeverity varchar(128),
   SeverityLowCost decimal(12,4),
   SeverityHighCost decimal(12,4),
   SeverityValue int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SeverityID
   )
) TYPE=InnoDB;

CREATE TABLE `rskx` (
   RiskIndexID int unsigned auto_increment not null,
   IndexValue int,
   RiskRecommendation varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RiskIndexID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskx_l` (
   _RecordID int unsigned not null auto_increment,
   RiskIndexID int unsigned  not null,
   IndexValue int,
   RiskRecommendation varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RiskIndexID
   )
) TYPE=InnoDB;

CREATE TABLE `rskxa` (
   IndexAssociationID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LikelihoodID int,
   LikelihoodFactors text,
   LikelihoodStatusID int,
   SeverityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndexAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskxa_l` (
   _RecordID int unsigned not null auto_increment,
   IndexAssociationID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LikelihoodID int,
   LikelihoodFactors text,
   LikelihoodStatusID int,
   SeverityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndexAssociationID
   )
) TYPE=InnoDB;

CREATE TABLE `rsp` (
   ResponsibilityID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   OrgLevelID int,
   ResponsibilityTitle varchar(128),
   OrgResponsibility text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResponsibilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsp_l` (
   _RecordID int unsigned not null auto_increment,
   ResponsibilityID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   OrgLevelID int,
   ResponsibilityTitle varchar(128),
   OrgResponsibility text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResponsibilityID
   )
) TYPE=InnoDB;

CREATE TABLE `rspc` (
   ResponsibilityConsidID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   OrgLevelID int,
   ResponsibilityConsidTitle varchar(128),
   ResponsibilityConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResponsibilityConsidID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rspc_l` (
   _RecordID int unsigned not null auto_increment,
   ResponsibilityConsidID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   OrgLevelID int,
   ResponsibilityConsidTitle varchar(128),
   ResponsibilityConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResponsibilityConsidID
   )
) TYPE=InnoDB;

CREATE TABLE `rtc` (
   CauseID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CausationTypeID int unsigned not null,
   RootCauseTitle varchar(128),
   RootCauseDesc text,
   RootCauseMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CauseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtc_l` (
   _RecordID int unsigned not null auto_increment,
   CauseID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CausationTypeID int unsigned not null,
   RootCauseTitle varchar(128),
   RootCauseDesc text,
   RootCauseMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CauseID
   )
) TYPE=InnoDB;

CREATE TABLE `rtcc` (
   RootCauseConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RootCauseConsiderationTitle varchar(128),
   RootCauseConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RootCauseConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtcc_l` (
   _RecordID int unsigned not null auto_increment,
   RootCauseConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RootCauseConsiderationTitle varchar(128),
   RootCauseConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RootCauseConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `rtct` (
   CausationTypeID int unsigned auto_increment not null,
   CauseCategoryID int,
   CauseType varchar(128),
   CauseTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CausationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtct_l` (
   _RecordID int unsigned not null auto_increment,
   CausationTypeID int unsigned  not null,
   CauseCategoryID int,
   CauseType varchar(128),
   CauseTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CausationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `rtp` (
   RecommendationID int unsigned auto_increment not null,
   CauseID int,
   RecToPreventID int,
   RecToPreventTitle varchar(128),
   RecToPreventDesc text,
   RecommendationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecommendationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtp_l` (
   _RecordID int unsigned not null auto_increment,
   RecommendationID int unsigned  not null,
   CauseID int,
   RecToPreventID int,
   RecToPreventTitle varchar(128),
   RecToPreventDesc text,
   RecommendationStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecommendationID
   )
) TYPE=InnoDB;

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

CREATE TABLE `sit` (
   SituationID int unsigned auto_increment not null,
   SituationTypeID int,
   LocalDescription text,
   OrganizationID int,
   DepartmentID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sit_l` (
   _RecordID int unsigned not null auto_increment,
   SituationID int unsigned  not null,
   SituationTypeID int,
   LocalDescription text,
   OrganizationID int,
   DepartmentID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationID
   )
) TYPE=InnoDB;

CREATE TABLE `sitc` (
   SituationContactID int unsigned auto_increment not null,
   SituationID int,
   ContactImmediacy float,
   ContactImmediacyUnitsID int,
   SeverityID int,
   SpecialFactors text,
   LastUpdate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationContactID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitc_l` (
   _RecordID int unsigned not null auto_increment,
   SituationContactID int unsigned  not null,
   SituationID int,
   ContactImmediacy float,
   ContactImmediacyUnitsID int,
   SeverityID int,
   SpecialFactors text,
   LastUpdate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationContactID
   )
) TYPE=InnoDB;

CREATE TABLE `sitd` (
   SituationDrillID int unsigned auto_increment not null,
   SituationID int unsigned not null,
   DrillTitle varchar(128),
   EndTime datetime,
   DrillScope varchar(255),
   DrillObjective text,
   DrillPlanning text,
   KeyLearning text,
   DrillStatusID int default 2,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationDrillID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitd_l` (
   _RecordID int unsigned not null auto_increment,
   SituationDrillID int unsigned  not null,
   SituationID int unsigned not null,
   DrillTitle varchar(128),
   EndTime datetime,
   DrillScope varchar(255),
   DrillObjective text,
   DrillPlanning text,
   KeyLearning text,
   DrillStatusID int default 2,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationDrillID
   )
) TYPE=InnoDB;

CREATE TABLE `sitdp` (
   DrillParticipantID int unsigned auto_increment not null,
   SituationDrillID int unsigned not null,
   ParticipantDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DrillParticipantID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitdp_l` (
   _RecordID int unsigned not null auto_increment,
   DrillParticipantID int unsigned  not null,
   SituationDrillID int unsigned not null,
   ParticipantDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DrillParticipantID
   )
) TYPE=InnoDB;

CREATE TABLE `sitrp` (
   SituationResponseID int unsigned auto_increment not null,
   SituationID int,
   ResourceTypeID int,
   LocalRoleDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationResponseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitrp_l` (
   _RecordID int unsigned not null auto_increment,
   SituationResponseID int unsigned  not null,
   SituationID int,
   ResourceTypeID int,
   LocalRoleDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationResponseID
   )
) TYPE=InnoDB;

CREATE TABLE `sitrs` (
   SituationResponseStepID int unsigned auto_increment not null,
   SituationResponseID int,
   ResponseStepOrder float,
   ResponseStep text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationResponseStepID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitrs_l` (
   _RecordID int unsigned not null auto_increment,
   SituationResponseStepID int unsigned  not null,
   SituationResponseID int,
   ResponseStepOrder float,
   ResponseStep text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationResponseStepID
   )
) TYPE=InnoDB;

CREATE TABLE `sitt` (
   SituationTypeID int unsigned auto_increment not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sitt_l` (
   _RecordID int unsigned not null auto_increment,
   SituationTypeID int unsigned  not null,
   SituationCategoryID int,
   SituationTitle varchar(128),
   SituationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `smc` (
   CacheID int unsigned auto_increment not null,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SubModuleID varchar(5) not null,
   SubRecordID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CacheID
   ),
   INDEX smc_Parent (
      ModuleID,
      RecordID
   ),
   INDEX smc_Sub (
      SubModuleID,
      SubRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `smc_l` (
   _RecordID int unsigned not null auto_increment,
   CacheID int unsigned  not null,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SubModuleID varchar(5) not null,
   SubRecordID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CacheID
   ),
   INDEX smc_l_Parent (
      ModuleID,
      RecordID
   ),
   INDEX smc_l_Sub (
      SubModuleID,
      SubRecordID
   )
) TYPE=InnoDB;

CREATE TABLE `spt` (
   SupportDocumentID int unsigned auto_increment not null,
   ModuleID varchar(5),
   LocalDocumentationStatusID int,
   WikiArticle varchar(128),
   WikiArticleStatusID int,
   WikiGuide varchar(128),
   WikiGuideStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupportDocumentID
   ),
   INDEX spt_ModuleID (
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `spt_l` (
   _RecordID int unsigned not null auto_increment,
   SupportDocumentID int unsigned  not null,
   ModuleID varchar(5),
   LocalDocumentationStatusID int,
   WikiArticle varchar(128),
   WikiArticleStatusID int,
   WikiGuide varchar(128),
   WikiGuideStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupportDocumentID
   ),
   INDEX spt_l_ModuleID (
      ModuleID
   )
) TYPE=InnoDB;

CREATE TABLE `spts` (
   SupportDocumentSectionID int unsigned auto_increment not null,
   SupportDocumentID int unsigned not null,
   Title varchar(50) not null,
   SectionText text,
   SortOrder int,
   Protected bool,
   Display bool,
   SectionID varchar(25),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupportDocumentSectionID
   ),
   INDEX spts_Doc_SectionID (
      SupportDocumentID,
      SectionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `spts_l` (
   _RecordID int unsigned not null auto_increment,
   SupportDocumentSectionID int unsigned  not null,
   SupportDocumentID int unsigned not null,
   Title varchar(50) not null,
   SectionText text,
   SortOrder int,
   Protected bool,
   Display bool,
   SectionID varchar(25),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupportDocumentSectionID
   ),
   INDEX spts_l_Doc_SectionID (
      SupportDocumentID,
      SectionID
   )
) TYPE=InnoDB;

CREATE TABLE `src` (
   ModuleID varchar(5) not null,
   UserID int not null,
   Expression text not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      UserID,
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `src_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   UserID int not null,
   Expression text not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      UserID,
      ModuleID
   )
) TYPE=InnoDB;

CREATE TABLE `sta` (
   StateID int unsigned auto_increment not null,
   CountryID int unsigned,
   StateName varchar(50),
   StateAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      StateID
   ),
   INDEX sta_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sta_l` (
   _RecordID int unsigned not null auto_increment,
   StateID int unsigned  not null,
   CountryID int unsigned,
   StateName varchar(50),
   StateAbbreviation varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      StateID
   ),
   INDEX sta_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

CREATE TABLE `std` (
   StandardID int unsigned auto_increment not null,
   OrganizationID int,
   CountryID int unsigned not null,
   StandardLevelID int,
   StandardsOrganizationID int,
   StandardsOrganizationAbbreviation varchar(25),
   StandardTitle varchar(128),
   StandardCode varchar(128),
   StandardSection varchar(128),
   StandardDescription text,
   StandardURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `std_l` (
   _RecordID int unsigned not null auto_increment,
   StandardID int unsigned  not null,
   OrganizationID int,
   CountryID int unsigned not null,
   StandardLevelID int,
   StandardsOrganizationID int,
   StandardsOrganizationAbbreviation varchar(25),
   StandardTitle varchar(128),
   StandardCode varchar(128),
   StandardSection varchar(128),
   StandardDescription text,
   StandardURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardID
   )
) TYPE=InnoDB;

CREATE TABLE `stda` (
   StandardAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   StandardID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `stda_l` (
   _RecordID int unsigned not null auto_increment,
   StandardAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   StandardID int,
   MannerAffected text,
   ComplianceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `stdc` (
   StandardsConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   StandardID int,
   StandardsConsiderationTitle varchar(128),
   StandardsConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      StandardsConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `stdc_l` (
   _RecordID int unsigned not null auto_increment,
   StandardsConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   StandardID int,
   StandardsConsiderationTitle varchar(128),
   StandardsConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      StandardsConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `sup` (
   SupplierID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SupplierTypeID int unsigned not null,
   SupplierOrgID int,
   PrimarySupplier bool default 0,
   SupplierTitle varchar(128),
   ItemDescription text,
   RelationshipID int,
   SupplierRelationship bool default 0,
   ProductLiabilityCertificate bool default 0,
   LiabilityExpiration date,
   OrganizationID int,
   CatalogID int,
   CatalogPgNo varchar(10),
   PartNo varchar(30),
   ItemURL varchar(128),
   UnitCost decimal(12,4),
   BaseUnitID int,
   MinimumUnitOrder float,
   MinimumOrderUoMID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sup_l` (
   _RecordID int unsigned not null auto_increment,
   SupplierID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SupplierTypeID int unsigned not null,
   SupplierOrgID int,
   PrimarySupplier bool default 0,
   SupplierTitle varchar(128),
   ItemDescription text,
   RelationshipID int,
   SupplierRelationship bool default 0,
   ProductLiabilityCertificate bool default 0,
   LiabilityExpiration date,
   OrganizationID int,
   CatalogID int,
   CatalogPgNo varchar(10),
   PartNo varchar(30),
   ItemURL varchar(128),
   UnitCost decimal(12,4),
   BaseUnitID int,
   MinimumUnitOrder float,
   MinimumOrderUoMID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupplierID
   )
) TYPE=InnoDB;

CREATE TABLE `supc` (
   SupplierConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   SupplierConsiderationTitle varchar(128),
   SupplierConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SupplierConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `supc_l` (
   _RecordID int unsigned not null auto_increment,
   SupplierConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   SupplierConsiderationTitle varchar(128),
   SupplierConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SupplierConsiderationID
   )
) TYPE=InnoDB;

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

CREATE TABLE `sur` (
   SurveyID int unsigned auto_increment not null,
   OrganizationID int,
   SurveyName varchar(128),
   SurveyDesc varchar(255),
   SurveyScaleTitleID int,
   NoOfQuest int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sur_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyID int unsigned  not null,
   OrganizationID int,
   SurveyName varchar(128),
   SurveyDesc varchar(255),
   SurveyScaleTitleID int,
   NoOfQuest int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyID
   )
) TYPE=InnoDB;

CREATE TABLE `surp` (
   SurveyPartID int unsigned auto_increment not null,
   SurveyID int,
   Anonymous bool default 0,
   ParticipationTime time,
   ParticipationReasonID int,
   ResultID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyPartID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surp_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyPartID int unsigned  not null,
   SurveyID int,
   Anonymous bool default 0,
   ParticipationTime time,
   ParticipationReasonID int,
   ResultID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyPartID
   )
) TYPE=InnoDB;

CREATE TABLE `surq` (
   QuestionID int not null auto_increment,
   SurveyID int not null,
   SurveyQuestion varchar(255),
   QuestionGroupID int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QuestionID
   ),
   INDEX surq_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surq_l` (
   _RecordID int unsigned not null auto_increment,
   QuestionID int not null ,
   SurveyID int not null,
   SurveyQuestion varchar(255),
   QuestionGroupID int,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QuestionID
   ),
   INDEX surq_l_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

CREATE TABLE `surqg` (
   QuestionGroupID int not null auto_increment,
   SurveyID int not null,
   Name varchar(50),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      QuestionGroupID
   ),
   INDEX surqg_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surqg_l` (
   _RecordID int unsigned not null auto_increment,
   QuestionGroupID int not null ,
   SurveyID int not null,
   Name varchar(50),
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      QuestionGroupID
   ),
   INDEX surqg_l_SurveyID (
      SurveyID
   )
) TYPE=InnoDB;

CREATE TABLE `surr` (
   ResultID int unsigned auto_increment not null,
   SurveyPartID int,
   QuestionID int,
   SurveyScaleValueID int,
   SurveyScore float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResultID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surr_l` (
   _RecordID int unsigned not null auto_increment,
   ResultID int unsigned  not null,
   SurveyPartID int,
   QuestionID int,
   SurveyScaleValueID int,
   SurveyScore float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResultID
   )
) TYPE=InnoDB;

CREATE TABLE `surs` (
   SurveyScaleID int unsigned auto_increment not null,
   Name varchar(50) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyScaleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surs_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyScaleID int unsigned  not null,
   Name varchar(50) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyScaleID
   )
) TYPE=InnoDB;

CREATE TABLE `surso` (
   OptionID int auto_increment not null,
   SurveyScaleID int unsigned not null,
   Label varchar(50) not null,
   Value int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OptionID
   ),
   INDEX surso_SurveyScaleID (
      SurveyScaleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `surso_l` (
   _RecordID int unsigned not null auto_increment,
   OptionID int  not null,
   SurveyScaleID int unsigned not null,
   Label varchar(50) not null,
   Value int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OptionID
   ),
   INDEX surso_l_SurveyScaleID (
      SurveyScaleID
   )
) TYPE=InnoDB;

CREATE TABLE `sursv` (
   SurveyScaleValueID int unsigned auto_increment not null,
   SurveyScaleTitleID int,
   ScaleValue float,
   ScaleValueDesc varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SurveyScaleValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sursv_l` (
   _RecordID int unsigned not null auto_increment,
   SurveyScaleValueID int unsigned  not null,
   SurveyScaleTitleID int,
   ScaleValue float,
   ScaleValueDesc varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SurveyScaleValueID
   )
) TYPE=InnoDB;

CREATE TABLE `sys` (
   SystemID int unsigned auto_increment not null,
   OrganizationID int,
   SystemTypeID int unsigned not null,
   SystemName varchar(128),
   SystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sys_l` (
   _RecordID int unsigned not null auto_increment,
   SystemID int unsigned  not null,
   OrganizationID int,
   SystemTypeID int unsigned not null,
   SystemName varchar(128),
   SystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SystemID
   )
) TYPE=InnoDB;

CREATE TABLE `sysc` (
   ComponentID int unsigned auto_increment not null,
   SubSystemID int,
   SysComponentTypeID int,
   ComponentName varchar(128),
   ComponentNumber varchar(50),
   ComponentDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ComponentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysc_l` (
   _RecordID int unsigned not null auto_increment,
   ComponentID int unsigned  not null,
   SubSystemID int,
   SysComponentTypeID int,
   ComponentName varchar(128),
   ComponentNumber varchar(50),
   ComponentDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ComponentID
   )
) TYPE=InnoDB;

CREATE TABLE `sysch` (
   SystemChemicalID int unsigned auto_increment not null,
   SystemID int,
   ChemicalInventoryID int,
   MatClassID int,
   DailyVolume float,
   DVWeightVolUnitsID int,
   EmissionTypeID int,
   EmissionConc float,
   ConcentrationUnitsID int,
   EmissionsEstimate float,
   EEWeightVolUnitsID int,
   ReleasePeriod float,
   RelPeriodUnitsID int,
   EeMonitoring bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemChemicalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysch_l` (
   _RecordID int unsigned not null auto_increment,
   SystemChemicalID int unsigned  not null,
   SystemID int,
   ChemicalInventoryID int,
   MatClassID int,
   DailyVolume float,
   DVWeightVolUnitsID int,
   EmissionTypeID int,
   EmissionConc float,
   ConcentrationUnitsID int,
   EmissionsEstimate float,
   EEWeightVolUnitsID int,
   ReleasePeriod float,
   RelPeriodUnitsID int,
   EeMonitoring bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SystemChemicalID
   )
) TYPE=InnoDB;

CREATE TABLE `sysct` (
   SysComponentTypeID int unsigned auto_increment not null,
   ComponentCategoryID int,
   ComponentType varchar(128),
   ComponentDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SysComponentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sysct_l` (
   _RecordID int unsigned not null auto_increment,
   SysComponentTypeID int unsigned  not null,
   ComponentCategoryID int,
   ComponentType varchar(128),
   ComponentDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SysComponentTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `syss` (
   SubSystemID int unsigned auto_increment not null,
   SystemID int,
   SubSystemName varchar(128),
   SubSystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SubSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `syss_l` (
   _RecordID int unsigned not null auto_increment,
   SubSystemID int unsigned  not null,
   SystemID int,
   SubSystemName varchar(128),
   SubSystemDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SubSystemID
   )
) TYPE=InnoDB;

CREATE TABLE `syst` (
   SystemTypeID int unsigned auto_increment not null,
   SystemCategoryID int,
   SystemTypeName varchar(128),
   SystemTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SystemTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `syst_l` (
   _RecordID int unsigned not null auto_increment,
   SystemTypeID int unsigned  not null,
   SystemCategoryID int,
   SystemTypeName varchar(128),
   SystemTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SystemTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `tas` (
   TaskID int unsigned auto_increment not null,
   OrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TaskID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tas_l` (
   _RecordID int unsigned not null auto_increment,
   TaskID int unsigned  not null,
   OrganizationID int,
   FunctionID int,
   TaskTypeID int unsigned not null,
   TaskName varchar(128),
   TaskDesc varchar(255),
   CriticalControlTaskID int,
   TaskStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TaskID
   )
) TYPE=InnoDB;

CREATE TABLE `tasc` (
   CarryingID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CarryTaskTitle varchar(128),
   CarryWeight float,
   WeightUnitID int,
   CarryDistance float,
   DistanceUnitID int,
   CarryDuration float,
   TimeUnitID int,
   Frequency float,
   CarryingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CarryingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasc_l` (
   _RecordID int unsigned not null auto_increment,
   CarryingID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CarryTaskTitle varchar(128),
   CarryWeight float,
   WeightUnitID int,
   CarryDistance float,
   DistanceUnitID int,
   CarryDuration float,
   TimeUnitID int,
   Frequency float,
   CarryingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CarryingID
   )
) TYPE=InnoDB;

CREATE TABLE `tasga` (
   ActivityID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GeneralActivityTitle varchar(128),
   FunActivityID int,
   ActivityDuration float,
   TimeUnitID int,
   Frequency float,
   ActivityDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ActivityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasga_l` (
   _RecordID int unsigned not null auto_increment,
   ActivityID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   GeneralActivityTitle varchar(128),
   FunActivityID int,
   ActivityDuration float,
   TimeUnitID int,
   Frequency float,
   ActivityDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ActivityID
   )
) TYPE=InnoDB;

CREATE TABLE `tashu` (
   HandUseID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HandUseTitle varchar(128),
   UseofHandsID int,
   HandUseDuration float,
   TimeUnitID int,
   Frequency float,
   HandUseDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HandUseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tashu_l` (
   _RecordID int unsigned not null auto_increment,
   HandUseID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HandUseTitle varchar(128),
   UseofHandsID int,
   HandUseDuration float,
   TimeUnitID int,
   Frequency float,
   HandUseDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HandUseID
   )
) TYPE=InnoDB;

CREATE TABLE `tasl` (
   LiftingID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LiftTaskTitle varchar(128),
   LiftWeight float,
   WeightUnitID int,
   LiftHeight float,
   DistanceUnitID int,
   LiftDuration float,
   TimeUnitID int,
   Frequency float,
   LiftingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LiftingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tasl_l` (
   _RecordID int unsigned not null auto_increment,
   LiftingID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   LiftTaskTitle varchar(128),
   LiftWeight float,
   WeightUnitID int,
   LiftHeight float,
   DistanceUnitID int,
   LiftDuration float,
   TimeUnitID int,
   Frequency float,
   LiftingDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LiftingID
   )
) TYPE=InnoDB;

CREATE TABLE `tast` (
   TaskTypeID int unsigned auto_increment not null,
   TaskCategoryID int,
   TaskTypeTitle varchar(128),
   TaskTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TaskTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tast_l` (
   _RecordID int unsigned not null auto_increment,
   TaskTypeID int unsigned  not null,
   TaskCategoryID int,
   TaskTypeTitle varchar(128),
   TaskTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TaskTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `tra` (
   CourseID int unsigned auto_increment not null,
   CourseCode varchar(50),
   CourseTitle varchar(128),
   CourseDesc text,
   CourseObj text,
   TrainingTypeID int unsigned not null,
   OrgLevelID int,
   MinClassSize float,
   MaxClassSize float,
   Duration float,
   TimeUnitID int,
   Validity float,
   ValidityUnitID int,
   CoursePrep text,
   OrganizationID int,
   RegulatoryRequired bool default 0,
   CourseStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CourseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tra_l` (
   _RecordID int unsigned not null auto_increment,
   CourseID int unsigned  not null,
   CourseCode varchar(50),
   CourseTitle varchar(128),
   CourseDesc text,
   CourseObj text,
   TrainingTypeID int unsigned not null,
   OrgLevelID int,
   MinClassSize float,
   MaxClassSize float,
   Duration float,
   TimeUnitID int,
   Validity float,
   ValidityUnitID int,
   CoursePrep text,
   OrganizationID int,
   RegulatoryRequired bool default 0,
   CourseStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CourseID
   )
) TYPE=InnoDB;

CREATE TABLE `traas` (
   AttendeeSchedID int unsigned auto_increment not null,
   TrainingClassID int unsigned not null,
   Completed bool default 1,
   PunctualnessID int default 2,
   AttentivenessID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AttendeeSchedID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `traas_l` (
   _RecordID int unsigned not null auto_increment,
   AttendeeSchedID int unsigned  not null,
   TrainingClassID int unsigned not null,
   Completed bool default 1,
   PunctualnessID int default 2,
   AttentivenessID int default 1,
   AttendeeDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AttendeeSchedID
   )
) TYPE=InnoDB;

CREATE TABLE `trac` (
   CertificationID int unsigned auto_increment not null,
   OrganizationID int,
   CertificationTypeID int,
   CertificationTitle varchar(128),
   CertificationDesc text,
   CertificationDuration float,
   CertificationDurationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trac_l` (
   _RecordID int unsigned not null auto_increment,
   CertificationID int unsigned  not null,
   OrganizationID int,
   CertificationTypeID int,
   CertificationTitle varchar(128),
   CertificationDesc text,
   CertificationDuration float,
   CertificationDurationUnitsID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertificationID
   )
) TYPE=InnoDB;

CREATE TABLE `traca` (
   TrainingAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CourseID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `traca_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CourseID int unsigned not null,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `tracl` (
   TrainingClassID int unsigned auto_increment not null,
   CourseID int unsigned not null,
   OrganizationID int,
   LocationID int unsigned not null,
   SpecialPreparation text,
   ClassStartTime datetime,
   ClassStatusID int,
   IssuesDiscussed text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingClassID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracl_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingClassID int unsigned  not null,
   CourseID int unsigned not null,
   OrganizationID int,
   LocationID int unsigned not null,
   SpecialPreparation text,
   ClassStartTime datetime,
   ClassStatusID int,
   IssuesDiscussed text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingClassID
   )
) TYPE=InnoDB;

CREATE TABLE `tracn` (
   TrainingConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   CourseID int unsigned not null,
   TrainingConsiderationTitle varchar(128),
   TrainingConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracn_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   CourseID int unsigned not null,
   TrainingConsiderationTitle varchar(128),
   TrainingConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `tracp` (
   CertPrereqID int unsigned auto_increment not null,
   CertificationID int,
   CourseID int,
   CourseSatisfactionLevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CertPrereqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tracp_l` (
   _RecordID int unsigned not null auto_increment,
   CertPrereqID int unsigned  not null,
   CertificationID int,
   CourseID int,
   CourseSatisfactionLevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CertPrereqID
   )
) TYPE=InnoDB;

CREATE TABLE `trai` (
   TrainingInstructorID int unsigned auto_increment not null,
   CourseID int,
   Qualifications text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingInstructorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trai_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingInstructorID int unsigned  not null,
   CourseID int,
   Qualifications text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingInstructorID
   )
) TYPE=InnoDB;

CREATE TABLE `trak` (
   TrainingKSAID int unsigned auto_increment not null,
   CourseID int,
   KSAID int,
   LevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingKSAID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trak_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingKSAID int unsigned  not null,
   CourseID int,
   KSAID int,
   LevelID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingKSAID
   )
) TYPE=InnoDB;

CREATE TABLE `tram` (
   MaterialID int unsigned auto_increment not null,
   CourseMaterialTypeID int,
   MaterialName varchar(128),
   MaterialDesc text,
   MaterialNo varchar(50),
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MaterialID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tram_l` (
   _RecordID int unsigned not null auto_increment,
   MaterialID int unsigned  not null,
   CourseMaterialTypeID int,
   MaterialName varchar(128),
   MaterialDesc text,
   MaterialNo varchar(50),
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MaterialID
   )
) TYPE=InnoDB;

CREATE TABLE `trama` (
   MaterialAssocID int unsigned auto_increment not null,
   CourseID int,
   MaterialID int,
   MaterialQuantity float,
   MaterialQuantityUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MaterialAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trama_l` (
   _RecordID int unsigned not null auto_increment,
   MaterialAssocID int unsigned  not null,
   CourseID int,
   MaterialID int,
   MaterialQuantity float,
   MaterialQuantityUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MaterialAssocID
   )
) TYPE=InnoDB;

CREATE TABLE `trap` (
   PrereqID int unsigned auto_increment not null,
   CourseID int,
   PrereqCourseID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PrereqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trap_l` (
   _RecordID int unsigned not null auto_increment,
   PrereqID int unsigned  not null,
   CourseID int,
   PrereqCourseID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PrereqID
   )
) TYPE=InnoDB;

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

CREATE TABLE `trat` (
   TrainingTypeID int auto_increment not null,
   TrainingCategoryID int,
   TrainingTypeTitle varchar(128),
   TrainingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrainingTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trat_l` (
   _RecordID int unsigned not null auto_increment,
   TrainingTypeID int  not null,
   TrainingCategoryID int,
   TrainingTypeTitle varchar(128),
   TrainingTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrainingTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `tru` (
   TrustAccountID int unsigned auto_increment not null,
   OrganizationID int,
   AccountName varchar(128),
   TrustAccountNumber varchar(50),
   AccountDesc text,
   TrustAmount decimal(12,4),
   AccountStatusID int,
   AccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustAccountID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tru_l` (
   _RecordID int unsigned not null auto_increment,
   TrustAccountID int unsigned  not null,
   OrganizationID int,
   AccountName varchar(128),
   TrustAccountNumber varchar(50),
   AccountDesc text,
   TrustAmount decimal(12,4),
   AccountStatusID int,
   AccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustAccountID
   )
) TYPE=InnoDB;

CREATE TABLE `trud` (
   TrustDistributionID int unsigned auto_increment not null,
   TrustAccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustDistributionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trud_l` (
   _RecordID int unsigned not null auto_increment,
   TrustDistributionID int unsigned  not null,
   TrustAccountID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustDistributionID
   )
) TYPE=InnoDB;

CREATE TABLE `true` (
   ExpenditureID int unsigned auto_increment not null,
   TrustDistributionID int unsigned not null,
   ProcessObstacleTypeID int,
   ObstacleDesc text,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentApproachID int,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExpenditureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `true_l` (
   _RecordID int unsigned not null auto_increment,
   ExpenditureID int unsigned  not null,
   TrustDistributionID int unsigned not null,
   ProcessObstacleTypeID int,
   ObstacleDesc text,
   ExpenseDate date,
   ExpenseAmount decimal(12,4),
   PayeeOrgID int,
   ExpenseTitle varchar(128),
   ExpenseDesc text,
   ExpensePaymentApproachID int,
   ExpensePaymentMethodID int,
   TransactionDocNumber varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExpenditureID
   )
) TYPE=InnoDB;

CREATE TABLE `trut` (
   TrustTransferID int unsigned auto_increment not null,
   FromDistributionID int,
   ToDistributionID int,
   TrustAccountTransferTypeID int,
   TransferAmount decimal(12,4),
   TransferDesc text,
   TransferDocNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TrustTransferID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `trut_l` (
   _RecordID int unsigned not null auto_increment,
   TrustTransferID int unsigned  not null,
   FromDistributionID int,
   ToDistributionID int,
   TrustAccountTransferTypeID int,
   TransferAmount decimal(12,4),
   TransferDesc text,
   TransferDocNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TrustTransferID
   )
) TYPE=InnoDB;

CREATE TABLE `twn` (
   TownHallQuestionID int unsigned auto_increment not null,
   OrganizationID int,
   QuestionTypeID int,
   QuestionAsked varchar(128),
   QuestionDetails text,
   QuestionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TownHallQuestionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `twn_l` (
   _RecordID int unsigned not null auto_increment,
   TownHallQuestionID int unsigned  not null,
   OrganizationID int,
   QuestionTypeID int,
   QuestionAsked varchar(128),
   QuestionDetails text,
   QuestionStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TownHallQuestionID
   )
) TYPE=InnoDB;

CREATE TABLE `twna` (
   TownHallAnswerID int unsigned auto_increment not null,
   TownHallQuestionID int unsigned not null,
   AnswertoQuestion text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      TownHallAnswerID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `twna_l` (
   _RecordID int unsigned not null auto_increment,
   TownHallAnswerID int unsigned  not null,
   TownHallQuestionID int unsigned not null,
   AnswertoQuestion text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      TownHallAnswerID
   )
) TYPE=InnoDB;

CREATE TABLE `usr` (
   PersonID int unsigned not null,
   Username varchar(25),
   Password varchar(50),
   IsAdmin bool,
   LangID varchar(5),
   DefaultOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PersonID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usr_l` (
   _RecordID int unsigned not null auto_increment,
   PersonID int unsigned not null,
   Username varchar(25),
   Password varchar(50),
   IsAdmin bool,
   LangID varchar(5),
   DefaultOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PersonID
   )
) TYPE=InnoDB;

CREATE TABLE `usrdi` (
   RecordID int auto_increment not null,
   PersonID int,
   ModuleID varchar(5),
   Dismiss bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecordID
   ),
   INDEX usrdi_PersonModule (
      PersonID,
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrdi_l` (
   _RecordID int unsigned not null auto_increment,
   RecordID int  not null,
   PersonID int,
   ModuleID varchar(5),
   Dismiss bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecordID
   ),
   INDEX usrdi_l_PersonModule (
      PersonID,
      ModuleID
   )
) TYPE=InnoDB;

CREATE TABLE `usrds` (
   RecordID int auto_increment not null,
   PersonID int not null,
   Title varchar(50) not null,
   Link varchar(128) not null,
   Type varchar(10) not null,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RecordID
   ),
   INDEX usrds_PersonID (
      PersonID
   ),
   INDEX usrds_PersonLink (
      PersonID,
      Link
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrds_l` (
   _RecordID int unsigned not null auto_increment,
   RecordID int  not null,
   PersonID int not null,
   Title varchar(50) not null,
   Link varchar(128) not null,
   Type varchar(10) not null,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RecordID
   ),
   INDEX usrds_l_PersonID (
      PersonID
   ),
   INDEX usrds_l_PersonLink (
      PersonID,
      Link
   )
) TYPE=InnoDB;

CREATE TABLE `usrl` (
   EntryID int unsigned auto_increment not null,
   PersonID int unsigned not null,
   EventTypeID int unsigned not null,
   EventDescription text not null,
   EventURL text default null,
   RemoteIP varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EntryID
   ),
   INDEX usrl_PersonID_EventTypeID_ModDate (
      PersonID,
      EventTypeID,
      _ModDate
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrl_l` (
   _RecordID int unsigned not null auto_increment,
   EntryID int unsigned  not null,
   PersonID int unsigned not null,
   EventTypeID int unsigned not null,
   EventDescription text not null,
   EventURL text default null,
   RemoteIP varchar(15),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EntryID
   ),
   INDEX usrl_l_PersonID_EventTypeID_ModDate (
      PersonID,
      EventTypeID,
      _ModDate
   )
) TYPE=InnoDB;

CREATE TABLE `usrp` (
   PermissionID int unsigned auto_increment not null,
   PersonID int unsigned not null,
   ModuleID varchar(5) not null,
   EditPermission tinyint unsigned not null,
   ViewPermission tinyint unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermissionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrp_l` (
   _RecordID int unsigned not null auto_increment,
   PermissionID int unsigned  not null,
   PersonID int unsigned not null,
   ModuleID varchar(5) not null,
   EditPermission tinyint unsigned not null,
   ViewPermission tinyint unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermissionID
   )
) TYPE=InnoDB;

CREATE TABLE `usrpo` (
   PermitOrganizationID int unsigned auto_increment not null,
   PersonID int unsigned not null,
   OrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitOrganizationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrpo_l` (
   _RecordID int unsigned not null auto_increment,
   PermitOrganizationID int unsigned  not null,
   PersonID int unsigned not null,
   OrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitOrganizationID
   )
) TYPE=InnoDB;

CREATE TABLE `usrsd` (
   ConditionID int auto_increment not null,
   ModuleID varchar(5) not null,
   UserID int not null,
   ConditionField varchar(50) not null,
   ConditionValue varchar(50) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ConditionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `usrsd_l` (
   _RecordID int unsigned not null auto_increment,
   ConditionID int  not null,
   ModuleID varchar(5) not null,
   UserID int not null,
   ConditionField varchar(50) not null,
   ConditionValue varchar(50) not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ConditionID
   )
) TYPE=InnoDB;

CREATE TABLE `uts` (
   UnitID int unsigned auto_increment not null,
   UnitCategoryID int,
   UnitName varchar(128),
   UnitValue decimal(12,2),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      UnitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `uts_l` (
   _RecordID int unsigned not null auto_increment,
   UnitID int unsigned  not null,
   UnitCategoryID int,
   UnitName varchar(128),
   UnitValue decimal(12,2),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      UnitID
   )
) TYPE=InnoDB;

CREATE TABLE `val` (
   ValueID int unsigned auto_increment not null,
   OrganizationID int,
   ValueTitle varchar(128),
   ValueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `val_l` (
   _RecordID int unsigned not null auto_increment,
   ValueID int unsigned  not null,
   OrganizationID int,
   ValueTitle varchar(128),
   ValueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValueID
   )
) TYPE=InnoDB;

CREATE TABLE `vala` (
   ValuesAssociationID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SharedValueID int,
   ValuesAssociationTitle varchar(128),
   Manner text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValuesAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vala_l` (
   _RecordID int unsigned not null auto_increment,
   ValuesAssociationID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   SharedValueID int,
   ValuesAssociationTitle varchar(128),
   Manner text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValuesAssociationID
   )
) TYPE=InnoDB;

CREATE TABLE `valc` (
   ValuesConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   ValueID int,
   ValueConsiderationTitle varchar(128),
   ValueConsiderationDescription text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ValuesConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `valc_l` (
   _RecordID int unsigned not null auto_increment,
   ValuesConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   ValueID int,
   ValueConsiderationTitle varchar(128),
   ValueConsiderationDescription text,
   ImpactTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ValuesConsiderationID
   )
) TYPE=InnoDB;

CREATE TABLE `vals` (
   SharedValueID int unsigned auto_increment not null,
   ValueID int,
   SharingOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SharedValueID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vals_l` (
   _RecordID int unsigned not null auto_increment,
   SharedValueID int unsigned  not null,
   ValueID int,
   SharingOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SharedValueID
   )
) TYPE=InnoDB;

CREATE TABLE `valt` (
   ThreatID int unsigned auto_increment not null,
   SharedValueID int,
   ThreatTitle varchar(128),
   ThreatDesc text,
   SurveyValidation bool default 0,
   SurveyID int unsigned not null,
   ThreatAbateStatusID int,
   ResolutionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ThreatID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `valt_l` (
   _RecordID int unsigned not null auto_increment,
   ThreatID int unsigned  not null,
   SharedValueID int,
   ThreatTitle varchar(128),
   ThreatDesc text,
   SurveyValidation bool default 0,
   SurveyID int unsigned not null,
   ThreatAbateStatusID int,
   ResolutionDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ThreatID
   )
) TYPE=InnoDB;

CREATE TABLE `veh` (
   VehicleID int unsigned auto_increment not null,
   AssignedOrganizationID int,
   VehYear varchar(50),
   VehicleModelID int,
   VehicleTypeID int,
   VehBody varchar(255),
   VehOperationTypeID int,
   VehColorID int,
   VehFuelTypeID int,
   VehDesc varchar(255),
   VehValue decimal(12,4),
   VehLoad float,
   VehLoadUnitsID int,
   VehWeight float,
   VehWeightUnitsID int,
   OwnerID int,
   VehNo varchar(50),
   VehIDNo varchar(50),
   VehLicenseNo varchar(50),
   StateID int,
   Commercial bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veh_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleID int unsigned  not null,
   AssignedOrganizationID int,
   VehYear varchar(50),
   VehicleModelID int,
   VehicleTypeID int,
   VehBody varchar(255),
   VehOperationTypeID int,
   VehColorID int,
   VehFuelTypeID int,
   VehDesc varchar(255),
   VehValue decimal(12,4),
   VehLoad float,
   VehLoadUnitsID int,
   VehWeight float,
   VehWeightUnitsID int,
   OwnerID int,
   VehNo varchar(50),
   VehIDNo varchar(50),
   VehLicenseNo varchar(50),
   StateID int,
   Commercial bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleID
   )
) TYPE=InnoDB;

CREATE TABLE `veha` (
   VehAssignmentID int unsigned auto_increment not null,
   VehicleID int,
   OrganizationID int,
   EstReturnDate datetime,
   Returned bool default 0,
   ActReturnDate datetime,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehAssignmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veha_l` (
   _RecordID int unsigned not null auto_increment,
   VehAssignmentID int unsigned  not null,
   VehicleID int,
   OrganizationID int,
   EstReturnDate datetime,
   Returned bool default 0,
   ActReturnDate datetime,
   IssueDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehAssignmentID
   )
) TYPE=InnoDB;

CREATE TABLE `vehmd` (
   VehicleModelID int unsigned auto_increment not null,
   VehMakeID int,
   VehModel varchar(128),
   VehModelNo varchar(20),
   VehModelDesc text,
   BestPrice decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleModelID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehmd_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleModelID int unsigned  not null,
   VehMakeID int,
   VehModel varchar(128),
   VehModelNo varchar(20),
   VehModelDesc text,
   BestPrice decimal(12,4),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleModelID
   )
) TYPE=InnoDB;

CREATE TABLE `vehml` (
   MileageID int unsigned auto_increment not null,
   VehicleID int,
   Odometer float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      MileageID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehml_l` (
   _RecordID int unsigned not null auto_increment,
   MileageID int unsigned  not null,
   VehicleID int,
   Odometer float,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      MileageID
   )
) TYPE=InnoDB;

CREATE TABLE `veht` (
   VehicleTypeID int unsigned auto_increment not null,
   VehicleTypeName varchar(128),
   VehicleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `veht_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleTypeID int unsigned  not null,
   VehicleTypeName varchar(128),
   VehicleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `vehtn` (
   VehicleTrackingNoID int unsigned auto_increment not null,
   VehicleID int,
   VehicleTrackingNumberTypeID int,
   TrackingNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      VehicleTrackingNoID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `vehtn_l` (
   _RecordID int unsigned not null auto_increment,
   VehicleTrackingNoID int unsigned  not null,
   VehicleID int,
   VehicleTrackingNumberTypeID int,
   TrackingNumber varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      VehicleTrackingNoID
   )
) TYPE=InnoDB;

CREATE TABLE `wam` (
   WeightMeasureID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WeightMeasureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wam_l` (
   _RecordID int unsigned not null auto_increment,
   WeightMeasureID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FirstUnitValue float,
   FirstUnitID int,
   SecondUnitValue float,
   SecondUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WeightMeasureID
   )
) TYPE=InnoDB;

CREATE TABLE `was` (
   WasteID int unsigned auto_increment not null,
   OrganizationID int,
   WasteTypeID int unsigned not null,
   WasteGenerationMethodID int,
   Solid bool default 0,
   Liquid bool default 0,
   Gas bool default 0,
   WasteName varchar(128),
   WasteDesc text,
   DisposalApproachID int,
   ChemicalInvolved bool default 0,
   ChemicalID int,
   SystemProcessInvolved bool default 0,
   SystemID int,
   LocalWasteIDs text,
   OrgWasteIDs text,
   AmtGenYear float,
   AmtGenYearUnitsID int,
   LocalDisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `was_l` (
   _RecordID int unsigned not null auto_increment,
   WasteID int unsigned  not null,
   OrganizationID int,
   WasteTypeID int unsigned not null,
   WasteGenerationMethodID int,
   Solid bool default 0,
   Liquid bool default 0,
   Gas bool default 0,
   WasteName varchar(128),
   WasteDesc text,
   DisposalApproachID int,
   ChemicalInvolved bool default 0,
   ChemicalID int,
   SystemProcessInvolved bool default 0,
   SystemID int,
   LocalWasteIDs text,
   OrgWasteIDs text,
   AmtGenYear float,
   AmtGenYearUnitsID int,
   LocalDisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteID
   )
) TYPE=InnoDB;

CREATE TABLE `wasdf` (
   WasteDisposalFacilityID int unsigned auto_increment not null,
   WasteID int,
   DisposalFacilityID int,
   DisposalContractAgmt bool default 0,
   DisposalFacilityApproval bool default 0,
   DisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteDisposalFacilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wasdf_l` (
   _RecordID int unsigned not null auto_increment,
   WasteDisposalFacilityID int unsigned  not null,
   WasteID int,
   DisposalFacilityID int,
   DisposalContractAgmt bool default 0,
   DisposalFacilityApproval bool default 0,
   DisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteDisposalFacilityID
   )
) TYPE=InnoDB;

CREATE TABLE `wass` (
   WasteStorageID int unsigned auto_increment not null,
   WasteID int,
   StorageOrganizationID int,
   StorageMethodID int,
   Quantity float,
   QuantityUoMID int,
   StorageDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteStorageID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wass_l` (
   _RecordID int unsigned not null auto_increment,
   WasteStorageID int unsigned  not null,
   WasteID int,
   StorageOrganizationID int,
   StorageMethodID int,
   Quantity float,
   QuantityUoMID int,
   StorageDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteStorageID
   )
) TYPE=InnoDB;

CREATE TABLE `wassy` (
   WasteSystemID int unsigned auto_increment not null,
   WasteID int,
   SystemID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wassy_l` (
   _RecordID int unsigned not null auto_increment,
   WasteSystemID int unsigned  not null,
   WasteID int,
   SystemID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteSystemID
   )
) TYPE=InnoDB;

CREATE TABLE `wast` (
   WasteTypeID int unsigned auto_increment not null,
   WasteCategoryID int,
   WasteTypeName varchar(128),
   WasteTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wast_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTypeID int unsigned  not null,
   WasteCategoryID int,
   WasteTypeName varchar(128),
   WasteTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTypeID
   )
) TYPE=InnoDB;

CREATE TABLE `wastf` (
   WasteTransferID int unsigned auto_increment not null,
   WasteID int,
   WasteTransporterID int,
   WasteShipAmt float,
   WasteShipAmtUnitsID int,
   Price decimal(12,4),
   PriceUnitID int,
   Revenue decimal(12,4),
   WasteDisposalFacilityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTransferID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wastf_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTransferID int unsigned  not null,
   WasteID int,
   WasteTransporterID int,
   WasteShipAmt float,
   WasteShipAmtUnitsID int,
   Price decimal(12,4),
   PriceUnitID int,
   Revenue decimal(12,4),
   WasteDisposalFacilityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTransferID
   )
) TYPE=InnoDB;

CREATE TABLE `wastp` (
   WasteTransporterID int unsigned auto_increment not null,
   WasteID int,
   TransporterID int,
   TransportContractAgmt bool default 0,
   TransporterFacilityApproval bool default 0,
   TransporterPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTransporterID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wastp_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTransporterID int unsigned  not null,
   WasteID int,
   TransporterID int,
   TransportContractAgmt bool default 0,
   TransporterFacilityApproval bool default 0,
   TransporterPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTransporterID
   )
) TYPE=InnoDB;

CREATE TABLE `wrk` (
   WorkOrderID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   WorkOrderNo varchar(30),
   WorkOrderTypeID int,
   WorkOrderTitle varchar(128),
   WorkOrderDesc text,
   PriorityID int,
   WorkOrderStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkOrderID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wrk_l` (
   _RecordID int unsigned not null auto_increment,
   WorkOrderID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   WorkOrderNo varchar(30),
   WorkOrderTypeID int,
   WorkOrderTitle varchar(128),
   WorkOrderDesc text,
   PriorityID int,
   WorkOrderStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkOrderID
   )
) TYPE=InnoDB;

