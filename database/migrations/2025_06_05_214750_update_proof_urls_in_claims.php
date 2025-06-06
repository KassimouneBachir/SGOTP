<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Claim;

class UpdateProofUrlsInClaims extends Migration
{
    public function up()
    {
        $claims = Claim::whereNotNull('proof_url')->get();
        foreach ($claims as $claim) {
            if (!str_starts_with($claim->getRawOriginal('proof_url'), 'storage/')) {
                $claim->proof_url = 'storage/proofs/' . $claim->getRawOriginal('proof_url');
                $claim->save();
            }
        }
    }

    public function down()
    {
        $claims = Claim::whereNotNull('proof_url')->get();
        foreach ($claims as $claim) {
            if (str_starts_with($claim->getRawOriginal('proof_url'), 'storage/proofs/')) {
                $claim->proof_url = str_replace('storage/proofs/', '', $claim->getRawOriginal('proof_url'));
                $claim->save();
            }
        }
    }
}