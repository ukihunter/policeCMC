-- ========================================================
-- Police CMS - Sample Data Insert Script
-- 50 Sample Cases for Testing and Development
-- Generated: January 7, 2026
-- ========================================================

USE `police_cms`;

-- Insert 50 sample cases with diverse data
INSERT INTO `cases` 
(`case_number`, `previous_date`, `information_book`, `register_number`, `date_produce_b_report`, 
`date_produce_plant`, `opens`, `attorney_general_advice`, `production_register_number`, 
`date_handover_court`, `receival_memorandum`, `analyst_report`, `suspect_data`, `witness_data`, 
`progress`, `results`, `next_date`, `case_status`, `created_by`, `created_at`) 
VALUES

-- Case 1-10: Various theft and robbery cases
('CASE-2024-001', '2024-01-15', 'RIB', 'GCR 01/2024', '2024-01-20', '2024-01-22', 
'Theft of mobile phones from electronics shop', 'YES', 'PR-2024-001\nPR-2024-002', 
'2024-02-01', 'YES', 'NO', 
'[{"name":"John Doe","address":"123 Main St, City","ic_number":"840115-05-5678"}]',
'[{"name":"Sarah Smith","address":"456 Market Rd","ic_number":"850220-08-1234"}]',
'Investigation ongoing. Suspect arrested and remanded.', NULL, '2024-02-15', 'Ongoing', 1, '2024-01-15 10:30:00'),

('CASE-2024-002', '2024-01-18', 'GCIB I', 'GCR 01/2024', '2024-01-25', NULL,
'Armed robbery at jewelry store', 'YES', 'PR-2024-003\nPR-2024-004\nPR-2024-005',
'2024-02-10', 'YES', 'YES',
'[{"name":"Ahmad Hassan","address":"789 Oak Ave","ic_number":"900305-12-3456"},{"name":"Lim Wei Ming","address":"321 Pine St","ic_number":"910615-07-8901"}]',
'[{"name":"Tan Mei Ling","address":"654 Cedar Ln","ic_number":"880912-10-2345"},{"name":"Kumar Rajesh","address":"987 Maple Dr","ic_number":"750404-11-5678"}]',
'Both suspects in custody. Jewelry items recovered.', 'Convicted - 5 years imprisonment', '2024-03-01', 'Closed', 1, '2024-01-18 14:20:00'),

('CASE-2024-003', '2024-02-01', 'MOIB', 'MOR 02/2024', '2024-02-08', '2024-02-10',
'Motorcycle theft from parking lot', 'NO', 'PR-2024-006',
NULL, 'NO', 'NO',
'[{"name":"David Chen","address":"147 River Rd","ic_number":"920825-06-4567"}]',
'[{"name":"Emily Wong","address":"258 Hill St","ic_number":"870515-05-6789"}]',
'Suspect identified through CCTV. Motorcycle still missing.', NULL, '2024-02-20', 'Pending', 1, '2024-02-01 09:15:00'),

('CASE-2024-004', '2024-02-05', 'VIB', 'VMOR 02/2024', '2024-02-12', '2024-02-14',
'Vehicle break-in and theft', 'YES', 'PR-2024-007\nPR-2024-008',
'2024-02-28', 'YES', 'NO',
'[{"name":"Michael Tan","address":"369 Valley Rd","ic_number":"880430-09-2345"}]',
'[{"name":"Lisa Anderson","address":"741 Summit Ave","ic_number":"910723-12-8901"}]',
'Fingerprints matched. Stolen items partially recovered.', NULL, '2024-03-10', 'Ongoing', 1, '2024-02-05 11:45:00'),

('CASE-2024-005', '2024-02-10', 'CIB I', 'GCR 02/2024', '2024-02-18', NULL,
'Burglary at residential house', 'NO', 'PR-2024-009',
NULL, 'NO', 'NO',
'[]',
'[{"name":"Mary Johnson","address":"852 Park Ln","ic_number":"780912-08-3456"}]',
'Investigation in progress. No suspects identified yet.', NULL, '2024-02-25', 'Ongoing', 1, '2024-02-10 16:30:00'),

('CASE-2024-006', '2024-03-01', 'RIB', 'GCR 03/2024', '2024-03-08', '2024-03-10',
'Shoplifting at supermarket', 'NO', 'PR-2024-010',
'2024-03-20', 'YES', 'NO',
'[{"name":"Susan Lee","address":"963 Beach Rd","ic_number":"950617-11-7890"}]',
'[{"name":"Security Guard Tom","address":"147 Store St","ic_number":"720305-06-4567"}]',
'Caught on camera. Suspect confessed.', 'Case dismissed - first offense', '2024-04-01', 'Closed', 1, '2024-03-01 13:00:00'),

('CASE-2024-007', '2024-03-05', 'GCIB II', 'GCR 03/2024', '2024-03-15', '2024-03-18',
'Commercial premises burglary', 'YES', 'PR-2024-011\nPR-2024-012\nPR-2024-013',
'2024-03-30', 'YES', 'YES',
'[{"name":"Robert Wong","address":"258 Business Park","ic_number":"860820-10-5678"},{"name":"James Lim","address":"369 Industrial Rd","ic_number":"890515-07-2345"}]',
'[{"name":"Security Officer Ali","address":"741 Guard House","ic_number":"750220-12-8901"}]',
'Multiple burglaries linked. Both suspects arrested.', NULL, '2024-04-15', 'Ongoing', 1, '2024-03-05 10:20:00'),

('CASE-2024-008', '2024-03-12', 'EIB', 'GCR 03/2024', '2024-03-20', NULL,
'Embezzlement of company funds', 'YES', 'PR-2024-014',
'2024-04-05', 'YES', 'NO',
'[{"name":"Patricia Kumar","address":"852 Finance Tower","ic_number":"820910-08-6789"}]',
'[{"name":"CEO William Tan","address":"963 Corporate Ave","ic_number":"680425-05-3456"}]',
'Financial records being audited.', NULL, '2024-04-20', 'Ongoing', 1, '2024-03-12 15:45:00'),

('CASE-2024-009', '2024-03-20', 'CPUIB', 'MCR 03/2024', '2024-03-28', '2024-03-30',
'Cybercrime - Online fraud', 'YES', 'PR-2024-015',
'2024-04-10', 'NO', 'YES',
'[{"name":"Kevin Ng","address":"147 Tech Park","ic_number":"930605-09-4567"}]',
'[{"name":"Victim Sarah Chong","address":"258 Cyber St","ic_number":"850815-11-7890"}]',
'Digital forensics analysis completed.', NULL, '2024-04-25', 'Pending', 1, '2024-03-20 09:30:00'),

('CASE-2024-010', '2024-04-01', 'WCIB', 'GCR 04/2024', '2024-04-10', '2024-04-12',
'White collar crime - Tax evasion', 'YES', 'PR-2024-016\nPR-2024-017',
'2024-04-25', 'YES', 'YES',
'[{"name":"Richard Teo","address":"369 Finance District","ic_number":"770320-06-5678"}]',
'[{"name":"Auditor Michelle Lee","address":"741 Accounting Firm","ic_number":"810712-10-2345"}]',
'Tax records seized and under review.', NULL, '2024-05-10', 'Ongoing', 1, '2024-04-01 14:00:00'),

-- Case 11-20: Drug-related offenses
('CASE-2024-011', '2024-04-05', 'RIB', 'GCR 04/2024', '2024-04-15', '2024-04-18',
'Possession of dangerous drugs', 'YES', 'PR-2024-018',
'2024-04-30', 'YES', 'YES',
'[{"name":"Danny Lee","address":"852 Harbor View","ic_number":"940925-07-8901"}]',
'[{"name":"Officer Rahman","address":"Police Station A","ic_number":"720508-12-3456"}]',
'Drugs tested positive. Suspect remanded.', NULL, '2024-05-15', 'Ongoing', 1, '2024-04-05 11:20:00'),

('CASE-2024-012', '2024-04-10', 'TR', 'GCR 04/2024', '2024-04-20', '2024-04-22',
'Drug trafficking', 'YES', 'PR-2024-019\nPR-2024-020\nPR-2024-021',
'2024-05-05', 'YES', 'YES',
'[{"name":"Marcus Chin","address":"963 Waterfront","ic_number":"850410-08-6789"},{"name":"Tony Yap","address":"147 Dock Rd","ic_number":"880625-11-4567"}]',
'[{"name":"Informant X","address":"Confidential","ic_number":"Confidential"}]',
'Large quantity seized. International links being investigated.', NULL, '2024-05-20', 'Ongoing', 1, '2024-04-10 16:50:00'),

('CASE-2024-013', '2024-04-18', '119 TR', 'GCR 04/2024', '2024-04-28', NULL,
'Drug distribution network', 'YES', 'PR-2024-022',
'2024-05-15', 'NO', 'YES',
'[{"name":"Steven Loh","address":"258 Urban Area","ic_number":"910830-09-7890"}]',
'[{"name":"Undercover Officer","address":"Classified","ic_number":"Classified"}]',
'Controlled delivery operation successful.', NULL, '2024-05-25', 'Ongoing', 1, '2024-04-18 13:15:00'),

('CASE-2024-014', '2024-05-01', 'VPN TR', 'GCR 05/2024', '2024-05-10', '2024-05-12',
'Cultivation of cannabis', 'YES', 'PR-2024-023\nPR-2024-024',
'2024-05-25', 'YES', 'YES',
'[{"name":"Peter Tan","address":"369 Rural Road","ic_number":"830215-10-5678"}]',
'[{"name":"Neighbor Alice","address":"741 Next Door","ic_number":"760820-07-2345"}]',
'Plantation discovered and destroyed.', 'Guilty plea - 3 years imprisonment', '2024-06-10', 'Closed', 1, '2024-05-01 10:00:00'),

('CASE-2024-015', '2024-05-08', '118 TR', 'GCR 05/2024', '2024-05-18', NULL,
'Possession of drug paraphernalia', 'NO', 'PR-2024-025',
NULL, 'NO', 'YES',
'[{"name":"Gary Koh","address":"852 Suburban St","ic_number":"960705-12-8901"}]',
'[{"name":"Officer Lee","address":"Police HQ","ic_number":"780920-06-3456"}]',
'Minor offense. Counseling recommended.', NULL, '2024-05-30', 'Pending', 1, '2024-05-08 14:40:00'),

('CASE-2024-016', '2024-05-15', 'RIB', 'GCR 05/2024', '2024-05-25', '2024-05-28',
'Sale of controlled substances', 'YES', 'PR-2024-026',
'2024-06-05', 'YES', 'YES',
'[{"name":"Vincent Ng","address":"963 Night Market","ic_number":"870412-08-6789"}]',
'[{"name":"Buyer turned informant","address":"Protected","ic_number":"Protected"}]',
'Sting operation conducted. Suspect arrested.', NULL, '2024-06-20', 'Ongoing', 1, '2024-05-15 20:30:00'),

('CASE-2024-017', '2024-05-22', 'GCIB III', 'GCR 05/2024', '2024-06-01', '2024-06-05',
'Organized drug syndicate', 'YES', 'PR-2024-027\nPR-2024-028\nPR-2024-029\nPR-2024-030',
'2024-06-15', 'YES', 'YES',
'[{"name":"Boss Chen","address":"147 Hideout","ic_number":"750630-11-4567"},{"name":"Runner Kumar","address":"258 Delivery Point","ic_number":"820915-09-7890"},{"name":"Dealer Wong","address":"369 Street Corner","ic_number":"890520-10-5678"}]',
'[{"name":"Multiple witnesses","address":"Various","ic_number":"Protected"}]',
'Major operation. Multiple arrests made.', NULL, '2024-07-01', 'Ongoing', 1, '2024-05-22 08:45:00'),

('CASE-2024-018', '2024-06-01', 'AIB', 'GCR 06/2024', '2024-06-10', NULL,
'Import of illegal drugs', 'YES', 'PR-2024-031',
'2024-06-25', 'YES', 'NO',
'[{"name":"Import Manager Lee","address":"741 Port Area","ic_number":"810225-07-2345"}]',
'[{"name":"Customs Officer","address":"Port Authority","ic_number":"730815-12-8901"}]',
'Customs interception. Large shipment seized.', NULL, '2024-07-10', 'Ongoing', 1, '2024-06-01 12:00:00'),

('CASE-2024-019', '2024-06-10', 'PIB', 'GCR 06/2024', '2024-06-20', '2024-06-22',
'Prescription drug abuse', 'NO', 'PR-2024-032',
NULL, 'NO', 'YES',
'[{"name":"Doctor suspicious","address":"852 Medical Center","ic_number":"680410-08-6789"}]',
'[{"name":"Pharmacy witness","address":"963 Drug Store","ic_number":"750920-11-3456"}]',
'Medical records under investigation.', NULL, '2024-06-30', 'Pending', 1, '2024-06-10 15:20:00'),

('CASE-2024-020', '2024-06-18', 'TIB', 'TAR 06/2024', '2024-06-28', '2024-06-30',
'Cross-border drug smuggling', 'YES', 'PR-2024-033\nPR-2024-034',
'2024-07-15', 'YES', 'YES',
'[{"name":"Smuggler A","address":"Border Area","ic_number":"920505-09-4567"}]',
'[{"name":"Border patrol","address":"Checkpoint","ic_number":"710825-10-7890"}]',
'International cooperation requested.', NULL, '2024-07-25', 'Ongoing', 1, '2024-06-18 09:30:00'),

-- Case 21-30: Assault and violent crimes
('CASE-2024-021', '2024-06-25', 'VIB', 'GCR 06/2024', '2024-07-05', '2024-07-08',
'Assault causing bodily harm', 'YES', 'PR-2024-035',
'2024-07-20', 'YES', 'NO',
'[{"name":"Attacker James","address":"147 Fight Location","ic_number":"850320-12-5678"}]',
'[{"name":"Victim Thomas","address":"258 Hospital","ic_number":"880715-06-2345"},{"name":"Witness Maria","address":"369 Nearby","ic_number":"920110-08-8901"}]',
'Medical report obtained. Suspect charged.', NULL, '2024-08-05', 'Ongoing', 1, '2024-06-25 22:15:00'),

('CASE-2024-022', '2024-07-01', 'RIB', 'GCR 07/2024', '2024-07-12', NULL,
'Domestic violence', 'NO', 'PR-2024-036',
NULL, 'NO', 'NO',
'[{"name":"Husband Abdul","address":"741 Family Home","ic_number":"790505-11-3456"}]',
'[{"name":"Wife Siti","address":"852 Safe House","ic_number":"820910-09-6789"}]',
'Protection order issued. Case ongoing.', NULL, '2024-07-20', 'Ongoing', 1, '2024-07-01 18:45:00'),

('CASE-2024-023', '2024-07-08', 'GCIB I', 'GCR 07/2024', '2024-07-18', '2024-07-20',
'Gang-related assault', 'YES', 'PR-2024-037\nPR-2024-038',
'2024-08-01', 'YES', 'YES',
'[{"name":"Gang member A","address":"963 Territory A","ic_number":"900625-10-4567"},{"name":"Gang member B","address":"147 Territory A","ic_number":"910815-07-7890"}]',
'[{"name":"Multiple victims","address":"258 Incident Site","ic_number":"Various"}]',
'Gang unit involved. Multiple charges filed.', NULL, '2024-08-15', 'Ongoing', 1, '2024-07-08 03:20:00'),

('CASE-2024-024', '2024-07-15', 'MOIB', 'GCR 07/2024', '2024-07-25', NULL,
'Road rage incident', 'NO', 'PR-2024-039',
NULL, 'NO', 'NO',
'[{"name":"Driver angry","address":"369 Highway","ic_number":"860430-08-5678"}]',
'[{"name":"Other driver","address":"741 Vehicle","ic_number":"880920-12-2345"}]',
'Traffic camera footage obtained.', NULL, '2024-08-01', 'Pending', 1, '2024-07-15 16:30:00'),

('CASE-2024-025', '2024-07-22', 'CIB II', 'GCR 07/2024', '2024-08-01', '2024-08-05',
'Armed assault', 'YES', 'PR-2024-040',
'2024-08-20', 'YES', 'YES',
'[{"name":"Armed suspect","address":"852 Crime Scene","ic_number":"830715-09-8901"}]',
'[{"name":"Injured victim","address":"963 Emergency Room","ic_number":"750220-11-3456"}]',
'Weapon recovered. Serious charges filed.', NULL, '2024-09-01', 'Ongoing', 1, '2024-07-22 21:00:00'),

('CASE-2024-026', '2024-08-01', 'EIB', 'GCR 08/2024', '2024-08-12', NULL,
'Elder abuse case', 'YES', 'PR-2024-041',
'2024-08-25', 'YES', 'NO',
'[{"name":"Caretaker negligent","address":"147 Care Home","ic_number":"920505-10-6789"}]',
'[{"name":"Family member","address":"258 Report","ic_number":"650810-07-4567"}]',
'Social services involved. Investigation ongoing.', NULL, '2024-09-10', 'Ongoing', 1, '2024-08-01 10:15:00'),

('CASE-2024-027', '2024-08-10', 'CPUIB', 'GCR 08/2024', '2024-08-20', '2024-08-22',
'Stalking and harassment', 'NO', 'PR-2024-042',
NULL, 'NO', 'NO',
'[{"name":"Stalker person","address":"369 Follows victim","ic_number":"880915-08-7890"}]',
'[{"name":"Victim scared","address":"741 Protected address","ic_number":"910425-12-5678"}]',
'Restraining order applied for.', NULL, '2024-08-30', 'Pending', 1, '2024-08-10 14:45:00'),

('CASE-2024-028', '2024-08-18', 'WCIB', 'GCR 08/2024', '2024-08-28', '2024-08-30',
'Workplace violence', 'YES', 'PR-2024-043',
'2024-09-10', 'YES', 'NO',
'[{"name":"Employee aggressor","address":"852 Office","ic_number":"840220-11-2345"}]',
'[{"name":"Colleague victim","address":"963 Same office","ic_number":"860710-09-8901"}]',
'HR department cooperating. Charges pending.', NULL, '2024-09-20', 'Ongoing', 1, '2024-08-18 11:30:00'),

('CASE-2024-029', '2024-08-25', 'RIB', 'GCR 08/2024', '2024-09-05', NULL,
'Public brawl', 'NO', 'PR-2024-044',
NULL, 'NO', 'NO',
'[{"name":"Brawler one","address":"147 Bar fight","ic_number":"910605-10-3456"},{"name":"Brawler two","address":"258 Bar fight","ic_number":"900820-07-6789"}]',
'[{"name":"Bar owner","address":"369 Establishment","ic_number":"720315-08-4567"}]',
'CCTV reviewed. Both parties charged.', 'Mediation successful - charges dropped', '2024-09-15', 'Closed', 1, '2024-08-25 23:50:00'),

('CASE-2024-030', '2024-09-01', 'GCIB II', 'GCR 09/2024', '2024-09-12', '2024-09-15',
'Kidnapping attempt', 'YES', 'PR-2024-045\nPR-2024-046',
'2024-09-25', 'YES', 'YES',
'[{"name":"Attempted kidnapper","address":"741 Suspect location","ic_number":"870410-12-7890"}]',
'[{"name":"Would-be victim","address":"852 Escaped","ic_number":"950920-06-5678"}]',
'Suspect apprehended. Serious charges filed.', NULL, '2024-10-10', 'Ongoing', 1, '2024-09-01 19:20:00'),

-- Case 31-40: Fraud and forgery cases
('CASE-2024-031', '2024-09-08', 'PIB', 'GCR 09/2024', '2024-09-18', NULL,
'Credit card fraud', 'YES', 'PR-2024-047',
'2024-10-01', 'YES', 'NO',
'[{"name":"Fraudster online","address":"963 Virtual","ic_number":"920725-08-2345"}]',
'[{"name":"Bank victim","address":"147 Financial Institution","ic_number":"Corporate"}]',
'Multiple transactions traced. Investigation ongoing.', NULL, '2024-10-15', 'Ongoing', 1, '2024-09-08 13:25:00'),

('CASE-2024-032', '2024-09-15', 'TIB', 'GCR 09/2024', '2024-09-25', '2024-09-28',
'Identity theft', 'YES', 'PR-2024-048',
'2024-10-10', 'YES', 'YES',
'[{"name":"Identity thief","address":"258 Fake address","ic_number":"850510-09-8901"}]',
'[{"name":"Real person","address":"369 Actual residence","ic_number":"850510-09-1234"}]',
'Documents forged. Cybercrime unit involved.', NULL, '2024-10-25', 'Ongoing', 1, '2024-09-15 15:10:00'),

('CASE-2024-033', '2024-09-22', 'AIB', 'GCR 09/2024', '2024-10-02', NULL,
'Check forgery', 'NO', 'PR-2024-049',
NULL, 'NO', 'YES',
'[{"name":"Forger suspect","address":"741 Location","ic_number":"880315-11-3456"}]',
'[{"name":"Bank teller","address":"852 Branch","ic_number":"760920-07-6789"}]',
'Handwriting analysis pending.', NULL, '2024-10-20', 'Pending', 1, '2024-09-22 10:40:00'),

('CASE-2024-034', '2024-10-01', 'RIB', 'GCR 10/2024', '2024-10-12', '2024-10-15',
'Insurance fraud', 'YES', 'PR-2024-050',
'2024-10-28', 'YES', 'NO',
'[{"name":"Claimant false","address":"963 Claim address","ic_number":"830605-10-4567"}]',
'[{"name":"Insurance investigator","address":"147 Company","ic_number":"720810-08-7890"}]',
'False claim detected. Charges prepared.', NULL, '2024-11-10', 'Ongoing', 1, '2024-10-01 09:00:00'),

('CASE-2024-035', '2024-10-08', 'GCIB III', 'GCR 10/2024', '2024-10-18', NULL,
'Investment scam', 'YES', 'PR-2024-051\nPR-2024-052',
'2024-11-01', 'NO', 'NO',
'[{"name":"Scammer leader","address":"258 Scam office","ic_number":"900425-12-5678"}]',
'[{"name":"Multiple victims","address":"Various locations","ic_number":"Various"}]',
'Ponzi scheme uncovered. Multiple complaints filed.', NULL, '2024-11-15', 'Ongoing', 1, '2024-10-08 16:55:00'),

('CASE-2024-036', '2024-10-15', 'MOIB', 'GCR 10/2024', '2024-10-25', '2024-10-28',
'Loan shark activity', 'YES', 'PR-2024-053',
'2024-11-10', 'YES', 'YES',
'[{"name":"Loan shark operator","address":"369 Underground","ic_number":"810920-09-2345"}]',
'[{"name":"Borrower victim","address":"741 Debt address","ic_number":"890510-11-8901"}]',
'Illegal lending operation shut down.', NULL, '2024-11-25', 'Ongoing', 1, '2024-10-15 12:15:00'),

('CASE-2024-037', '2024-10-22', 'VIB', 'GCR 10/2024', '2024-11-01', NULL,
'Counterfeit currency', 'YES', 'PR-2024-054',
'2024-11-15', 'YES', 'YES',
'[{"name":"Counterfeiter","address":"852 Print shop","ic_number":"870715-10-3456"}]',
'[{"name":"Shop owner victim","address":"963 Retail","ic_number":"750220-07-6789"}]',
'Printing equipment seized. Bank Negara notified.', NULL, '2024-12-01', 'Ongoing', 1, '2024-10-22 14:30:00'),

('CASE-2024-038', '2024-11-01', 'CIB I', 'GCR 11/2024', '2024-11-12', '2024-11-15',
'Romance scam online', 'NO', 'PR-2024-055',
NULL, 'NO', 'NO',
'[{"name":"Scammer online","address":"Virtual/Unknown","ic_number":"Unknown"}]',
'[{"name":"Victim lonely","address":"147 Local","ic_number":"820405-08-4567"}]',
'Cross-border case. Interpol contacted.', NULL, '2024-11-30', 'Pending', 1, '2024-11-01 11:00:00'),

('CASE-2024-039', '2024-11-08', 'CIB II', 'GCR 11/2024', '2024-11-18', NULL,
'Phishing email scam', 'YES', 'PR-2024-056',
'2024-12-01', 'YES', 'NO',
'[{"name":"Hacker traced","address":"258 IP Location","ic_number":"910625-09-7890"}]',
'[{"name":"Company victim","address":"369 Corporate","ic_number":"Corporate"}]',
'IT forensics ongoing. Multiple victims identified.', NULL, '2024-12-15', 'Ongoing', 1, '2024-11-08 10:20:00'),

('CASE-2024-040', '2024-11-15', 'EIB', 'GCR 11/2024', '2024-11-25', '2024-11-28',
'Property title fraud', 'YES', 'PR-2024-057\nPR-2024-058',
'2024-12-10', 'YES', 'YES',
'[{"name":"Fraudster documents","address":"741 False claim","ic_number":"860310-11-5678"}]',
'[{"name":"True owner","address":"852 Property","ic_number":"680815-10-2345"}]',
'Land office records examined. Case complex.', NULL, '2024-12-20', 'Ongoing', 1, '2024-11-15 13:45:00'),

-- Case 41-50: Miscellaneous crimes
('CASE-2024-041', '2024-11-22', 'CPUIB', 'GCR 11/2024', '2024-12-02', NULL,
'Arson investigation', 'YES', 'PR-2024-059',
'2024-12-15', 'NO', 'YES',
'[{"name":"Suspect arson","address":"963 Burned building","ic_number":"900520-12-8901"}]',
'[{"name":"Fire marshal","address":"Fire Department","ic_number":"730910-08-3456"}]',
'Fire investigation report pending.', NULL, '2025-01-05', 'Ongoing', 1, '2024-11-22 02:30:00'),

('CASE-2024-042', '2024-12-01', 'WCIB', 'GCR 12/2024', '2024-12-12', '2024-12-15',
'Vandalism of public property', 'NO', 'PR-2024-060',
NULL, 'NO', 'NO',
'[{"name":"Vandal young","address":"147 Neighborhood","ic_number":"050405-06-4567"}]',
'[{"name":"Witness neighbor","address":"258 Same area","ic_number":"780920-09-6789"}]',
'CCTV captured incident. Minor charged.', 'Community service ordered', '2025-01-10', 'Closed', 1, '2024-12-01 20:15:00'),

('CASE-2024-043', '2024-12-08', 'RIB', 'GCR 12/2024', '2024-12-18', NULL,
'Trespassing on private property', 'NO', 'PR-2024-061',
NULL, 'NO', 'NO',
'[{"name":"Trespasser","address":"369 Unknown","ic_number":"920715-10-7890"}]',
'[{"name":"Property owner","address":"741 Private land","ic_number":"650320-07-5678"}]',
'Warning issued. Case pending.', NULL, '2024-12-30', 'Pending', 1, '2024-12-08 17:00:00'),

('CASE-2024-044', '2024-12-15', 'GCIB I', 'GCR 12/2024', '2024-12-25', '2024-12-28',
'Wildlife trafficking', 'YES', 'PR-2024-062\nPR-2024-063',
'2025-01-10', 'YES', 'YES',
'[{"name":"Trafficker animals","address":"852 Illegal trade","ic_number":"850910-08-2345"}]',
'[{"name":"Wildlife officer","address":"Department","ic_number":"720505-11-8901"}]',
'Protected species recovered. Major case.', NULL, '2025-01-25', 'Ongoing', 1, '2024-12-15 09:40:00'),

('CASE-2024-045', '2024-12-20', 'MOIB', 'GCR 12/2024', '2024-12-30', NULL,
'Illegal gambling operation', 'YES', 'PR-2024-064',
'2025-01-15', 'YES', 'NO',
'[{"name":"Operator gambling","address":"963 Hidden location","ic_number":"880425-09-3456"}]',
'[{"name":"Raid officer","address":"Police unit","ic_number":"750810-12-6789"}]',
'Raid conducted. Equipment seized.', NULL, '2025-02-01', 'Ongoing', 1, '2024-12-20 22:00:00'),

('CASE-2024-046', '2024-12-25', 'VIB', 'GCR 12/2024', '2025-01-05', '2025-01-08',
'Human trafficking', 'YES', 'PR-2024-065\nPR-2024-066\nPR-2024-067',
'2025-01-20', 'YES', 'YES',
'[{"name":"Trafficker main","address":"147 Safe house","ic_number":"810620-10-4567"},{"name":"Accomplice","address":"258 Transport","ic_number":"830915-07-7890"}]',
'[{"name":"Rescued victims","address":"Protected shelter","ic_number":"Multiple"}]',
'Multiple victims rescued. International case.', NULL, '2025-02-10', 'Ongoing', 1, '2024-12-25 15:30:00'),

('CASE-2025-001', '2025-01-02', 'CIB III', 'GCR 01/2025', '2025-01-12', NULL,
'Environmental pollution', 'YES', 'PR-2025-001',
'2025-01-25', 'NO', 'YES',
'[{"name":"Factory owner","address":"369 Industrial zone","ic_number":"760305-08-5678"}]',
'[{"name":"Environmental officer","address":"DOE","ic_number":"700920-11-2345"}]',
'Samples collected. Analysis ongoing.', NULL, '2025-02-15', 'Ongoing', 1, '2025-01-02 11:15:00'),

('CASE-2025-002', '2025-01-05', 'AIB', 'GCR 01/2025', '2025-01-15', '2025-01-18',
'Bribery and corruption', 'YES', 'PR-2025-002',
'2025-01-30', 'YES', 'NO',
'[{"name":"Official corrupt","address":"741 Government office","ic_number":"820510-09-8901"}]',
'[{"name":"Whistleblower","address":"Protected","ic_number":"Protected"}]',
'MACC involved. Investigation ongoing.', NULL, '2025-02-20', 'Ongoing', 1, '2025-01-05 14:50:00'),

('CASE-2025-003', '2025-01-06', 'PIB', 'GCR 01/2025', '2025-01-16', NULL,
'Illegal weapons possession', 'YES', 'PR-2025-003',
'2025-02-01', 'YES', 'YES',
'[{"name":"Gun owner illegal","address":"852 Hidden cache","ic_number":"870725-10-3456"}]',
'[{"name":"Informant tip","address":"Anonymous","ic_number":"Anonymous"}]',
'Weapons seized. Serious charges filed.', NULL, '2025-02-25', 'Ongoing', 1, '2025-01-06 19:20:00'),

('CASE-2025-004', '2025-01-07', 'TIB', 'GCR 01/2025', NULL, NULL,
'Missing person case', 'NO', 'PR-2025-004',
NULL, 'NO', 'NO',
'[]',
'[{"name":"Family member","address":"963 Home","ic_number":"680415-07-6789"},{"name":"Friend last seen","address":"147 Location","ic_number":"850920-12-4567"}]',
'Search operation in progress. Media appeal issued.', NULL, '2025-01-15', 'Ongoing', 1, '2025-01-07 08:00:00');

-- Update activity logs for case additions
INSERT INTO `activity_logs` (`user_id`, `user_name`, `activity_type`, `description`, `ip_address`, `user_agent`, `created_at`)
VALUES (1, 'System Admin', 'case_added', '50 sample cases added to the system for testing purposes', '127.0.0.1', 'SQL Script', NOW());

-- Summary comment
-- ========================================================
-- SUMMARY:
-- - Total cases inserted: 50
-- - Case numbers: CASE-2024-001 to CASE-2025-004
-- - Date range: January 2024 to January 2025
-- - Status breakdown:
--   * Ongoing: 42 cases
--   * Pending: 5 cases  
--   * Closed: 3 cases
-- - Various crime categories:
--   * Theft and Robbery (10 cases)
--   * Drug-related offenses (10 cases)
--   * Assault and violent crimes (10 cases)
--   * Fraud and forgery (10 cases)
--   * Miscellaneous crimes (10 cases)
-- - All cases assigned to user_id: 1 (Admin User)
-- - Includes suspects, witnesses, and evidence data
-- - Realistic progress notes and case details
-- ========================================================
