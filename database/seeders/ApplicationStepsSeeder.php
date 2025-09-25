<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationStepsSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [

            // ===============================
            // NEW APPLICATION
            // ===============================
            // Visa Extension
            [
                'application_type'=>'New',
                'step_name'=>'Visa Extension: BI Form 2 copies, Photocopy of Passport, Original Passport',
                'plan_days'=>2,
                'order'=>1
            ],

            // AEP Application
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Application Form, 2x2 ID, Expiring AEP, Passport bio-page',
                'plan_days'=>1,
                'order'=>2
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: BIR Requirements: Contract of Employment, SPA, Authorization Letter, BIR Form No. 1902 2 copies',
                'plan_days'=>2,
                'order'=>3
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Preparation For Publication Requirements Application Letter, Career Job Opportunity, NSRP form',
                'plan_days'=>3,
                'order'=>4
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet)',
                'plan_days'=>15,
                'order'=>5,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Notarized of No Qualified Applicant',
                'plan_days'=>1,
                'order'=>6
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: CTC of DOLE Accreditation, DOLE ID Pass',
                'plan_days'=>1,
                'order'=>7
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: CTC of PEZA Registration, SEC Registration Docs (CTC, GIS, AOI & By-Laws)',
                'plan_days'=>2,
                'order'=>8
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Board Resolution / Sec Cert (Corp)',
                'plan_days'=>1,
                'order'=>9
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Understudy or Skills Development Program',
                'plan_days'=>3,
                'order'=>10
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: DOLE Form and collect all necessary document to be submitted',
                'plan_days'=>2,
                'order'=>11
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Submission to DOLE and Evaluation of Docs by DOLE personnel',
                'plan_days'=>10,
                'order'=>12
            ],
            [
                'application_type'=>'New',
                'step_name'=>'AEP Application: Payment application fee and Releasing of AEP Card',
                'plan_days'=>1,
                'order'=>13
            ],

            // PV VISA Application
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Checklist for PEZA, Application form for Principal, Letter Request, Secretary\'s Certificate',
                'plan_days'=>5,
                'order'=>14,
                'parallel_group'=>1 // parallel with Job Vacancy Proof
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Comprehensive Bio-data, Organizational Chart',
                'plan_days'=>2,
                'order'=>15,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Submission of PEZA Validation (Order Payment)',
                'plan_days'=>3,
                'order'=>16,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: PRF Payment (Accounting)',
                'plan_days'=>7,
                'order'=>17,
                'depends_on'=>13 // depends on AEP Payment step
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Proof of Payment',
                'plan_days'=>5,
                'order'=>18,
                'depends_on'=>13
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Sending of Documents to PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>5,
                'order'=>19,
                'depends_on'=>13
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Processing of Documents for PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>15,
                'order'=>20,
                'depends_on'=>13
            ],
            [
                'application_type'=>'New',
                'step_name'=>'PV Visa: Sending Documents to Cebu from PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>5,
                'order'=>21,
                'depends_on'=>13
            ],

            // ===============================
            // RENEWAL APPLICATION
            // ===============================
            // AEP Application
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Application Form, 2x2 ID, Expiring AEP, Passport bio-page',
                'plan_days'=>1,
                'order'=>1
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: BIR Requirements: Contract of Employment, SPA, Authorization Letter, BIR Form No. 1902 2 copies',
                'plan_days'=>2,
                'order'=>2
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Preparation For Publication Requirements Application Letter, Career Job Opportunity, NSRP form',
                'plan_days'=>3,
                'order'=>3
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet)',
                'plan_days'=>15,
                'order'=>4,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Notarized of No Qualified Applicant',
                'plan_days'=>1,
                'order'=>5
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: CTC of DOLE Accreditation, DOLE ID Pass',
                'plan_days'=>1,
                'order'=>6
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: CTC of PEZA Registration, SEC Registration Docs (CTC, GIS, AOI & By-Laws)',
                'plan_days'=>2,
                'order'=>7
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Board Resolution / Sec Cert (Corp)',
                'plan_days'=>1,
                'order'=>8
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Understudy or Skills Development Program',
                'plan_days'=>3,
                'order'=>9
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: DOLE Form and collect all necessary document to be submitted',
                'plan_days'=>2,
                'order'=>10
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Submission to DOLE and Evaluation of Docs by DOLE personnel',
                'plan_days'=>10,
                'order'=>11
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'AEP Application: Payment application fee and Releasing of AEP Card',
                'plan_days'=>1,
                'order'=>12
            ],

            // PV VISA Application
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Checklist for PEZA, Application form for Principal, Letter Request, Secretary\'s Certificate',
                'plan_days'=>5,
                'order'=>13,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Comprehensive Bio-data, Organizational Chart',
                'plan_days'=>2,
                'order'=>14,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Submission of PEZA Validation (Order Payment)',
                'plan_days'=>3,
                'order'=>15,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: PRF Payment (Accounting)',
                'plan_days'=>7,
                'order'=>16,
                'depends_on'=>12
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Proof of Payment',
                'plan_days'=>5,
                'order'=>17,
                'depends_on'=>12
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Sending of Documents to PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>5,
                'order'=>18,
                'depends_on'=>12
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Processing of Documents for PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>15,
                'order'=>19,
                'depends_on'=>12
            ],
            [
                'application_type'=>'Renewal',
                'step_name'=>'PV Visa: Sending Documents to Cebu from PEZA-FNU & BI thru Pan Malayan Agency',
                'plan_days'=>5,
                'order'=>20,
                'depends_on'=>12
            ],

            // ===============================
            // CANCELLATION & DOWNGRADING
            // ===============================
            // AEP Cancellation
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'AEP Cancellation: Request Letter, SPA, Secretary\'s Certificate',
                'plan_days'=>10,
                'order'=>1,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'AEP Cancellation: Submit Documents to PEZA',
                'plan_days'=>5,
                'order'=>2
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'AEP Cancellation: PEZA Cancellation Processing Time',
                'plan_days'=>15,
                'order'=>3
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'AEP Cancellation: Air Ticket',
                'plan_days'=>5,
                'order'=>4
            ],

            // Downgrading
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'Downgrading: Company Letter Request, Letter Request for Downgrading (PV VISA), SPA, Secretary\'s Certificate, Resignation Letter',
                'plan_days'=>10,
                'order'=>5,
                'parallel_group'=>1
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'Downgrading: Submit Downgrading Documents to PEZA',
                'plan_days'=>5,
                'order'=>6
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'Downgrading: PEZA Downgrading Processing Time',
                'plan_days'=>15,
                'order'=>7
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'Downgrading: Sending Documents to Cebu from PEZA',
                'plan_days'=>5,
                'order'=>8
            ],
            [
                'application_type'=>'CancellationDowngrade',
                'step_name'=>'Downgrading: Exit Clearance',
                'plan_days'=>2,
                'order'=>9
            ],

        ];

        DB::table('application_steps')->insert($steps);
    }
}
