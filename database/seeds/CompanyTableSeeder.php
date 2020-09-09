<?php

use App\Company;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company = [
            [
                'id'             => 1,
                'compCode'       => 'EPCS',
                'compDesc'       => 'ENG PENG COLD STORAGE SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 2,
                'compCode'       => 'KFSB',
                'compDesc'       => 'KOBOS FARM SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 3,
                'compCode'       => 'EPPF',
                'compDesc'       => 'ENG PENG POULTRY FARM SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 4,
                'compCode'       => 'SMESB',
                'compDesc'       => 'SALAM MARKETING ENTERPRISE SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 5,
                'compCode'       => 'AI',
                'compDesc'       => 'LADANG TERNAKAN AYAM INDUK KOTA KINABALU SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 6,
                'compCode'       => 'JDSB',
                'compDesc'       => 'JADIMA SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 7,
                'compCode'       => 'JNSB',
                'compDesc'       => 'JUA NIKMAT SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 8,
                'compCode'       => 'PDUSB',
                'compDesc'       => 'PERUSAHAAN DAYA USAHA SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 9,
                'compCode'       => 'EPSB',
                'compDesc'       => 'EDEN PERFECT SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 10,
                'compCode'       => 'IISB',
                'compDesc'       => 'IMPIAN INTERAKTIF SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 11,
                'compCode'       => 'LASB',
                'compDesc'       => 'LAGENDA AMANJAYA SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
            [
                'id'             => 12,
                'compCode'       => 'SGSB',
                'compDesc'       => 'SALAM GLOBAL SDN BHD',
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ],
        ];
        
        Company::insert($company);
    }
}
