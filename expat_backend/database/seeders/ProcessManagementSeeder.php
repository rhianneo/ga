<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApplicationStep;

class ProcessManagementSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [

            // New Application
            [
                'application_type' => 'New Application',
                'step_name' => 'Visa Extension',
                'required_docs' => [
                    'BI form (2 copies)',
                    'Photocopy of passport',
                    'Original passport'
                ],
                'order' => 1,
            ],
            [
                'application_type' => 'New Application',
                'step_name' => 'AEP Application',
                'required_docs' => [
                    'Application Form, 2x2 ID picture, expiring AEP, passport bio-page',
                    'BIR Form No. 1902',
                    'Contract of Employment',
                    'CTC of PEZA registration, SEC registration documents (CTC, GIS, AOI & By-laws)',
                    'Job vacancy proof/published (PESO, Sunstar, & PhilJobNet)',
                    'CTC of DOLE accreditation, DOLE ID pass',
                    'Board resolution / secretary certificate (corporation)',
                    'Understudy or skills development program',
                    'Payment of AEP application fee',
                    'Submission to DOLE and AEP card release'
                ],
                'order' => 2,
            ],
            [
                'application_type' => 'New Application',
                'step_name' => 'PV VISA Application',
                'required_docs' => [
                    'Checklist for PEZA, application form for principal, letter request, secretary\'s certificate',
                    'Comprehensive bio-data / resume / curriculum vitae, organizational chart',
                    'Submission of PEZA validation (order payment)',
                    'PRF payment (accounting)',
                    'Proof of payment',
                    'Sending of documents to PEZA-FNU & BI (Pan Malayan Agency)',
                    'Processing of documents for PEZA-FNU & BI (Pan Malayan Agency)',
                    'Sending of documents to Cebu from PEZA-FNU & BI (Pan Malayan Agency)'
                ],
                'order' => 3,
            ],

            // Renewal Application
            [
                'application_type' => 'Renewal Application',
                'step_name' => 'AEP Application',
                'required_docs' => [
                    'Application Form, 2x2 ID picture, expiring AEP, passport bio-page',
                    'BIR Form No. 1902',
                    'Contract of Employment',
                    'CTC of PEZA registration, SEC registration documents (CTC, GIS, AOI & By-laws)',
                    'Job vacancy proof/published (PESO, Sunstar, & PhilJobNet)',
                    'CTC of DOLE accreditation, DOLE ID pass',
                    'Board resolution / secretary certificate (corporation)',
                    'Understudy or skills development program',
                    'Payment of AEP application fee',
                    'Submission to DOLE and AEP card release'
                ],
                'order' => 1,
            ],
            [
                'application_type' => 'Renewal Application',
                'step_name' => 'PV VISA Application',
                'required_docs' => [
                    'Checklist for PEZA, application form for principal, letter request, secretary\'s certificate',
                    'Comprehensive bio-data / resume / curriculum vitae, organizational chart',
                    'Submission of PEZA validation (order payment)',
                    'PRF payment (accounting)',
                    'Proof of payment',
                    'Sending of documents to PEZA-FNU & BI (Pan Malayan Agency)',
                    'Processing of documents for PEZA-FNU & BI (Pan Malayan Agency)',
                    'Sending of documents to Cebu from PEZA-FNU & BI (Pan Malayan Agency)'
                ],
                'order' => 2,
            ],

            // Downgrading and Cancellation
            [
                'application_type' => 'Cancellation and Downgrading',
                'step_name' => 'Cancellation of PEZA VISA',
                'required_docs' => [
                    'Request letter for cancellation of PEZA VISA, SPA, secretary\'s certificate',
                    'Submit documents to PEZA (Pan Malayan Agency)',
                    'PEZA cancellation processing time',
                    'Air ticket'
                ],
                'order' => 1,
            ],
            [
                'application_type' => 'Cancellation and Downgrading',
                'step_name' => 'Downgrading of PEZA VISA',
                'required_docs' => [
                    'Company letter request (certificate of end of employment), letter request for downgrading (PV VISA), SPA, secretary\'s certificate, resignation letter',
                    'Submit downgrading documents to PEZA (Pan Malayan Agency)',
                    'PEZA downgrading processing time',
                    'Sending of documents to Cebu from PEZA (Pan Malayan Agency)',
                    'Exit clearance'
                ],
                'order' => 2,
            ],

        ];

        foreach ($steps as $step) {
            ApplicationStep::create($step);
        }
    }
}
